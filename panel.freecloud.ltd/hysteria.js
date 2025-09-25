#!/usr/bin/env node
// -*- coding: utf-8 -*-
// 极简部署脚本（Node.js版本，spawn 启动进程以释放 Node.js 占用）
// 适用于 超低内存 设备（尽量小心：32MB 极限环境仍可能不够）

const os = require('os');
const fs = require('fs');
const path = require('path');
const https = require('https');
const { spawn, execSync } = require('child_process');

// ---------- 配置（请按需修改） ----------
const HYSTERIA_VERSION = 'v2.6.3';
const SERVER_PORT = 22222; // 端口这里填你面板的端口
const AUTH_PASSWORD = '20250922'; // 强烈建议改成更复杂的密码
// 如果能提前把 cert.pem/key.pem 放到设备上，会更可靠、减少运行时步骤
const CERT_FILE = 'cert.pem';
const KEY_FILE = 'key.pem';
// 下载重试次数
const RETRIES = 2;
// ---------------------------------------

console.log('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
console.log('Hysteria 极简部署脚本 - Node.js 版本');
console.log('适用于超低内存环境（32-64MB）');
console.log('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');

function archName() {
  const arch = os.arch();
  const machine = arch.toLowerCase();

  if (machine.includes('arm64') || machine.includes('aarch64')) {
    return 'arm64';
  }
  if (machine.includes('x64') || machine.includes('x86_64') || machine.includes('amd64')) {
    return 'amd64';
  }
  // 如果识别失败，返回 null
  return null;
}

function downloadBinary(dest) {
  return new Promise((resolve, reject) => {
    const arch = archName();
    if (!arch) {
      console.error('❌ 无法识别 CPU 架构:', os.arch());
      process.exit(1);
    }

    const binName = `hysteria-linux-${arch}`;

    if (fs.existsSync(dest)) {
      console.log('✅ 二进制已存在，跳过下载。');
      resolve();
      return;
    }

    const url = `https://github.com/apernet/hysteria/releases/download/app/${HYSTERIA_VERSION}/${binName}`;
    console.log('⏳ 下载:', url);

    let retryCount = 0;

    function attemptDownload() {
      const request = https.get(url, { timeout: 30000 }, (response) => {
        if (response.statusCode >= 400) {
          console.log(`下载失败，HTTP状态: ${response.statusCode}，重试: ${retryCount}`);
          if (retryCount < RETRIES) {
            retryCount++;
            setTimeout(attemptDownload, 1000);
            return;
          }
          reject(new Error(`HTTP status ${response.statusCode}`));
          return;
        }

        const fileStream = fs.createWriteStream(dest);
        response.pipe(fileStream);

        fileStream.on('finish', () => {
          fileStream.close();
          // 设置可执行权限
          fs.chmodSync(dest, fs.statSync(dest).mode | parseInt('111', 8));
          console.log('✅ 下载完成并设置可执行:', dest);
          resolve();
        });

        fileStream.on('error', (err) => {
          fs.unlinkSync(dest);
          console.log('下载失败，重试:', retryCount, 'err:', err.message);
          if (retryCount < RETRIES) {
            retryCount++;
            setTimeout(attemptDownload, 1000);
          } else {
            reject(err);
          }
        });
      });

      request.on('error', (err) => {
        console.log('下载失败，重试:', retryCount, 'err:', err.message);
        if (retryCount < RETRIES) {
          retryCount++;
          setTimeout(attemptDownload, 1000);
        } else {
          reject(err);
        }
      });

      request.on('timeout', () => {
        request.destroy();
        console.log('下载超时，重试:', retryCount);
        if (retryCount < RETRIES) {
          retryCount++;
          setTimeout(attemptDownload, 1000);
        } else {
          reject(new Error('Download timeout'));
        }
      });
    }

    attemptDownload();
  });
}

function ensureCert() {
  return new Promise((resolve, reject) => {
    // 如果证书已存在，就直接返回
    if (fs.existsSync(CERT_FILE) && fs.existsSync(KEY_FILE)) {
      console.log('✅ 发现证书，使用现有 cert/key。');
      resolve();
      return;
    }

    // 尽量使用 openssl 但不捕获大量输出
    console.log('🔑 未发现证书，尝试使用 openssl 生成自签 ECDSA 证书（prime256v1）...');

    const cmd = [
      'openssl',
      'req',
      '-x509',
      '-nodes',
      '-newkey',
      'ec',
      '-pkeyopt',
      'ec_paramgen_curve:prime256v1',
      '-days',
      '3650',
      '-keyout',
      KEY_FILE,
      '-out',
      CERT_FILE,
      '-subj',
      '/CN=localhost',
    ];

    try {
      const result = execSync(cmd.join(' '), { stdio: 'inherit' });
      console.log('✅ 证书生成成功。');
      resolve();
    } catch (error) {
      if (error.code === 'ENOENT') {
        console.error('❌ 未找到 openssl，可考虑先在其它机器生成证书并拷贝到设备。');
      } else {
        console.error('❌ 证书生成失败:', error.message);
      }
      reject(error);
    }
  });
}

function writeConfig() {
  // 极限内存配置：更小的窗口和更低带宽，短空闲超时，少并发流
  const cfg = `listen: ":${SERVER_PORT}"
tls:
  cert: "${path.resolve(CERT_FILE)}"
  key: "${path.resolve(KEY_FILE)}"
auth:
  type: "password"
  password: "${AUTH_PASSWORD}"
bandwidth:
  up: "200mbps"
  down: "200mbps"
quic:
  max_idle_timeout: "10s"
  max_concurrent_streams: 4
  initial_stream_receive_window: 65536        # 64 KB
  max_stream_receive_window: 131072           # 128 KB
  initial_conn_receive_window: 131072         # 128 KB
  max_conn_receive_window: 262144             # 256 KB
`;

  fs.writeFileSync('server.yaml', cfg);
  console.log('✅ 写入配置 server.yaml（极小化）。');
}

function getServerIP() {
  return new Promise((resolve) => {
    console.log('🌐 获取服务器 IP 地址...');
    const request = https.get('https://api.ipify.org', { timeout: 10000 }, (response) => {
      let data = '';
      response.on('data', (chunk) => {
        data += chunk;
      });
      response.on('end', () => {
        const ip = data.trim();
        console.log(`✅ 服务器 IP: ${ip}`);
        resolve(ip);
      });
    });

    request.on('error', (error) => {
      console.log(`❌ 获取 IP 失败: ${error.message}`);
      console.log('💡 使用默认 IP 占位符');
      resolve('YOUR_SERVER_IP');
    });

    request.on('timeout', () => {
      request.destroy();
      console.log('❌ 获取 IP 超时');
      console.log('💡 使用默认 IP 占位符');
      resolve('YOUR_SERVER_IP');
    });
  });
}

function printConnectionInfo(serverIP) {
  console.log('🎉 Hysteria2 部署成功！（极简优化版）');
  console.log('' + '='.repeat(75));
  console.log('='.repeat(75));
  console.log('📋 服务器信息:');
  console.log(`   🌐 IP地址: ${serverIP}`);
  console.log(`   🔌 端口: ${SERVER_PORT}`);
  console.log(`   🔑 密码: ${AUTH_PASSWORD}`);
  console.log(`   💾 内存优化: 极简配置`);
  console.log('');
  console.log('📱 节点链接（标准）:');
  console.log(`hysteria2://${AUTH_PASSWORD}@${serverIP}:${SERVER_PORT}?sni=localhost#Hy2-Minimal`);
  console.log('');
  console.log('📱 节点链接（跳过证书验证）:');
  console.log(`hysteria2://${AUTH_PASSWORD}@${serverIP}:${SERVER_PORT}?insecure=1#Hy2-Minimal-Fix`);
  console.log('');
  console.log('📄 客户端配置文件:');
  console.log(`server: ${serverIP}:${SERVER_PORT}`);
  console.log(`auth: ${AUTH_PASSWORD}`);
  console.log('tls:');
  console.log('  sni: localhost');
  console.log('  insecure: true');
  console.log('socks5:');
  console.log('  listen: 127.0.0.1:1080');
  console.log('http:');
  console.log('  listen: 127.0.0.1:8080');
  console.log('='.repeat(75));
  console.log('');
}

async function main() {
  try {
    console.log('🚀 极简部署启动（Node.js版）');

    const arch = archName();
    if (!arch) {
      console.error('❌ 无法识别架构，退出。');
      process.exit(1);
    }

    const binFile = `hysteria-linux-${arch}`;

    // 下载（如果不存在）
    await downloadBinary(binFile);
    await ensureCert();
    writeConfig();

    // 获取服务器IP并输出连接信息
    const serverIP = await getServerIP();
    printConnectionInfo(serverIP);

    // 启动二进制文件，使用 spawn 以减少内存占用
    const binPath = path.resolve(`./${binFile}`);
    const args = ['server', '-c', 'server.yaml'];

    console.log('🚀 启动 hysteria 服务器:', binPath, args.join(' '));

    const child = spawn(binPath, args, {
      stdio: 'inherit', // 继承父进程的 stdio
      detached: false, // 不分离进程
    });

    child.on('error', (err) => {
      console.error('❌ 启动失败:', err.message);
      process.exit(1);
    });

    child.on('exit', (code, signal) => {
      if (signal) {
        console.log(`进程被信号终止: ${signal}`);
      } else {
        console.log(`进程退出，代码: ${code}`);
      }
      process.exit(code || 0);
    });

    // 处理进程信号
    process.on('SIGINT', () => {
      console.log('\n正在关闭服务器...');
      child.kill('SIGINT');
    });

    process.on('SIGTERM', () => {
      console.log('\n正在关闭服务器...');
      child.kill('SIGTERM');
    });
  } catch (error) {
    console.error('❌ 部署失败:', error.message);
    process.exit(1);
  }
}

// 启动主函数
if (require.main === module) {
  main();
}

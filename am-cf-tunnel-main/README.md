# 🚀[am-cf-tunnel](https://github.com/amclubs/am-cf-tunnel)
这是一个基于 Cloudflare Workers 和 Pages平台的脚本，在原版的基础上修改了显示 VLESS 配置信息转换为订阅内容。使用该脚本，你可以方便地将 VLESS 配置信息使用在线配置转换到 Clash、 Singbox 、Quantumult X等工具中订阅使用。Cloudflare Workers 和 Pages 生成VLESS节点,实现一键订阅节点。[最新视频教程](https://youtu.be/emEBm8Gw2wI) | [NAT64版教程](https://youtu.be/nx80sGpVoBM)

#
▶️ **新人[YouTube](https://youtube.com/@am_clubs?sub_confirmation=1)** 需要您的支持，请务必帮我**点赞**、**关注**、**打开小铃铛**，***十分感谢！！！*** ✅
</br>🎁请 **follow** 我的[GitHub](https://github.com/amclubs)、给我所有项目一个 **Star** 星星（拜托了）！你的支持是我不断前进的动力！ 💖
</br>✅**解锁更多技能** [加入TG群【am_clubs】](https://t.me/am_clubs)、[YouTube频道【@am_clubs】](https://youtube.com/@am_clubs?sub_confirmation=1)、[【博客(国内)】](https://amclubss.com)、[【博客(国际)】](https://amclubs.blogspot.com) 
</br>✅点击观看教程[CLoudflare免费节点](https://www.youtube.com/playlist?list=PLGVQi7TjHKXbrY0Pk8gm3T7m8MZ-InquF) | [VPS搭建节点](https://www.youtube.com/playlist?list=PLGVQi7TjHKXaVlrHP9Du61CaEThYCQaiY) | [获取免费域名](https://www.youtube.com/playlist?list=PLGVQi7TjHKXZGODTvB8DEervrmHANQ1AR) | [免费VPN](https://www.youtube.com/playlist?list=PLGVQi7TjHKXY7V2JF-ShRSVwGANlZULdk) | [IPTV源](https://www.youtube.com/playlist?list=PLGVQi7TjHKXbkozDYVsDRJhbnNaEOC76w) | [Mac和Win工具](https://www.youtube.com/playlist?list=PLGVQi7TjHKXYBWu65yP8E08HxAu9LbCWm) | [AI分享](https://www.youtube.com/playlist?list=PLGVQi7TjHKXaodkM-mS-2Nwggwc5wRjqY)

# 🎬推荐视频教程
- [Error1101和522解决教程](https://youtu.be/4fcyJjstFdg) | [优选IP和反代IP教程](https://youtu.be/pKrlfRRB0gU) | [常见-1问题教程](https://youtu.be/kYQxV1G-ePw) | [免费域名教程](https://www.youtube.com/playlist?list=PLGVQi7TjHKXZGODTvB8DEervrmHANQ1AR)
- [VLESS免费节点部署教程](https://youtu.be/dPH63nITA0M) | [Trojan免费节点部署教程](https://youtu.be/uh27CVVi6HA) | [从入门到精通免费部署教程](https://youtu.be/ag12Rpc9KP4)| [高级固定节点区域教程](https://youtu.be/wgeM9XvZ5RA)
- [GitHub私有储优选IP文教程](https://youtu.be/vX3U3FuuTT8) | [CF免费KV存储IP文件教程](https://youtu.be/dzxezRV1v-o)  | [获取CF自家域名无限节点](https://youtu.be/novrPiMsK70) | [聚合节点订阅教程](https://youtu.be/YBO2hf96150)
- [🔥amclubs-cfnat自动优先IP(Win桌面版)](https://youtu.be/-a6NJ6vPSu4) | [Linux&openwrt软路由版](https://youtu.be/ZC6fxZwPaiM) | [Mac版](https://youtu.be/gf6gncc2yEE) | [安卓(Android)手机版](https://youtu.be/7yamDM38MFw) | [docker版](https://youtu.be/gRnNwoeUQKU) 

## 📝一、需要准备的前提资料
<details>
<summary>点击展开/收起</summary>

### 1、注册免费**cloudflare**帐号(邮箱就可以免费注册)
- 注册地址：https://cloudflare.com <a href="https://youtu.be/ITeuSbHVQ2E">[点击观看视频教程]</a>

### 2、注册**免费域名** [点击观看所有免费域名视频教程](https://www.youtube.com/playlist?list=PLGVQi7TjHKXZGODTvB8DEervrmHANQ1AR)

### 3、**订阅工具** [点击观看使用视频教程](https://youtu.be/xGOL57cmvaw)
👉 [点击加入TG群 数字套利｜交流群](https://t.me/AM_CLUBS)发送关键字 **工具** 获取下载

### 4、Cloudflare标准 **端口** 知识  [点击观看优选IP视频教程](https://youtu.be/pKrlfRRB0gU)
- 80系端口(HTTP)：80，8080，8880，2052，2082，2086，2095
- 443系端口(HTTPS)：443，2053，2083，2087，2096，8443
- [IP落地测试工具地址](https://ip.sb/) 

</details>

## ⚙️ 二、Workers 部署方法 [视频教程](https://www.youtube.com/watch?v=wgeM9XvZ5RA&t=195s)
<details>
<summary>点击展开/收起</summary>

1. 部署 Cloudflare Worker：
   - 在 Cloudflare Worker 控制台中创建一个新的 Worker。
   - 将 [_worker.js](https://github.com/amclubs/am-cf-tunnel/blob/main/_worker.js) 的内容粘贴到 Worker 编辑器中。
2. 给 workers绑定 自定义域： [免费域名申请教程](https://www.youtube.com/playlist?list=PLGVQi7TjHKXZGODTvB8DEervrmHANQ1AR)
   - 在 workers控制台的 `设置` 选项卡 -> 点击 `域和路由` -> 右方点击 -> `添加` -> 选择 `自定义域`。
   - 填入你已转入 CloudFlare 域名 (amclubss.com) 解析服务的次级域名，例如:`vless.amclubss.com`后 点击 `添加域`，等待证书生效即可。
3. 给UUID设置KV存储桶(可选项，推荐设置)： 
   - 在 CloudFlare主页的左边菜单的 ` 存储和数据库` 选项卡 -> 展开选择点击 `KV` -> 右方点击 -> `创建` -> 填入 `命名空间名称`(此名称自己命名) 后 -> 点击 `添加`。(此步已有可忽略)
   - 在 workers控制台的 `设置` 选项卡 -> 点击 `绑定` -> 右方点击 -> `添加` -> 选择 `KV 命名空间` -> 变量名称 填入 `amclubs`(此名称固定不能变) -> KV 命名空间 选择 在上面创建的 `命名空间名称`后 -> 右下方点击 `部署`。
4. 访问订阅内容：
   - 访问 `https://[YOUR-WORKERS-URL]/[UUID]` 即可获取订阅内容（默认UUID是：d0298536-d670-4045-bbb1-ddd5ea68683e）。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?sub` 就是你的通用自适应订阅地址(Quantumult X、Clash、singbox、小火箭、v2rayN、v2rayU、surge、PassWall、SSR+、Karing等)。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?base64` Base64订阅格式，适用PassWall,SSR+等。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?clash` Clash订阅格式，适用OpenClash等。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?singbox` singbox订阅格式，适用singbox等。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?sub&IP_URL=https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/ipUrl.txt` 自动义变量等参数。
5. 修改默认UUID变量，使用KV存储桶(可选项，推荐修改，防止别人用你节点)： 
   - 访问 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e/ui` 即可进入修改UUID页面 
   - 在UUID页面UUID项 -> 填入 `新的UUID` 后,[在线获取UUID](https://1024tools.com/uuid) -> 点击 `Save`。
   - 保存成功后，原UUID已作废不能访问，用新UUID访问  `https://vless.amclubss.com/新的UUID` 即可获取订阅内容。
   
</details>

## 📦三、Pages 上传 部署方法 **最佳推荐!!!** [视频教程](https://www.youtube.com/watch?v=wgeM9XvZ5RA&t=1203s)
 <details>
<summary>点击展开/收起</summary>
    
1. 部署 Cloudflare Pages：
   - 下载 [_worker.js.zip](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/_worker.js.zip) 文件，并点上 Star !!!
   - 在 Cloudflare Pages 控制台中选择 `上传资产`后，为你的项目取名后点击 `创建项目`，然后上传你下载好的 [_worker.js.zip](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/_worker.js.zip) 文件后点击 `部署站点`。
2. 给 Pages绑定 CNAME自定义域：[无域名绑定Cloudflare部署视频教程]->[免费域名教程1](https://youtu.be/wHJ6TJiCF0s) [免费域名教程2](https://youtu.be/yEF1YoLVmig)  [免费域名教程3](https://www.youtube.com/watch?v=XS0EgqckUKo&t=320s)
   - 在 Pages控制台的 `自定义域`选项卡，下方点击 `设置自定义域`。
   - 填入你的自定义次级域名，注意不要使用你的根域名，例如：
     您分配到的域名是 `amclubss.com`，则添加自定义域填入 `vless.amclubss.com`即可，点击 `激活域`即可。    
3. 给UUID设置KV存储桶(可选项，推荐设置)： 
   - 在 CloudFlare主页的左边菜单的 ` 存储和数据库` 选项卡 -> 展开选择点击 `KV` -> 右方点击 -> `创建` -> 填入 `命名空间名称`(此名称自己命名) 后 -> 点击 `添加`。(此步已有可忽略)
   - 在 workers控制台的 `设置` 选项卡 -> 点击 `绑定` -> 右方点击 -> `添加` -> 选择 `KV 命名空间` -> 变量名称 填入 `amclubs`(此名称固定不能变) -> KV 命名空间 选择 在上面创建的 `命名空间名称`后 -> 右下方点击 `部署`。
   - 在 `设置` 选项卡，在右上角点击 `创建部署` 后，重新上传 [_worker.js.zip](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/_worker.js.zip) 文件后点击 `保存并部署` 即可。
4. 访问订阅内容：
   - 访问 `https://[YOUR-WORKERS-URL]/[UUID]` 即可获取订阅内容（默认UUID是：d0298536-d670-4045-bbb1-ddd5ea68683e）。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?sub` 就是你的通用自适应订阅地址(Quantumult X、Clash、singbox、小火箭、v2rayN、v2rayU、surge、PassWall、SSR+、Karing等)。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?base64` Base64订阅格式，适用PassWall,SSR+等。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?clash` Clash订阅格式，适用OpenClash等。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?singbox` singbox订阅格式，适用singbox等。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?sub&IP_URL=https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/ipUrl.txt` 自动义变量等参数。
5. 修改默认UUID变量，使用KV存储桶(可选项，推荐修改，防止别人用你节点)： 
   - 访问 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e/ui` 即可进入修改UUID页面 
   - 在UUID页面UUID项 -> 填入 `新的UUID` 后,[在线获取UUID](https://1024tools.com/uuid) -> 点击 `Save`。
   - 保存成功后，原UUID已作废不能访问，用新UUID访问  `https://vless.amclubss.com/新的UUID` 即可获取订阅内容。

</details>

## 🧰四、Pages GitHub 部署方法 [视频教程](https://www.youtube.com/watch?v=dPH63nITA0M&t=654s)
<details>
<summary>点击展开/收起</summary>
   
1. 部署 Cloudflare Pages：
   - 在 Github 上先 Fork 本项目，并点上 Star !!!
   - 在 Cloudflare Pages 控制台中选择 `连接到 Git`后，选中 `am-cf-tunnel`项目后点击 `开始设置`。
   - 在 `设置构建和部署`页面下方，后点击 `保存并部署`即可。
2. 给 Pages绑定 CNAME自定义域：[无域名绑定Cloudflare部署视频教程]->[免费域名教程1](https://youtu.be/wHJ6TJiCF0s) [免费域名教程2](https://youtu.be/yEF1YoLVmig)  [免费域名教程3](https://www.youtube.com/watch?v=XS0EgqckUKo&t=320s)
   - 在 Pages控制台的 `自定义域`选项卡，下方点击 `设置自定义域`。
   - 填入你的自定义次级域名，注意不要使用你的根域名，例如：
     您分配到的域名是 `amclubss.com`，则添加自定义域填入 `vless.amclubss.com`即可，点击 `激活域`即可。    
3. 给UUID设置KV存储桶(可选项，推荐设置)： 
   - 在 CloudFlare主页的左边菜单的 ` 存储和数据库` 选项卡 -> 展开选择点击 `KV` -> 右方点击 -> `创建` -> 填入 `命名空间名称`(此名称自己命名) 后 -> 点击 `添加`。(此步已有可忽略)
   - 在 workers控制台的 `设置` 选项卡 -> 点击 `绑定` -> 右方点击 -> `添加` -> 选择 `KV 命名空间` -> 变量名称 填入 `amclubs`(此名称固定不能变) -> KV 命名空间 选择 在上面创建的 `命名空间名称`后 -> 右下方点击 `部署`。
   - 在 `设置` 选项卡，在右上角点击 `创建部署` 后，重新选择 `部署` 即可。
4. 访问订阅内容：
   - 访问 `https://[YOUR-WORKERS-URL]/[UUID]` 即可获取订阅内容（默认UUID是：d0298536-d670-4045-bbb1-ddd5ea68683e）。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?sub` 就是你的通用自适应订阅地址(Quantumult X、Clash、singbox、小火箭、v2rayN、v2rayU、surge、PassWall、SSR+、Karing等)。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?base64` Base64订阅格式，适用PassWall,SSR+等。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?clash` Clash订阅格式，适用OpenClash等。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?singbox` singbox订阅格式，适用singbox等。
   - 例如 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e?sub&IP_URL=https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/ipUrl.txt` 自动义变量等参数。
5. 修改默认UUID变量，使用KV存储桶(可选项，推荐修改，防止别人用你节点)： 
   - 访问 `https://vless.amclubss.com/d0298536-d670-4045-bbb1-ddd5ea68683e/ui` 即可进入修改UUID页面 
   - 在UUID页面UUID项 -> 填入 `新的UUID` 后,[在线获取UUID](https://1024tools.com/uuid) -> 点击 `Save`。
   - 保存成功后，原UUID已作废不能访问，用新UUID访问  `https://vless.amclubss.com/新的UUID` 即可获取订阅内容。

</details>

## 🔧五、变量说明 [视频教程](https://www.youtube.com/watch?v=ag12Rpc9KP4&t=739s)
| 变量名 | 示例 | 必填 | 备注 | YT |
|-----|-----|-----|-----|-----|
| UUID            | d0298536-d670-4045-bbb1-ddd5ea68683e（默认） |✅| 支持Cloudflare的KV存储桶设置 [在线获取UUID](https://1024tools.com/uuid) 如果是Trojan节点的变量是：PASSWORD     | |
| PROT_TYPE        | 默认空          |❌|      默认空,就是生成vless和trojan节点，vless(只生成vless节点)，trojan(只生成trojan节点)           | [视频教程](https://www.youtube.com/watch?v=emEBm8Gw2wI&t=922s) |
| IP_URL           | [https://raw.github.../ipUrl.txt](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/ipUrl.txt)           |❌| （推荐）优选(ipv4、ipv6、域名、API)地址(支持多个之间`,`或 换行 作间隔)，支持文件连接后里带PROXYIP参数，可以实现不同区域优先IP使用不同的PROXYIP固定区域，解决IP乱跳问题  | [视频教程](https://www.youtube.com/watch?v=4fcyJjstFdg&t=349s)|
| IP_URL_TXT       | [https://raw.github.../ipv4.txt](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/ipv4.txt) |❌| （不推荐）优选ipv4、ipv6、域名、API地址(支持多个之间`,`或 换行 作间隔) |[视频教程](https://youtu.be/dzxezRV1v-o) [视频教程](https://youtu.be/vX3U3FuuTT8)|
| IP_URL_CSV       | [https://raw.github.../ipv4.csv](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/ipv4.csv) |❌| （不推荐）优选ipv4/6的IP测速结果(支持多元素, 元素之间使用`,`作间隔) |[视频教程](https://youtu.be/vX3U3FuuTT8)|
| IP_LOCAL         | `icook.hk:2053#官方优选域名`           |❌| （不推荐）本地优选域名/优选IP(支持多元素之间`,`或 换行 作间隔)                                 | |
| NAT64           | true/false                           |❌| 默认false,是否开启nat做PROXYIP(反代IP)，开启后优选使用NAT64再用PROXYIP       | [视频教程](https://www.youtube.com/watch?v=nx80sGpVoBM&t=533s) |
| NAT64_DOM_URL_TXT  | [https://raw.github.../nat64Domain.txt](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/nat64Domain.txt)     |❌| 指定相关域名网站走NAT64      | [视频教程](https://www.youtube.com/watch?v=nx80sGpVoBM&t=533s)|
| NAT64_PREFIX  | 2a01:4f8:c2c:123f:64::  </br>或</br> [https://raw.github.../nat64Prefix.txt](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/nat64Prefix.txt)    |❌| 指定自定NAT64前缀,不填走CF默认的 (https://amclubss.com/public/)     | [视频教程](https://www.youtube.com/watch?v=nx80sGpVoBM&t=533s)|
| PROXYIP_DOM_URL_TXT | [https://raw.github.../proxyDomain.txt](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/proxyDomain.txt) |❌| 指定相关域名网站走反代IP(PROXYIP),如果NAT64也设置相同域名,优选级是走PROXYIP      | [视频教程](https://www.youtube.com/watch?v=nx80sGpVoBM&t=533s)|
| PROXYIP          | proxyip.amclubs.kozow.com </br>或</br> [https://raw.github.../proxyip.txt](https://raw.githubusercontent.com/amclubs/am-cf-tunnel/main/proxyip.txt)  |❌| 访问CloudFlare的CDN代理节点(支持多PROXYIP, PROXYIP之间使用`,`或 换行 作间隔),支持端口设置默认443 如: proxyip.amclubs.kozow.com:2053 ，支持远程txt或csv文件| [视频教程](https://youtu.be/pKrlfRRB0gU) |
| SOCKS5           | user:password@127.0.0.1:1080         |❌| 优先作为访问CFCDN站点的SOCKS5代理                                                   | [视频教程](https://youtu.be/Bw82BH_ecC4) |
| DNS_RESOLVER_URL | https://cloudflare-dns.com/dns-query |❌| DNS解析获取作用，小白勿用                                                           |  |
| NO_TLS           | true/false                           |❌| 默认false,是否开启TLS系列端口，只有workers部署才可以使非用TLS系列端口             | |
| SL               | 5                                    |❌| `CSV`文件里的测速结果满足速度下限                                                     ||
| SUB_CONFIG       | [https://raw.github.../ACL4SSR_Online_Mini.ini](https://raw.githubusercontent.com/amclubs/ACL4SSR/main/Clash/config/ACL4SSR_Online_Full_MultiMode.ini) |❌| clash、singbox等 订阅转换配置文件  ||
| SUB_CONVERTER    | url.v1.mk                    |❌| clash、singbox等 订阅转换后端的api地址                               ||
| SUB_NAME         | AM科技                             |❌ | 订阅名称                                                     ||
| CF_EMAIL         | test@gmail.com                       |❌| CF账户邮箱(要和`CF_KEY`同时填才生效, 订阅信息将显示请求使用量, 小白别用)                        ||
| CF_KEY          | c6a944b5c9c18c235288bced8b85e         |❌| CF账户Global API Key(要和`CF_EMAIL`同时填才生效, 订阅信息将显示请求使用量, 小白别用)           ||
| TG_TOKEN        | 6823456:XXXXXXX0qExVUhHDAbXXXqWXgBA   |❌| 发送TG通知的机器人token                       ||
| TG_ID           | 6946912345                            |❌ | 接收TG通知的账户数字ID                                       ||
| HOST_REAMRK           | true/false                            |❌ | 默认false,是否用订阅域名做节点别名                                      ||

## 🛠六、已适配订阅工具 [点击进入视频教程](https://youtu.be/xGOL57cmvaw) [点进进入karing视频教程](https://youtu.be/M3vLLBWfuFg)
<details>
<summary>点击展开/收起</summary>

- Mac（苹果电脑）
   - [v2rayU](https://github.com/yanue/V2rayU/releases) | [clash-verge-rev](https://github.com/clash-verge-rev/clash-verge-rev/releases) | [Quantumult X](https://apps.apple.com/us/app/quantumult-x/id1443988620) |  [小火箭](https://apps.apple.com/us/app/shadowrocket/id932747118) | [surge](https://apps.apple.com/us/app/surge-5/id1442620678) | [karing](https://karing.app/download) | [sing-box](https://github.com/SagerNet/sing-box/releases)  | [Clash Nyanpasu](https://github.com/keiko233/clash-nyanpasu/releases) | [openclash](https://github.com/vernesong/OpenClash/releases) | [Hiddify](https://github.com/hiddify/hiddify-next/releases)

- Win（win系统电脑）
   - [v2rayN](https://github.com/2dust/v2rayN/releases) |  [clash-verge-rev](https://github.com/clash-verge-rev/clash-verge-rev/releases) | [sing-box](https://github.com/SagerNet/sing-box/releases) |  [Clash Nyanpasu](https://github.com/keiko233/clash-nyanpasu/releases) | [openclash](https://github.com/vernesong/OpenClash/releases)  | [karing](https://karing.app/download) |  [Hiddify](https://github.com/hiddify/hiddify-next/releases)
     
- IOS（苹果手机）
   - [clash-verge-rev](https://github.com/clash-verge-rev/clash-verge-rev/releases) |  [Quantumult X](https://apps.apple.com/us/app/quantumult-x/id1443988620)  |  [小火箭](https://apps.apple.com/us/app/shadowrocket/id932747118)  |  [surge](https://apps.apple.com/us/app/surge-5/id1442620678) |  [sing-box](https://github.com/SagerNet/sing-box/releases) | [Clash Nyanpasu](https://github.com/keiko233/clash-nyanpasu/releases) | [karing](https://karing.app/download) | [Hiddify](https://github.com/hiddify/hiddify-next/releases)
     
- Android（安卓手机）
   - [v2rayNG](https://github.com/2dust/v2rayNG/releases) |  [clash-verge-rev](https://github.com/clash-verge-rev/clash-verge-rev/releases) | [sing-box](https://github.com/SagerNet/sing-box/releases) |  [Clash Nyanpasu](https://github.com/keiko233/clash-nyanpasu/releases) |  [karing](https://karing.app/download) | [Hiddify](https://github.com/hiddify/hiddify-next/releases)

- 软路由
   - [openclash(clash.meta)](https://github.com/vernesong/OpenClash/releases) 
  
</details>
	  
# 🙏感谢
[3Kmfi6HP](https://github.com/3Kmfi6HP/EDtunnel)、[ACL4SSR](https://github.com/ACL4SSR/ACL4SSR/tree/master/Clash/config)

# 🌟推荐
**【流量光】** 中转+专线高速机场 **9.9元300G 14.9元500G 1倍率**✅畅爽晚高峰 解锁ChatGPT、全流媒体(送小火箭)
</br>🌐官网：[https://llgjc1.com](https://llgjc1.com/#/register?code=bIUDEPTu)

# 
<center>
<details><summary><strong> ☕ [点击展开] 赞赏支持 ~🧧</strong></summary>
*我非常感谢您的赞赏和支持，它们将极大地激励我继续创新，持续产生有价值的工作。*

- **USDT-TRC20:** `TWTxUyay6QJN3K4fs4kvJTT8Zfa2mWTwDD`
- **TRX-TRC20:** `TWTxUyay6QJN3K4fs4kvJTT8Zfa2mWTwDD`

<div align="center"> 
  <img src="https://github.com/user-attachments/assets/e6cdc42a-6374-4722-b833-601738f72196" width="200"></br> 
  TRC10/TRC20扫码支付 
</div> 
</details>
</center>

 #
 ⚠️免责声明:
 - 1、该项目设计和开发仅供学习、研究和安全测试目的。请于下载后 24 小时内删除, 不得用作任何商业用途, 文字、数据及图片均有所属版权, 如转载须注明来源。
 - 2、使用本程序必循遵守部署服务器所在地区的法律、所在国家和用户所在国家的法律法规。对任何人或团体使用该项目时产生的任何后果由使用者承担。
 - 3、作者不对使用该项目可能引起的任何直接或间接损害负责。作者保留随时更新免责声明的权利，且不另行通知。
 

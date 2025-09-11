<?php
/**
 * Plugin Name:       Cloudflare 自动刷新缓存 (增强版)显示
 * Description:       当文章发布、更新、删除或分类变更时，自动刷新Cloudflare的相关缓存。
 * Version:           0.0.1
 * Author:            Joey
 * Author URI:        https://joeyblog.net
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       cf-auto-purge
 */

// 如果直接访问此文件,则中止执行
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ===================================================================================
// 模块一：后台设置页面 
// ===================================================================================

/**
 * 添加后台菜单
 */
function cf_purge_add_admin_menu() {
    add_options_page(
        'Cloudflare 自动刷新缓存',  // 页面标题
        'CF 自动刷新',              // 菜单标题
        'manage_options',
        'cf_auto_purge',
        'cf_purge_options_page_html'
    );
}
add_action( 'admin_menu', 'cf_purge_add_admin_menu' );

/**
 * 初始化设置
 */
function cf_purge_settings_init() {
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'cf_auto_purge' && isset( $_GET['refresh_zones'] ) && $_GET['refresh_zones'] === 'true' ) {
        check_admin_referer( 'cf-purge-refresh-zones' );
        delete_transient( 'cf_purge_zone_list' );
        wp_safe_redirect( menu_page_url( 'cf_auto_purge', false ) );
        exit;
    }

    register_setting( 'cf_purge_options_group', 'cf_purge_settings' );
    add_settings_section('cf_purge_api_section', 'Cloudflare API 凭证', null, 'cf_auto_purge');
    add_settings_field('cf_purge_email', 'Cloudflare 账户邮箱', 'cf_purge_email_render', 'cf_auto_purge', 'cf_purge_api_section');
    add_settings_field('cf_purge_global_api_key', '全局 API 密钥', 'cf_purge_global_api_key_render', 'cf_auto_purge', 'cf_purge_api_section');
    add_settings_field('cf_purge_zone_id', '区域', 'cf_purge_zone_id_render', 'cf_auto_purge', 'cf_purge_api_section');
}
add_action( 'admin_init', 'cf_purge_settings_init' );

/**
 * 获取 Cloudflare 区域列表
 */
function cf_purge_fetch_zones() {
    $cached_zones = get_transient( 'cf_purge_zone_list' );
    if ( false !== $cached_zones ) {
        return $cached_zones;
    }

    $settings = get_option('cf_purge_settings');
    $email    = $settings['cf_purge_email'] ?? '';
    $api_key  = $settings['cf_purge_global_api_key'] ?? '';

    if ( empty($email) || empty($api_key) ) {
        return new WP_Error( 'missing_creds', 'API 凭证未设置。' );
    }

    $response = wp_remote_get( 'https://api.cloudflare.com/client/v4/zones', [
        'headers' => [
            'X-Auth-Email' => $email, 
            'X-Auth-Key' => $api_key, 
            'Content-Type' => 'application/json'
        ],
        'timeout' => 15,
    ]);

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( !isset($body['success']) || !$body['success'] ) {
        $error_message = $body['errors'][0]['message'] ?? '未知的 API 错误。';
        return new WP_Error( 'api_error', $error_message );
    }

    set_transient( 'cf_purge_zone_list', $body['result'], HOUR_IN_SECONDS );
    return $body['result'];
}

/**
 * 渲染邮箱输入框
 */
function cf_purge_email_render() {
    $options = get_option( 'cf_purge_settings' );
    echo "<input type='email' name='cf_purge_settings[cf_purge_email]' value='" . esc_attr( $options['cf_purge_email'] ?? '' ) . "' class='regular-text'>";
}

/**
 * 渲染 API 密钥输入框
 */
function cf_purge_global_api_key_render() {
    $options = get_option( 'cf_purge_settings' );
    echo "<input type='password' name='cf_purge_settings[cf_purge_global_api_key]' value='" . esc_attr( $options['cf_purge_global_api_key'] ?? '' ) . "' class='regular-text'>";
}

/**
 * 渲染区域选择框
 */
function cf_purge_zone_id_render() {
    $options = get_option( 'cf_purge_settings' );
    $selected_zone = $options['cf_purge_zone_id'] ?? '';
    $zones_data = cf_purge_fetch_zones();
    
    if ( is_wp_error( $zones_data ) ) {
        if ( $zones_data->get_error_code() === 'missing_creds' ) {
            echo '<p class="description">请先输入并保存您的邮箱和全局API密钥。之后这里会自动显示可用的区域列表。</p>';
        } else {
            echo '<p class="description" style="color: #d63638;"><strong>错误：</strong>获取区域列表失败。 ' . esc_html( $zones_data->get_error_message() ) . '</p>';
        }
        return;
    }
    
    if ( empty( $zones_data ) ) {
        echo '<p class="description">此账户下未找到任何区域。</p>';
        return;
    }
    
    echo "<select name='cf_purge_settings[cf_purge_zone_id]'>";
    echo "<option value=''>-- 请选择一个区域 --</option>";
    foreach ( $zones_data as $zone ) {
        printf(
            '<option value="%s" %s>%s</option>', 
            esc_attr( $zone['id'] ), 
            selected( $selected_zone, $zone['id'], false ), 
            esc_html( $zone['name'] )
        );
    }
    echo "</select>";
    
    $refresh_url = wp_nonce_url( menu_page_url( 'cf_auto_purge', false ) . '&refresh_zones=true', 'cf-purge-refresh-zones' );
    echo " <a href='" . esc_url( $refresh_url ) . "'>刷新列表</a>";
}

/**
 * 渲染设置页面
 */
function cf_purge_options_page_html() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action='options.php' method='post'>
            <?php 
                settings_fields('cf_purge_options_group'); 
                do_settings_sections('cf_auto_purge'); 
                submit_button('保存设置'); 
            ?>
        </form>
    </div>
    <?php
}

// ===================================================================================
// 模块二：核心功能与API调用 (已重构)
// ===================================================================================

/**
 * 核心执行函数：负责收集URL并调用Cloudflare API。
 *
 * @param array $urls_to_purge 需要刷新的URL数组。
 */
function cf_purge_execute_purge( $urls_to_purge ) {
    if ( empty( $urls_to_purge ) ) {
        return;
    }

    $settings = get_option( 'cf_purge_settings' );
    $email    = $settings['cf_purge_email'] ?? '';
    $api_key  = $settings['cf_purge_global_api_key'] ?? '';
    $zone_id  = $settings['cf_purge_zone_id'] ?? '';

    // 如果凭证不完整，则中止
    if ( empty( $email ) || empty( $api_key ) || empty( $zone_id ) ) {
        return;
    }

    // 移除重复的URL并重新索引数组
    $urls_to_purge = array_values( array_unique( $urls_to_purge ) );

    $api_url = "https://api.cloudflare.com/client/v4/zones/{$zone_id}/purge_cache";
    $headers = [
        'X-Auth-Email' => $email, 
        'X-Auth-Key' => $api_key, 
        'Content-Type' => 'application/json'
    ];
    
    $response = wp_remote_post($api_url, [
        'method'  => 'POST',
        'headers' => $headers,
        'body'    => json_encode(['files' => $urls_to_purge]),
        'timeout' => 15
    ]);

    // 设置后台通知
    if ( is_wp_error( $response ) ) {
        $message = "CF API 错误: " . $response->get_error_message();
    } else {
        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        if (isset($body['success']) && $body['success']) {
            $message = sprintf('成功：已向Cloudflare发送刷新请求，共涉及 %d 个URL。', count($urls_to_purge));
        } else {
            $error_message = $body['errors'][0]['message'] ?? '未知错误。';
            $message = "CF API 错误: " . $error_message;
        }
    }
    set_transient( 'cf_purge_notice_'. get_current_user_id(), $message, 45 );
}

/**
 * 当文章状态改变时触发的函数。
 * 例如：发布、存为草稿、移入回收站。
 *
 * @param string  $new_status 新状态。
 * @param string  $old_status 旧状态。
 * @param WP_Post $post       文章对象。
 */
function cf_purge_on_status_change( $new_status, $old_status, $post ) {
    // 仅当文章从非发布状态变为发布，或从发布状态变为非发布状态时触发
    if ( $new_status === 'publish' || $old_status === 'publish' ) {
        if ( ! in_array( $post->post_type, ['post'] ) ) return; // 只处理 'post' 类型

        $urls = [ home_url( '/' ) ];
        $urls[] = get_permalink( $post->ID );

        // 获取分类和标签的URL
        $categories = get_the_category( $post->ID );
        if ( ! empty( $categories ) ) {
            foreach ( $categories as $category ) {
                $urls[] = get_category_link( $category->term_id );
            }
        }
        
        $tags = get_the_tags( $post->ID );
        if ( ! empty( $tags ) ) {
            foreach ( $tags as $tag ) {
                $urls[] = get_tag_link( $tag->term_id );
            }
        }
        
        cf_purge_execute_purge( $urls );
    }
}
add_action( 'transition_post_status', 'cf_purge_on_status_change', 10, 3 );

/**
 * 当文章被永久删除时触发。
 *
 * @param int $post_id 文章ID。
 */
function cf_purge_on_delete_post( $post_id ) {
    // 文章已删除，我们只能获取它的分类和标签信息来刷新归档页
    $post = get_post($post_id);
    if ( ! in_array( $post->post_type, ['post'] ) ) return;

    $urls = [ home_url( '/' ) ]; // 刷新首页

    $categories = get_the_category( $post_id );
    if ( ! empty( $categories ) ) {
        foreach ( $categories as $category ) {
            $urls[] = get_category_link( $category->term_id );
        }
    }
    
    $tags = get_the_tags( $post_id );
    if ( ! empty( $tags ) ) {
        foreach ( $tags as $tag ) {
            $urls[] = get_tag_link( $tag->term_id );
        }
    }

    cf_purge_execute_purge( $urls );
}
add_action( 'delete_post', 'cf_purge_on_delete_post', 10, 1 );

/**
 * 当分类或标签本身被编辑时触发（例如改名或改别名）。
 *
 * @param int $term_id 术语ID。
 */
function cf_purge_on_term_change( $term_id ) {
    $urls = [ get_term_link( $term_id ), home_url( '/' ) ];
    cf_purge_execute_purge( $urls );
}
add_action( 'edited_term', 'cf_purge_on_term_change', 10, 1 );
add_action( 'delete_term', 'cf_purge_on_term_change', 10, 1 );

/**
 * 显示后台通知。
 */
function cf_purge_display_admin_notice() {
    $notice = get_transient( 'cf_purge_notice_'. get_current_user_id() );
    if ( $notice ) {
        $is_error = stripos( $notice, '错误' ) !== false;
        $class = $is_error ? 'notice-error' : 'notice-success';
        printf( 
            '<div class="notice %s is-dismissible"><p><strong>CF 自动刷新:</strong> %s</p></div>', 
            esc_attr( $class ), 
            esc_html( $notice )
        );
        delete_transient( 'cf_purge_notice_'. get_current_user_id() );
    }
}
add_action( 'admin_notices', 'cf_purge_display_admin_notice' );

<?php
/**
 * テーマ管理メニューと設定ページ
 * 
 * @package Oyiso
 * @since 1.0.0
 */

// 直接アクセスを防止
if (!defined('ABSPATH')) {
    exit;
}

/**
 * テーマオプションメニューを追加
 */
function add_theme_options_menu() {
    add_menu_page(
        'Oyisoテーマ設定',      // ページタイトル
        'Oyisoテーマ設定',      // メニュータイトル
        'manage_options',     // 権限
        'oyiso-theme-options', // メニューslug
        'oyiso_option_admin', // コールバック関数
        'dashicons-admin-generic', // アイコン
        60                    // 位置
    );
}
add_action('admin_menu', 'add_theme_options_menu');

/**
 * テーマ設定ページのコールバック
 */
function oyiso_option_admin() {
    if (!current_user_can('manage_options')) {
        wp_die('このページにアクセスする権限がありません');
    }
    
    // テーマ設定ページを読み込み
    require_once get_template_directory() . '/admin/option.php';
}

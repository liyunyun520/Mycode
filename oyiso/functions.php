<?php
/**
 * Oyiso Theme Functions
 * 
 * @package Oyiso
 * @version 1.5.2
 * @requires PHP 8.0+
 * @requires WordPress 5.0+
 * 
 * @author Oyiso
 * @email me@onll.cn
 * @created 2024-01-12
 * @updated 2024-12-04
 */

// 直接アクセスを防止
if (!defined('ABSPATH')) {
    exit;
}

// PHPバージョンチェック
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    add_action('admin_notices', function() {
        echo '<div class="error"><p><strong>Oyisoテーマエラー：</strong>PHP 8.0 以上が必要です。現在のバージョン：' . PHP_VERSION . '</p></div>';
    });
    return;
}

// カスタム静的リソースパス
function file_uri() {
  return get_template_directory_uri();
}

// テーマ機能モジュールを読み込み
require_once get_template_directory().'/modules/add-email.php'; //メールSMTP
require_once get_template_directory().'/modules/add-category-image.php'; //カテゴリーに画像リンクフィールドを追加
require_once get_template_directory().'/modules/add-menu.php'; //ナビゲーションメニューを追加
require_once get_template_directory().'/modules/add-bookmark.php'; //リンクを追加
require_once get_template_directory().'/modules/add-moment.php'; //瞬間を追加
require_once get_template_directory().'/modules/add-gallery.php'; //ギャラリーを追加
require_once get_template_directory().'/modules/add-comments.php'; //コメントエリア
require_once get_template_directory().'/modules/add-shortcode.php'; //ショートコードを追加
require_once get_template_directory().'/modules/get-post-image-color.php'; //記事のアイキャッチ画像のメインカラーとサブカラーを取得
require_once get_template_directory().'/modules/get-category-image-color.php'; //カテゴリーのアイキャッチ画像のメインカラーとサブカラーを取得
require_once get_template_directory().'/modules/get-post-views.php'; //記事閲覧数を設定
require_once get_template_directory().'/modules/get-comments.php'; //コメントエリア
require_once get_template_directory().'/modules/get-user-location.php'; //ユーザー位置情報を取得
require_once get_template_directory().'/modules/get-user-avatar.php'; //ユーザーアバター
require_once get_template_directory().'/modules/get-home-newest.php'; // ホームで最新コンテンツを取得
require_once get_template_directory().'/modules/upyun-uss.php'; //又拍雲ストレージ
require_once get_template_directory().'/modules/other.php'; //その他の設定
require_once get_template_directory().'/admin/auth.php'; //テーマ設定メニュー
require_once get_template_directory().'/admin/oyiso_options.php'; //テーマ設定AJAX処理

// ajaxリクエストモジュールを読み込み
require_once get_template_directory().'/modules/ajax/get-theme-version.php'; //テーマバージョンを取得

// プラグインを読み込み
require_once 'plugins/external-media-without-import/external-media-without-import.php'; //メディア外部リンクを追加

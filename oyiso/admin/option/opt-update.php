<?php
/**
 * テーマ更新ログページ
 * 
 * @package Oyiso
 * @since 1.0.0
 */

// 直接アクセスを防止
if (!defined('ABSPATH')) {
    exit;
}

/**
 * テーマバージョン情報を取得
 */
function oyiso_get_theme_version() {
    $theme = wp_get_theme();
    return $theme->get('Version');
}

/**
 * テーマバージョンと更新ログを表示
 */
function oyiso_theme_update_page() {
    $version = oyiso_get_theme_version();
    
    // 更新ログ（外部APIから取得またはローカル保存）
    $update_log = get_option('oyiso_update_log', array());
    
    ?>
    <div class="wrap">
        <h1>テーマ更新ログ</h1>
        
        <div class="oyiso-version-info">
            <p>現在のバージョン：<strong><?php echo esc_html($version); ?></strong></p>
        </div>
        
        <div class="oyiso-update-log">
            <h2>更新履歴</h2>
            
            <?php if (!empty($update_log)) : ?>
                <div class="update-log-list">
                    <?php foreach ($update_log as $log) : ?>
                        <div class="log-item">
                            <h3>バージョン <?php echo esc_html($log['version']); ?></h3>
                            <p class="log-date">公開日：<?php echo esc_html($log['date']); ?></p>
                            <div class="log-content">
                                <?php echo wp_kses_post($log['content']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="no-updates">
                    <p>更新ログはありません。</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
        .oyiso-version-info {
            background: #fff;
            padding: 15px 20px;
            border-left: 4px solid #46b450;
            margin-bottom: 20px;
        }
        .update-log-list .log-item {
            background: #fff;
            padding: 15px 20px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .log-item h3 {
            margin-top: 0;
            color: #23282d;
        }
        .log-date {
            color: #666;
            font-size: 13px;
        }
        .log-content ul {
            margin-left: 20px;
        }
    </style>
    <?php
}

/**
 * 更新ログ記録関数
 * 
 * @param array $log_data 更新ログデータ
 */
function oyiso_save_update_log($log_data) {
    $existing_logs = get_option('oyiso_update_log', array());
    array_unshift($existing_logs, $log_data);
    update_option('oyiso_update_log', $existing_logs);
}

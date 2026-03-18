<?php
if (!defined('ABSPATH')) {
  exit;
}

// 大きな画像の自動縮小を無効化
add_filter('big_image_size_threshold', '__return_false');

// 画像サイズを無効化
add_filter('intermediate_image_sizes_advanced', 'only_image_sizes', 10);
function only_image_sizes($sizes) {
  if (get_option('oyiso_ban_thumbnail')) {
    $allowed_sizes = ['full'];
  } else {
    $allowed_sizes = ['thumbnail', 'medium', 'large', 'full'];
  }
  
  foreach ($sizes as $size => $details) {
    if (!in_array($size, $allowed_sizes)) {
      unset($sizes[$size]);
    }
  }
  return $sizes;
}


// 又拍雲設定が入力されていない場合は実行しない
if (
  !get_option('oyiso_upyun_uss') ||
  !get_option('oyiso_upyun_bucketname') ||
  !get_option('oyiso_upyun_host') ||
  !get_option('oyiso_upyun_username') ||
  !get_option('oyiso_upyun_passwd')) 
{
  return;
}

require_once get_template_directory().'/vendor/autoload.php';
use Upyun\Upyun;
use Upyun\Config;


// ドメインパスを抽出
function replacePath() {
  $upyun_domain = get_option('oyiso_upyun_host');
  $upyun_domain_path = parse_url($upyun_domain, PHP_URL_PATH);
  $upyun_domain_path_lastchar = substr($upyun_domain_path, -1);
  if ($upyun_domain_path_lastchar === '/') {
    $domain = $upyun_domain;
    $domain_path = $upyun_domain_path;
  } else {
    $domain = $upyun_domain.'/';
    $domain_path = $upyun_domain_path.'/';
  }
  return array('domain' => $domain, 'path' => $domain_path);
}


// すべてのファイルURLを置換
add_filter('upload_dir', 'oyiso_upload_url', 20);
function oyiso_upload_url($upload) {
  $upload['baseurl'] = rtrim(replacePath()['domain'], '/');
  return $upload;
}


// ファイルをアップロード
add_filter('wp_generate_attachment_metadata', 'oyiso_upload_attachment', 10, 2);
function oyiso_upload_attachment($metadata, $attachment_id) {
  operationFile($attachment_id, $metadata, 'upload');
  return $metadata;
}


// ファイルを削除
add_action('delete_attachment', 'oyiso_delete_attachment');
function oyiso_delete_attachment($attachment_id) {
  $metadata = wp_get_attachment_metadata($attachment_id);
  operationFile($attachment_id, $metadata, 'delete');
}


// ファイル操作
function operationFile(int $attachment_id, array $metadata, string $action): void {
  $attachment_id = intval($attachment_id);
  $upload_dir = wp_upload_dir();
  
  $bucket_name = get_option('oyiso_upyun_bucketname');
  $username = get_option('oyiso_upyun_username');
  $password = get_option('oyiso_upyun_passwd');
  
  if (!$bucket_name || !$username || !$password) {
    return;
  }
  
  try {
    $config = new Config($bucket_name, $username, $password);
    $upyun = new Upyun($config);
  } catch (Exception $e) {
    return;
  }
  
  $file_path = get_attached_file($attachment_id);
  
  // ファイルパスを検証
  if (!$file_path || !file_exists($file_path)) {
    return;
  }
  
  $filetype = wp_check_filetype($file_path);
  $replace_path = replacePath()['path'];

  // 画像の場合
  if (isset($filetype['type']) && str_contains($filetype['type'], 'image')) {
    // 画像のメインとすべてのトリミングバージョンを取得
    $files = [];
    if (isset($metadata['file'])) {
      $files['full'] = $metadata['file'];
    }
    $sizes = ['large', 'medium', 'thumbnail'];
    foreach ($sizes as $size) {
      if (isset($metadata['sizes'][$size]['file'])) {
        $files[$size] = $metadata['sizes'][$size]['file'];
      }
    }
    foreach($files as $i => $f) {
      if ($i == 'full') {
        $path = '/'.$f;
        $file = $upload_dir['basedir'].'/'.$f;
      } else {
        $path = ($upload_dir['subdir'] ?? '').'/'.$f;
        $file = ($upload_dir['path'] ?? '').'/'.$f;
      }
      $upload_path = rtrim($replace_path, '/').$path;

      // アップロード
      if ($action == 'upload') {
        if (file_exists($file)) {
          $upload_file = fopen($file, 'r');
          if ($upload_file) {
            $upyun->write($upload_path, $upload_file, true);
            fclose($upload_file);
          }
        }

      // 删除
      } else if ($action == 'delete') {
        try {
          if ($upyun->has($upload_path)) {
            $upyun->delete($upload_path, true);
          }
        } catch (Exception $e) {
          // 静默处理删除错误
        }
      }
    }

  // 他のファイルの場合
  } else {
    $file_name = basename($file_path);
    $upload_path = rtrim($replace_path, '/').($upload_dir['subdir'] ?? '').'/'.$file_name;

    // アップロード
    if ($action == 'upload') {
      if (file_exists($file_path)) {
        $upload_file = fopen($file_path, 'r');
        if ($upload_file) {
          $upyun->write($upload_path, $upload_file, true);
          fclose($upload_file);
        }
      }

    // 削除
    } else if ($action == 'delete') {
      try {
        if ($upyun->has($upload_path)) {
          $upyun->delete($upload_path, true);
        }
      } catch (Exception $e) {
        // 削除エラーをサイレント処理
      }
    }
  }
}


// 又拍雲ストレージ使用量
function upyun_usage() {
  // ユーザー権限を確認
  if (!current_user_can('administrator')) {
    wp_send_json_error('failed');
  } else {
    $upyun_use = '不明';
    if (get_option('oyiso_upyun_uss')) {
      $config = new Config(get_option('oyiso_upyun_bucketname'), get_option('oyiso_upyun_username'), get_option('oyiso_upyun_passwd'));
      $upyun = new Upyun($config);
      function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow]; 
      } 
      try {
        $upyun_use_bytes = $upyun -> usage();
        $upyun_use = formatBytes($upyun_use_bytes);
      } catch (Exception $e) {
        $upyun_use = '不明';
      }
    }
    wp_send_json_success($upyun_use);
  }
}
add_action('wp_ajax_upyun_usage', 'upyun_usage');
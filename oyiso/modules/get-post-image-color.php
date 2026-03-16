<?php

require_once ABSPATH.'wp-admin/includes/image.php';

require_once get_template_directory().'/vendor/autoload.php';
use ColorThief\ColorThief;

// 添加特色图片支持
if (function_exists('add_theme_support')) {
  add_theme_support('post-thumbnails', array('post', 'page', 'gallery'));
}

//如果关闭则不执行
if (!get_option('oyiso_cover_color')) {
  return;
}

// 增删改特色图片时执行的操作
add_action('added_post_meta', 'added_post_image', 10, 4);
add_action('updated_post_meta', 'updated_post_image', 10, 4);
add_action('deleted_post_meta', 'deleted_post_image', 10, 4);
add_action('save_post', 'save_post_action', 10, 3);

function added_post_image($meta_id, $post_id, $meta_key, $meta_value) {
  // 检查是否是自动保存或者是其他非用户提交的情况，如果是则直接返回
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // 检查文章类型是否是 'post' 或 'page'，如果不是，则返回
  $post_type = get_post_type($post_id);
  if (($post_type !== 'post') && ($post_type !== 'page') && ($post_type !== 'gallery')) {
    return;
  }

  // 检查是否添加了特色图片
  if ($meta_key === '_thumbnail_id') {
    // 如果添加了特色图片，则执行相应操作
    $thumbnail_url = wp_get_attachment_image_src($meta_value, 'large');
    // error_log("文章 ID 为 $post_id 的特色图片 URL 是：" . $thumbnail_url[0]);

    // 获取特色图片的主题色
    $image_theme_color = get_image_theme_color($thumbnail_url[0], true);
    if (isset($image_theme_color['api']) && $image_theme_color['api'] == true) {
      update_post_meta($post_id, 'image_theme_color', $image_theme_color);
      // 更新图片到媒体库
      $new_url = $image_theme_color['url'];
      add_image_url_to_media($post_id, $new_url);
    } else {
      update_post_meta($post_id, 'image_theme_color', $image_theme_color);
    }
    
    // 执行完获取新的附件后，获取新的特色图片的 URL
    $new_thumbnail_id = get_post_thumbnail_id($post_id);
    $thumbnail_url = wp_get_attachment_image_src($new_thumbnail_id, 'large');
  }
}

function updated_post_image($meta_id, $post_id, $meta_key, $meta_value) {
  // 检查是否是自动保存或者是其他非用户提交的情况，如果是则直接返回
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // 检查文章类型是否是 'post' 或 'page'，如果不是，则返回
  $post_type = get_post_type($post_id);
  if (($post_type !== 'post') && ($post_type !== 'page') && ($post_type !== 'gallery')) {
    return;
  }

  // 检查是否更换了特色图片
  if ($meta_key === '_thumbnail_id') {
    if (empty($meta_value)) {
      // error_log("删除了特色图片");

      // 获取文章内容第一张图片url并获取主题色，没有则返回空
      $image_url = get_cover_url($post_id, 'medium', get_post($post_id)->post_content);
      $image_theme_color = get_image_theme_color($image_url);
      update_post_meta($post_id, 'image_theme_color', $image_theme_color);
    } else {
      // 如果更新了特色图片，则获取特色图片的 URL
      $thumbnail_url = wp_get_attachment_image_src($meta_value, 'large');
      // error_log("文章 ID 为 $post_id 的特色图片 URL 是：" . $thumbnail_url[0]);

      // 获取特色图片的主题色
      $image_theme_color = get_image_theme_color($thumbnail_url[0], true);
      if (isset($image_theme_color['api']) && $image_theme_color['api'] == true) {
        update_post_meta($post_id, 'image_theme_color', $image_theme_color);
        // 更新图片到媒体库
        $new_url = $image_theme_color['url'];
        add_image_url_to_media($post_id, $new_url);
        // 执行完获取新的附件后，获取新的特色图片的 URL
        $new_thumbnail_id = get_post_thumbnail_id($post_id);
        $thumbnail_url = wp_get_attachment_image_src($new_thumbnail_id, 'large');
      } else {
        update_post_meta($post_id, 'image_theme_color', $image_theme_color);
      }
    }
  }
}

function deleted_post_image($meta_ids, $post_id, $meta_key, $only_delete_some) {
  // 检查是否是自动保存或者是其他非用户提交的情况，如果是则直接返回
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // 检查文章类型是否是 'post' 或 'page'，如果不是，则返回
  $post_type = get_post_type($post_id);
  if (($post_type !== 'post') && ($post_type !== 'page') && ($post_type !== 'gallery')) {
    return;
  }

  // 检查是否删除了特色图片
  if ($meta_key === '_thumbnail_id') {
    // 如果删除了特色图片，则执行其他逻辑
    // error_log("文章 ID 为 $post_id 的文章删除了特色图片。");

    // 获取文章内容第一张图片url并获取主题色，没有则返回空
    $image_url = get_cover_url($post_id, 'medium', get_post($post_id)->post_content);
    $image_theme_color = get_image_theme_color($image_url);
    update_post_meta($post_id, 'image_theme_color', $image_theme_color);

    // 执行完获取新的附件后，获取新的特色图片的 URL
    $new_thumbnail_id = get_post_thumbnail_id($post_id);
    $thumbnail_url = wp_get_attachment_image_src($new_thumbnail_id, 'large');
    return $thumbnail_url;
  }
}

function save_post_action($post_id, $post, $update) {
  // 检查是否是自动保存或者是其他非用户提交的情况，如果是则直接返回
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  // 检查文章类型是否是 'post' 或 'page'，如果不是，则返回
  $post_type = get_post_type($post_id);
  if (($post_type !== 'post') && ($post_type !== 'page') && ($post_type !== 'gallery')) {
    return;
  }

  // 检查是否添加了特色图片
  $thumbnail_id = get_post_meta($post_id, '_thumbnail_id', true);
  if (empty($thumbnail_id)) {
    // 如果没有添加特色图片，则执行逻辑
    // error_log("新建文章 ID 为 $post_id 的文章没有添加特色图片。");

    // 获取文章内容第一张图片url并获取主题色，没有则返回空
    $image_url = get_cover_url($post_id, 'medium', get_post($post_id)->post_content);
    $image_theme_color = get_image_theme_color($image_url);
    update_post_meta($post_id, 'image_theme_color', $image_theme_color);
  }
}


// 提取图像主题色的函数（优化版：消除重复代码）
function get_image_theme_color($image_url, bool $redirect = false): ?array {
  if (empty($image_url)) {
    return null;
  }

  // 处理重定向
  $is_api = false;
  if ($redirect) {
    $redirected_url = is_redirect($image_url);
    if (!empty($redirected_url)) {
      $image_url = $redirected_url;
      $is_api = true;
    }
  } else {
    $image_url = image_final_url($image_url);
  }

  if (empty($image_url)) {
    return null;
  }

  try {
    $main_color = @ColorThief::getPalette($image_url, 2);
    
    if (!is_array($main_color) || !isset($main_color[0], $main_color[1])) {
      return null;
    }
    
    $red = (int) $main_color[0][0];
    $green = (int) $main_color[0][1];
    $blue = (int) $main_color[0][2];
    
    // 增强暗色
    if ($red < 100 && $green < 100 && $blue < 100) {
      $red = min(255, $red * 2);
      $green = min(255, $green * 2);
      $blue = min(255, $blue * 2);
    }
    
    // 计算亮度
    $color_depth = $red * 0.299 + $green * 0.587 + $blue * 0.114;
    
    // 只返回较暗的颜色（用于主题色）
    if ($color_depth >= 200) {
      return null;
    }
    
    $theme_color = array(
      'pri' => rgbToHex($red, $green, $blue),
      'sub' => rgbToHex($main_color[1][0], $main_color[1][1], $main_color[1][2]),
      'url' => $image_url,
    );
    
    if ($redirect && $is_api) {
      $theme_color['api'] = true;
    }
    
    return $theme_color;
    
  } catch (Exception $e) {
    return null;
  }
}


// 获取图片最终链接（优化版：使用 WordPress HTTP API）
function image_final_url(string $url): string {
  if (empty($url)) {
    return '';
  }
  
  // 使用 WordPress HTTP API 替代 curl
  $response = wp_remote_head($url, array(
    'redirection' => 10,
    'timeout' => 5,
    'sslverify' => false,
  ));
  
  if (is_wp_error($response)) {
    return '';
  }
  
  // 获取最终 URL（跟随重定向后）
  $final_url = wp_remote_retrieve_header($response, 'location');
  
  // 如果没有重定向头，返回原始 URL
  return !empty($final_url) ? $final_url : $url;
}


// 判断图片链接是否有重定向
function is_redirect($url) {
  $final = image_final_url($url);
  if (!empty($final) && $final != $url) {
    return $final;
  } else {
    return '';
  }
}


// RGB -> HEX（优化版：使用 sprintf）
function rgbToHex(int $r, int $g, int $b): string {
  // 确保 RGB 值在合法范围内
  $r = max(0, min(255, $r));
  $g = max(0, min(255, $g));
  $b = max(0, min(255, $b));

  // 使用 sprintf 更高效地转换
  return sprintf('#%02x%02x%02x', $r, $g, $b);
}


// 获取主色调和副色调（优化版：使用静态缓存）
function theme_color(): object {
  static $cached_color = null;
  
  // 如果已经计算过，直接返回缓存结果
  if ($cached_color !== null) {
    return $cached_color;
  }
  
  $object = new stdClass();
  $object->pri = '';
  $object->sub = '';
  
  // 使用静态变量缓存选项值
  static $cover_color_enabled = null;
  if ($cover_color_enabled === null) {
    $cover_color_enabled = get_option('oyiso_cover_color');
  }
  
  if (!$cover_color_enabled) {
    $cached_color = $object;
    return $object;
  }
  
  $post_id = get_the_ID();
  if (!$post_id) {
    $cached_color = $object;
    return $object;
  }
  
  $image_theme_color = get_post_meta($post_id, 'image_theme_color', true);
  
  if (empty($image_theme_color) || !is_array($image_theme_color)) {
    $cached_color = $object;
    return $object;
  }
  
  if (isset($image_theme_color['pri']) && !empty($image_theme_color['pri'])) {
    $cover_url = get_cover_url($post_id, 'large', get_the_content());
    if ($cover_url) {
      $object->pri = $image_theme_color['pri'];
      $object->sub = $image_theme_color['sub'] ?? '';
    }
  }
  
  $cached_color = $object;
  return $object;
}

// 色彩同步
function root_color($pri, $sub) {
  echo "
    <style>
      :root {
        --theme-color-pri: ".$pri.";
        --theme-color-sub: ".$sub.";
        --theme-color-op70: ".$pri."b3;
        --theme-color-op50: ".$pri."80;
        --theme-color-op30: ".$pri."4d;
        --theme-color-op20: ".$pri."33;
        --theme-color-op10: ".$pri."1a;
        --theme-color-op15: ".$pri."26;
        --theme-color-op05: ".$pri."0d;
      }
    </style>
  ";
}

// 色彩同步（优化版：减少重复调用）
function theme_color_sync(bool $foreach = false): string {
  // 静态缓存主题颜色选项
  static $cover_color_enabled = null;
  static $default_theme_colors = null;
  
  if ($cover_color_enabled === null) {
    $cover_color_enabled = get_option('oyiso_cover_color');
  }
  
  if ($default_theme_colors === null) {
    $default_theme_colors = get_option('oyiso_theme_colors');
  }
  
  // 获取当前文章的主题色
  $current_theme_color = theme_color();
  $has_custom_color = $cover_color_enabled && !empty($current_theme_color->pri);
  
  if ($foreach) {
    // 仅返回主色调值
    if ($has_custom_color) {
      return $current_theme_color->pri;
    }
    return '';
  }
  
  // 输出 CSS 样式
  if ($has_custom_color) {
    root_color($current_theme_color->pri, $current_theme_color->sub);
  } elseif ($default_theme_colors && isset($default_theme_colors->pri) && !empty($default_theme_colors->pri)) {
    root_color($default_theme_colors->pri, $default_theme_colors->sub);
  }
  
  return '';
}


function get_theme_color() {
  $theme_colors = get_option('oyiso_theme_colors');
  if (isset($theme_colors->pri) && !empty($theme_colors->pri)) {
    root_color($theme_colors->pri, $theme_colors->sub);
  }
}


// 获取文章第一张图片
function first_post_cover($content) {
  if ($content === false) $content = get_the_content();
  preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $content, $images);
  if (isset($images[1][0])) {      
    return $images[1][0];
  } else {
    return false;
  }
}


// 获取封面图（取色处理）
function get_cover_url($post_id, string $size, $content) {
  if (has_post_thumbnail($post_id)) {
    $thumbnail_url = get_the_post_thumbnail_url($post_id, $size);
    return $thumbnail_url;
  } else {
    if (first_post_cover($content)) {
      return first_post_cover($content);
    } else {
      if (get_option('oyiso_cover_color')) { // 用户自定义取色封面，有的话文章直接显示（需要取色）
        if (get_option('oyiso_cover_color_url')) {
          return get_option('oyiso_cover_color_url');
        } else {
          return null;
        }
      } else {
        return null;
      }
    }
  }
}


// 文章列表封面（不做取色处理）
function the_cover_url($size, $i, $post_id = false, $content = false) {
  if (!$post_id) {
    $post_id = get_the_ID();
  }
  if (empty($content)) {
    $content = get_the_content();
    // error_log($content);
  }
  $cover_url = get_cover_url($post_id, $size, $content);
  if (!empty($cover_url)) {
    if (preg_match("/\?/", $cover_url)) {
      return $cover_url.'&'.$i;
    } else {
      return $cover_url.'?'.$i;
    }
    
  } else {
    if (get_option('oyiso_post_cover')) {
      $url = get_option('oyiso_post_cover'); // 用户自定义默认封面（不取色）
      if (preg_match("/\?/", $url)) {
        return $url.'&'.$i;
      } else {
        return $url.'?'.$i;
      }
    } else {
      return file_uri()."/assets/images/cover-post.jpg";
    }
  }
}


// 获取图片url插入附件中
function add_image_url_to_media($post_id, $url, $desc = null) {

  $response = wp_remote_head($url);
  if (is_array($response) && isset($response['headers']['content-type'])) {
    $type_of_the_image = $response['headers']['content-type'];
  }

  // 创建新的附件数组
  $attachment = array(
      'guid'           => $url, 
      'post_mime_type' => $type_of_the_image,
      'post_title'     => preg_replace('/\.[^.]+$/', '', basename($url)),
      'post_content'   => '',
      'post_status'    => 'inherit'
  );

  // 插入新的附件
  $attach_id = wp_insert_attachment($attachment, false, $post_id);

  // 生成附件元数据
  $attach_data = wp_generate_attachment_metadata($attach_id, $url);

  // 更新附件元数据
  wp_update_attachment_metadata($attach_id, $attach_data);

  // 设置新的附件为文章的封面图
  set_post_thumbnail($post_id, $attach_id);
}
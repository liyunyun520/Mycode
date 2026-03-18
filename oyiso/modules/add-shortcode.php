<?php

// TIP
function shortcode_tip($atts, $content = false) {
  // デフォルト値
  $default_type = 'info';
  $default_title = 'ヒント';

  // パラメータを取得
  $atts = shortcode_atts(array(
    'type' => $default_type,
    'title' => $default_title,
  ), $atts, 'tip');

  $type = $atts['type'];
  $title = !empty($atts['title']) ? $atts['title'] : $default_title;

  // ヒントタイプ
  switch ($type) {
    case 'info':
      $icon = 'icon-info-full';
      break;
    case 'warning':
      $icon = 'icon-warning-full';
      break;
    case 'success':
      $icon = 'icon-success-full';
      break;
    case 'danger':
      $icon = 'icon-danger-full';
      break;
    default:
      $icon = 'icon-info-full';
      $type = 'info';
      break;
  }

  if (!empty($content)) {
    $content = "<p>{$content}</p>";
  }

  $output = "
    <div class='shortcode-tip {$type}'>
      <svg class='icon' aria-hidden='true'>
        <use xlink:href='#{$icon}'></use>
      </svg> <span>{$title}</span>
      {$content}
    </div>
  ";

  return $output;
}
add_shortcode('tip', 'shortcode_tip');
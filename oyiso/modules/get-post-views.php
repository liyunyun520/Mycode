<?php

// 设置文章浏览量
function set_post_views() {
  if (is_single()) {
    $post_id = get_the_ID();
    $views = get_post_meta($post_id, 'post_views', true) ?: 0;
    $views++;
    update_post_meta($post_id, 'post_views', $views);
  }
}
add_action('wp_head', 'set_post_views');

// 获取文章浏览量
function get_post_views() {
  $post_id = get_the_ID();
  $views = get_post_meta($post_id, 'post_views', true) ?: 0;
  echo $views;
}


// 设置文章热度
function set_post_heat() {
  $post_id = get_the_ID();
  $current_heat = get_post_meta($post_id, 'post_heat', true);
  if (!$current_heat || $current_heat < 25) {
    $new_heat = rand(25, 50);
  } else {
    $new_heat = $current_heat + rand(25, 50);
  }
  update_post_meta($post_id, 'post_heat', $new_heat);
}

// 显示文章热度
function get_post_heat() {
  $post_id = get_the_ID();
  $heat = get_post_meta($post_id, 'post_heat', true);
  if (!$heat) {
    $heat = rand(25, 50);
    update_post_meta($post_id, 'post_heat', $heat);
  }
  return $heat;
}


// 设置文章点赞
add_action('wp_ajax_post_like', 'post_like');
add_action('wp_ajax_nopriv_post_like', 'post_like');

function post_like() {
  if (!empty($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $count = get_post_meta($post_id, 'post_likes', true);
    if($count==''){
      add_post_meta($post_id, 'post_likes', '1');
      echo '1';
    } else {
      $count++;
      update_post_meta($post_id, 'post_likes', $count);
      echo $count;
    }
  }
  wp_die();
}
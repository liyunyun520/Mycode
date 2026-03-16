<?php
if (!defined('ABSPATH')) {
  exit;
}

// 注册链接
add_filter('pre_option_link_manager_enabled', '__return_true');

// 添加排序字段到链接分类
function add_link_category_order_field() {
  ?>
  <div class="form-field term-order-wrap">
    <label for="link-category-order">排序</label>
    <input type="number" name="link_category_order" id="link-category-order" value="1">
    <p>为链接分类添加排序，从 <code>1</code> 开始，数值越小权重越高，排序越靠前。</p>
    <p>填 <code>0</code> 则此分类排序在最后面，通常表示失联或禁止访问。</p>
    <p>填 <code>-1</code> 则此分类不在前台显示。</p>
  </div>
  <?php
}
add_action('link_category_add_form_fields', 'add_link_category_order_field');

// 在链接分类编辑页面显示排序字段
function edit_link_category_order_field($term) {
  $order = get_term_meta($term->term_id, 'link_category_order', true);
  ?>
  <tr class="form-field term-order-wrap">
    <th scope="row"><label for="link-category-order">排序</label></th>
    <td>
      <input type="number" name="link_category_order" id="link-category-order" value="<?=$order?>">
      <p class="description">为链接分类添加排序，从 <code>1</code> 开始，数值越小权重越高，排序越靠前。</p>
      <p class="description">填 <code>0</code> 则此分类排序在最后面，通常表示失联或禁止访问。</p>
      <p class="description">填 <code>-1</code> 则此分类不在前台显示。</p>
    </td>
  </tr>
  <?php
}
add_action('link_category_edit_form_fields', 'edit_link_category_order_field');

// 保存链接分类的排序字段值
function save_link_category_order_field($term_id) {
  if (isset($_POST['link_category_order'])) {
    $order = intval($_POST['link_category_order']);
    update_term_meta($term_id, 'link_category_order', $order);
  }
}
add_action('edited_link_category', 'save_link_category_order_field');
add_action('create_link_category', 'save_link_category_order_field');


// 友链自助添加

// 发送邮件通知管理员
function send_email($link_name, $link_url, $link_description, $link_image, $link_category_id, $link_id, $link_email, $who, $pass=false) {
  ignore_user_abort(true);
  set_time_limit(600);

  $theme_colors = get_option('oyiso_theme_colors');
  if (isset($theme_colors->pri) && !empty($theme_colors->pri)) {
    $theme_color_pri = $theme_colors->pri;
  } else {
    $theme_color_pri = '#8183ff';
  }
  $email_css_ul = "style='background-color:#f5f5f5;padding:10px 15px;border-left:3px solid {$theme_color_pri};'"; // 邮件样式
  $email_css_li = "style='margin-left: 1.2rem;margin-top: 0.5rem;margin-bottom: 0.5rem;'"; // 邮件样式
  $smtp_sitename = get_option('oyiso_smtp_sitename'); // 自定义网站名称
  $site_name = $smtp_sitename ?: get_bloginfo('name'); // 网站名称
  $site_url = get_bloginfo('url');
  $link_category_term = get_term($link_category_id, 'link_category');
  $link_category = ($link_category_term && !is_wp_error($link_category_term)) ? $link_category_term->name : '';

  // 通知管理员或用户
  if ($who == 'admin') {
    $link_edit_url = admin_url('link.php?action=edit&link_id='.$link_id); // 审核地址
    $user_ip = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''); // 获取用户IP
    $location = get_user_location($user_ip, ' - '); // 获取用户IP归属地

    $to = get_option('admin_email');
    $subject = "[{$site_name}] 友链：{$link_name} 请求申请友链！";
    $message = "
      <p>您的网站有新的朋友（来自：{$location}）申请友情链接！</p>
      <ul {$email_css_ul}>
        <li {$email_css_li}>名称：{$link_name}</li>
        <li {$email_css_li}>地址：{$link_url}</li>
        <li {$email_css_li}>描述：{$link_description}</li>
        <li {$email_css_li}>图标：{$link_image}</li>
        <li {$email_css_li}>分类：{$link_category}</li>
        <li {$email_css_li}>邮箱：{$link_email}</li>
      </ul>
      <p>审核地址：<a href='{$link_edit_url}'>{$link_edit_url}</a></p>
      <p>博客地址：<a href='{$site_url}'>{$site_url}</a></p>
    ";
    
  } else if ($who == 'user') {
    $to = $link_email;
    $subject = "[{$site_name}] 已收到您的友链申请！";
    $message = "
      <p>本站已收到您的友链申请，请等待管理员审核！</p>
      <p>您的申请信息如下：</p>
      <ul {$email_css_ul}>
        <li {$email_css_li}>名称：{$link_name}</li>
        <li {$email_css_li}>地址：{$link_url}</li>
        <li {$email_css_li}>描述：{$link_description}</li>
        <li {$email_css_li}>图标：{$link_image}</li>
        <li {$email_css_li}>分类：{$link_category}</li>
        <li {$email_css_li}>邮箱：{$link_email}</li>
      </ul>
      <p>博客地址：<a href='{$site_url}'>{$site_url}</a></p>
    ";
  }

  // 审核通过
  if ($pass) {
    $subject = "[{$site_name}] 友链：{$link_name} 已通过审核！";
    $message = "
      <p>您的网站已通过审核，已经正式成为本站的友情链接！</p>
      <ul {$email_css_ul}>
        <li {$email_css_li}>名称：{$link_name}</li>
        <li {$email_css_li}>地址：{$link_url}</li>
        <li {$email_css_li}>描述：{$link_description}</li>
        <li {$email_css_li}>图标：{$link_image}</li>
        <li {$email_css_li}>分类：{$link_category}</li>
        <li {$email_css_li}>邮箱：{$link_email}</li>
      </ul>
      <p>博客地址：<a href='{$site_url}'>{$site_url}</a></p>
    ";
  }

  $headers = array('Content-Type: text/html; charset=UTF-8');
  wp_mail($to, $subject, $message, $headers);
}

// 添加友链ajax请求处理
add_action('wp_ajax_my_friends', 'my_friends');
add_action('wp_ajax_nopriv_my_friends', 'my_friends');

function my_friends() {
  // 验证 nonce
  if (!wp_verify_nonce($_POST['nonce'] ?? '', 'oyiso_friends_nonce')) {
    wp_die('security_error');
  }

  // 判断是否开启友链自助申请
  if (!get_option('oyiso_friend_sup')) {
    wp_die('closed');
  }

  if (!empty($_POST['name']) && !empty($_POST['url']) && !empty($_POST['category']) && !empty($_POST['email'])) {
    $link_name = sanitize_text_field($_POST['name']);
    $link_url = esc_url_raw($_POST['url']);
    $link_description = sanitize_textarea_field($_POST['description']);
    $link_image = esc_url_raw($_POST['image']);
    $link_category_id = intval($_POST['category']);
    $link_email = sanitize_email($_POST['email']);

    //判断是否存在该链接
    $link_exists = get_bookmarks(array('search' => rtrim($link_url, '/')));
    if ($link_exists) {
      wp_die('exists');
    }

    $link_id = wp_insert_link(array(
      'link_name' => $link_name,
      'link_url' => $link_url,
      'link_description' => $link_description,
      'link_image' => $link_image,
      'link_category' => $link_category_id,
      'link_notes' => $link_email,
      'link_visible' => 'N',
      // 'link_rating' => '1',
      'link_target' => '_blank',
    ));

    if ($link_id) {
      update_option('bookmark_pass_' . $link_id, 'no');
      send_email($link_name, $link_url, $link_description, $link_image, $link_category_id, $link_id, $link_email, 'admin'); // 通知管理
      send_email($link_name, $link_url, $link_description, $link_image, $link_category_id, $link_id, $link_email, 'user'); // 通知申请用户
      wp_die('success');
    }
  } else {
    wp_die('error');
  }
}


// 给链接添加一个第一次审核字段
function add_custom_bookmark_field() {
  add_meta_box(
    'custom_bookmark_field',
    '审核状态',
    'custom_bookmark_field_callback',
    'link',
    'normal',
    'high'
  );
}
add_action('add_meta_boxes', 'add_custom_bookmark_field');
function custom_bookmark_field_callback($link) {
  if (isset($link->link_id)) {
    $pass = get_option('bookmark_pass_' . $link->link_id, '');
    if ($pass == 'no') {
      echo '<div class="link_pass"><p style="color:red">待审核</p><input id="'.$link->link_id.'" type="submit" class="button button-primary button-large" value="通过审核"></div>';
    } else {
      echo '<div class="link_pass"><p style="color:green">已审核</p><input id="'.$link->link_id.'" type="submit" class="button button-primary button-large" value="取消审核"></div>';
    }
  } else {
    echo '<div class="link_pass"><p>添加后自动通过审核</p></div>';
  }
}
function pass_link($link_id) {
  $bookmark = get_bookmark($link_id);
  $link_visible = $bookmark->link_visible;
  $pass = get_option('bookmark_pass_' . $link_id, '');
  if ($pass == 'no' && $link_visible == 'Y') {
    update_option('bookmark_pass_' . $link_id, '');

    // 发送邮件通知用户
    $link_name = $bookmark->link_name;
    $link_url = $bookmark->link_url;
    $link_description = $bookmark->link_description;
    $link_image = $bookmark->link_image;
    $link_category_id = isset($bookmark->link_category[0]) ? $bookmark->link_category[0] : null;
    $link_email = $bookmark->link_notes;
    if (!empty($link_email) && is_email($link_email)) {
      send_email($link_name, $link_url, $link_description, $link_image, $link_category_id, $link_id, $link_email, 'user', true); // 通知申请用户
    }
  }
}
add_action('edit_link', 'pass_link');

add_action('wp_ajax_link_pass', 'link_pass');
function link_pass() {
  // 验证权限
  if (!current_user_can('manage_links')) {
    wp_die('permission_denied');
  }
  
  // 验证输入
  if (!isset($_POST['link_id'])) {
    wp_die('missing_id');
  }
  
  $link_id = intval($_POST['link_id']);
  if ($link_id <= 0) {
    wp_die('invalid_id');
  }
  
  $pass = get_option('bookmark_pass_' . $link_id, '');
  if ($pass == 'no') {
    update_option('bookmark_pass_' . $link_id, '');
    wp_die('pass');
  } else {
    update_option('bookmark_pass_' . $link_id, 'no');
    wp_die('cancel');
  }
}
<?php
  if (!defined('ABSPATH')) {
    exit;
  }
  
  if (!is_admin() || !current_user_can('administrator')) {
    return;
  }
  
  wp_enqueue_media();
  wp_enqueue_script('opt', file_uri().'/admin/option.js', array('media-editor'), '1.5.3', true);
  $user_id = get_current_user_id();
  $user_data = get_userdata($user_id);
  
  // $user_data が存在することを確認
  if (!$user_data) {
    wp_die('ユーザーデータを取得できません');
  }
  
  $user_display_name = esc_html($user_data->display_name ?? '');
  $user_email = esc_html($user_data->user_email ?? '');
  $avatar_url = esc_url(get_avatar_url($user_id));
  $custom_avatar_url = esc_url(get_user_meta($user_id, 'avatar_url', true) ?: '');
?>

<link rel="stylesheet" href="<?=esc_url(file_uri())?>/admin/option.css">
<link rel="stylesheet preload" as="style" href="<?=esc_url(file_uri())?>/assets/iconfont/iconfont.css">
<script src="<?=esc_url(file_uri())?>/assets/iconfont/iconfont.js"></script>
<?php
  $theme_colors = get_option('oyiso_theme_colors');
  if (isset($theme_colors->pri) && !empty($theme_colors->pri)) {
    $theme_color_pri = sanitize_hex_color($theme_colors->pri);
  }
  if (empty($theme_color_pri)) {
    $theme_color_pri = '#8183ff';
  }
  echo '<style>
    :root{
      --theme-color-pri:'.esc_attr($theme_color_pri).';
      --theme-color-op10:'.esc_attr($theme_color_pri).'1a;
    }
  </style>';
?>

<div class="container oyiso">
  <div class="banner">
    <h2 class="title">Oyiso's Theme Settings</h2>
    <div class="save">
      <span class="tip"></span>
      <button type="submit" disabled>
        <svg class="icon" aria-hidden="true">
          <use xlink:href="#icon-upload-cloud-fill"></use>
        </svg><span>変更を保存</span>
      </button>
    </div>
  </div>
  <main>
    <div class="sidebar">
      <div class="admin">
        <div class="avatar">
          <img src="<?=$avatar_url?>" alt="">
          <div class="panel">
            <div class="choose">
              <input type="text" placeholder="画像URLを入力" value="<?=$custom_avatar_url?>">
              <button class="media">メディアライブラリ</button>
            </div>
            <div class="more">
              <button class="save">アバターを保存</button>
              <button class="restore">デフォルトに戻す</button>
            </div>
          </div>
        </div>
        <div class="info">
          <div class="name"><?=$user_display_name?></div>
          <div class="email"><?=$user_email?></div>
        </div>
      </div>
      <ul>
        <li data-item="opt_basic">
          <a>
          <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-dashboard-fill"></use>
            </svg><span>基本設定</span>
          </a>
        </li>
        <li data-item="opt_home">
          <a>
          <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-home-smile-fill"></use>
            </svg><span>ホーム設定</span>
          </a>
        </li>
        <li data-item="opt_pages">
          <a>
            <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-pages-fill"></use>
            </svg><span>ページ設定</span></a>
        </li>
        <li data-item="opt_posts">
          <a>
            <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-article-fill"></use>
            </svg><span>記事設定</span></a>
        </li>
        <li data-item="opt_comments">
          <a>
            <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-chat-smile-square-fill"></use>
            </svg><span>コメント設定</span></a>
        </li>
        <li data-item="opt_footer">
          <a>
            <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-footer-fill"></use>
            </svg><span>フッター設定</span></a>
        </li>
        <li data-item="opt_other">
          <a>
            <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-settings-square-fill"></use>
            </svg><span>その他の設定</span></a>
        </li>
        <li data-item="opt_custom">
          <a>
            <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-code-box-fill"></use>
            </svg><span>カスタムコード</span></a>
        </li>
        <li data-item="opt_update">
          <a>
            <svg class="icon" aria-hidden="true">
              <use xlink:href="#icon-primary-full"></use>
            </svg><span>更新を確認</span></a>
        </li>
      </ul>
    </div>
    <form class="content">
      <ul>
        <li><?php require_once get_template_directory().'/admin/option/opt-basic.php'; ?></li>
        <li><?php require_once get_template_directory().'/admin/option/opt-home.php'; ?></li>
        <li><?php require_once get_template_directory().'/admin/option/opt-pages.php'; ?></li>
        <li><?php require_once get_template_directory().'/admin/option/opt-posts.php'; ?></li>
        <li><?php require_once get_template_directory().'/admin/option/opt-comments.php'; ?></li>
        <li><?php require_once get_template_directory().'/admin/option/opt-footer.php'; ?></li>
        <li><?php require_once get_template_directory().'/admin/option/opt-other.php'; ?></li>
        <li><?php require_once get_template_directory().'/admin/option/opt-custom.php'; ?></li>
        <li><?php require_once get_template_directory().'/admin/option/opt-update.php'; ?></li>
      </ul>
    </form>
  </main>
</div>

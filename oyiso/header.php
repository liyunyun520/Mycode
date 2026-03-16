<?php
  // 検索エンジンのインデックスを禁止
  $robots = is_search() ? 'noindex, follow' : 'index, follow';

  // テーマバージョン番号
  $theme_version = '?ver='.wp_get_theme()->get('Version');
?>

<!DOCTYPE html>
<html <?=language_attributes()?>>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="renderer" content="webkit">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <meta name="robots" content="<?=$robots?>">
  <meta name="keywords" content="<?=is_home() ? bloginfo('name') : the_title()?>">
  <title><?=function_exists('show_wp_title') ? show_wp_title() : bloginfo('name')?></title>
  <link rel="shortcut icon" href="<?=has_site_icon() ? site_icon_url() : file_uri().'/assets/images/wp-logo-blue.png'?>" type="image/x-icon">
  <link rel="stylesheet preload" as="style" href="<?=file_uri()?>/assets/css/main.css<?=$theme_version?>">
  <link rel="stylesheet preload" as="style" href="<?=file_uri()?>/assets/css/format.css<?=$theme_version?>">
  <link rel="stylesheet preload" as="style" href="<?=file_uri()?>/assets/css/main-m.css<?=$theme_version?>">
  <link rel="stylesheet preload" as="style" href="<?=file_uri()?>/assets/fancybox/fancybox.css<?=$theme_version?>">
  <link rel="stylesheet preload" as="style" data-light href="<?=file_uri()?>/assets/prism/prism.default.css<?=$theme_version?>">
  <link rel="alternate stylesheet preload" as="style" data-dark href="<?=file_uri()?>/assets/prism/prism.vsc.dark.css<?=$theme_version?>">
  <link rel="stylesheet preload" as="style" href="<?=file_uri()?>/assets/iconfont/iconfont.css<?=$theme_version?>">
  <script src="<?=file_uri()?>/assets/js/ahead.js<?=$theme_version?>"></script>
  <script src="<?=file_uri()?>/assets/iconfont/iconfont.js<?=$theme_version?>"></script>
  <script src="<?=file_uri()?>/assets/js/pjax-api.js<?=$theme_version?>"></script>
  <script src="<?=file_uri()?>/assets/js/scrollreveal.min.js<?=$theme_version?>"></script>
  <?=get_theme_color()?>
  <?=lazy_load() ? '<script>let loading_img = "'.lazy_load().'"</script>' : '<script>let loading_img = false</script>'?>
  <?=get_option('oyiso_custom_html_head')?stripslashes(get_option('oyiso_custom_html_head')):''?>
  <?=get_option('oyiso_custom_css')?'<style>'.stripslashes(get_option('oyiso_custom_css')).'</style>':''?>
</head>
<body>
  <div class="container">
    <?php
      if (get_option('oyiso_home_tpl')) {
        $home_class = get_option('oyiso_home_tpl').' active';
        global $def_img;
        $def_img = file_uri().'/assets/images/default-cover.jpg';
      } else {
        $home_class = 'home active';
        $def_img = '';
      }
    ?>
    <script>let def_img = '<?=$def_img?>';</script>
    <main id="pjax-box" class="<?=$home_class?>">
      <div class="overlay"></div>
      <?php
        if (get_option('oyiso_navbar_animation')) {
          echo '<header class="header-reveal">';
        } else {
          echo '<header>';
        }
      ?>
        <div class="navbar">
          <nav>
            <div class="nav-menu">
              <div class="left">
                <div class="menu-btn">
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
                <div class="title">
                  <a href="<?=bloginfo('url')?>"><?=bloginfo('name')?></a>
                </div>
                <div class="menu-group">
                  <?php
                    $header_menu = wp_nav_menu(array(
                      'theme_location' => 'menu', // 登録されたメニュー識別子
                      'menu_id' => 'menu', // メニューのID
                      'container_class' => 'menu-div', // 外部コンテナのCSSクラス名
                      'fallback_cb' => 'menu_fallback'
                    ));
                  ?>
                </div>
              </div>
              <div class="right">
                <?=get_search_form()?>
                <div class="toc-btn"></div>
                <div class="user">
                  <div class="box">
                    <svg class="icon" aria-hidden="true">
                      <use xlink:href="#icon-user-6-fill"></use>
                    </svg>
                  </div>
                  <box class="info">
                    <div class="top">
                      <?php
                        $logged = is_user_logged_in();
                        if ($logged) {
                          $user_id = get_current_user_id();
                          $user_info = get_userdata($user_id);
                          $avatar = get_avatar_url($user_id);
                          $name = $user_info -> display_name;
                          $desc = $user_info -> description;
                        } else {
                          $user_info = get_userdata(1);
                          $avatar = get_avatar_url(1);
                          $name = $user_info -> display_name;
                          $desc = $user_info -> description;
                        }
                      ?>
                      <div class="avatar">
                        <img src="<?=$avatar?>" alt="">
                      </div>
                      <div class="msg">
                        <h2 class="name" title="<?=$name?>"><?=$name?></h2>
                        <?=$desc ? "<p title='{$desc}'>{$desc}</p>" : ''?>
                      </div>
                    </div>
                    <div class="content <?=$logged?'logged':''?>">
                      <?php
                        if ($logged) {
                          if (current_user_can('administrator')) {
                            echo '<a href="'.admin_url().'" class="setting" target="_blank">管理</a>';
                          }
                          echo '<a href="'.wp_logout_url(home_url(add_query_arg(array()))).'" class="logout">ログアウト</a>';
                        } else {
                          echo '<a href="'.wp_login_url(home_url(add_query_arg(array()))).'" class="login">ログイン</a>';
                        }
                      ?>
                    </div>
                  </box>
                </div>
              </div>
            </div>
            <div class="nav-title">
              <span>
                <h2>
                  <p>
                  <?php
                    function now_page() {
                      global $page, $paged;
                      if ( $paged >= 2 || $page >= 2 )
                      echo ' &#8211; ' . sprintf( '%sページ目', max( $paged, $page ) );
                    }
                    if(is_home()) {
                      bloginfo('name');
                      now_page();

                    } else if (is_single() || is_page()) {
                      if (get_the_title()) {
                        the_title();
                      } else {
                        echo 'タイトルなし';
                      }
                      now_page();

                    } else if (is_archive() || is_category() || is_tag()) {
                      single_cat_title();
                      now_page();

                    } else if (is_author()) {
                      the_author_nickname();
                      now_page();

                    } else if (is_date()) {
                      the_time('Y年n月');
                      now_page();

                    } else if (is_search()) {
                      the_search_query();
                      now_page();

                    } else if (is_404()) {
                      echo 'Not Found';
                      
                    } else {
                      echo bloginfo('name');
                    }
                  ?>
                  </p>
                  <div class="backtop">トップに戻る</div>
                </h2>
              </span>
            </div>
          </nav>
        </div>
      </header>
    

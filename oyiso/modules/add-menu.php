<?php 

// メニューを登録
function register_menu() {
  register_nav_menu('menu', __('トップメニュー'));
  register_nav_menu('social', __('ソーシャルメニュー'));
}
add_action('after_setup_theme', 'register_menu');

function menu_fallback() {
  if(is_user_logged_in() && current_user_can('administrator')) {
    if(is_home()) {
      echo "
        <div class='menu-div'>
          <ul id='menu' class='menu'>
            <li class='current-menu-item'>
              <a href='".home_url()."'>ホーム</a>
            </li>
            <li>
              <a href='".home_url()."/wp-admin/nav-menus.php'>メニュー設定</a>
            </li>
          </ul>
        </div>
      ";
    } else {
      echo "
        <div class='menu-div'>
          <ul id='menu' class='menu'>
            <li>
              <a href='".home_url()."'>ホーム</a>
            </li>
            <li>
              <a href='".home_url()."/wp-admin/nav-menus.php'>メニュー設定</a>
            </li>
          </ul>
        </div>
      ";
    }
  } else {
    if(is_home()) {
      echo "
        <div class='menu-div'>
          <ul id='menu' class='menu'>
            <li class='current-menu-item'>
              <a href='".home_url()."'>ホーム</a>
            </li>
          </ul>
        </div>
      ";
    } else {
      echo "
        <div class='menu-div'>
          <ul id='menu' class='menu'>
            <li>
              <a href='".home_url()."'>ホーム</a>
            </li>
          </ul>
        </div>
      ";
    }
  }
}

function social_fallback() {
  echo '
    <div class="socialize">
      <ul id="menu-social" class="menu">
        <li class="github">
          <a title="GitHub" target="_blank" href="https://github.com/kannafay">
            <i class="iconfont icon-github-fill"></i>
            <span>Kannafay</span>
          </a>
        </li>
      </ul>
    </div>
  ';
}

function home1_social_fallback() {
  return '
    <div class="socialize main-reveal">
      <ul id="menu-social" class="menu">
        <li class="github">
          <a title="GitHub" target="_blank" href="https://github.com/kannafay">
            <i class="iconfont icon-github-fill"></i>
            <span>Kannafay</span>
          </a>
        </li>
      </ul>
    </div>
  ';
}
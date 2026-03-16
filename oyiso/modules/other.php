<?php
if (!defined('ABSPATH')) {
  exit;
}

// サイトタイトル
function show_wp_title() {
  global $page, $paged;
  wp_title('&#8211;', true, 'right');
  bloginfo('name');
  $site_description = get_bloginfo( 'description', 'display' );
  if($site_description && (is_home() || is_front_page()))
  echo ' &#8211; '.$site_description;
  if ( $paged >= 2 || $page >= 2 )
  echo ' &#8211; '.sprintf('%sページ目', max($paged, $page));
}


// 読書時間（最適化版：ループ回数を削減）
add_action('save_post', 'reading_time');
function reading_time(int $post_id): void {
  // 記事内容を取得
  $content = get_post_field('post_content', $post_id);
  if (empty($content)) {
    update_post_meta($post_id, 'reading_time', '');
    return;
  }

  // HTMLタグと余分なスペースを削除
  $string = preg_replace('/\s+/u', '', strip_tags($content));
  if (empty($string)) {
    update_post_meta($post_id, 'reading_time', '');
    return;
  }

  // 日本語・中国語文字数をカウント（全角記号を含む）
  $asian_count = preg_match_all('/[\x{4e00}-\x{9fa5}\x{3040}-\x{309f}\x{30a0}-\x{30ff}\x{3000}-\x{303f}\x{ff01}-\x{ff5e}]/u', $string);
  
  // 英語文字数をカウント
  $english_count = preg_match_all('/[a-zA-Z]/', $string);
  
  // 総文字数：日本語・中国語は1文字、英語は0.5文字として計算
  $word_count = $asian_count + ($english_count * 0.5);
  
  // 読書時間を計算（毎分300文字）
  $reading_minutes = max(1, (int) ceil($word_count / 300));
  
  $reading_time = $reading_minutes === 1 ? '1 Min read' : $reading_minutes . ' Mins read';

  // メタデータを更新
  update_post_meta($post_id, 'reading_time', $reading_time);
}


// コメントユーザーIP不正確問題を修正
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
  $_SERVER['REMOTE_ADDR'] = $list[0];
}


// 引用符の半角/全角切り替えを無効化
add_filter('run_wptexturize', '__return_false');


// カテゴリーの記事数を取得（最適化版：キャッシュを使用）
function get_post_count($id, $taxonomy): int {
  // WordPress内蔵関数でカテゴリー記事数を取得
  if ($taxonomy === 'category') {
    $term = get_term($id, 'category');
    if ($term && !is_wp_error($term)) {
      return $term->count;
    }
  }
  
  // 他のタクソノミーの場合はより効率的な方法を使用
  $cache_key = "post_count_{$taxonomy}_{$id}";
  $cached = wp_cache_get($cache_key, 'oyiso');
  
  if (false !== $cached) {
    return (int) $cached;
  }
  
  $args = array(
    'tax_query' => array(                     
      array(
        'taxonomy' => $taxonomy,
        'field' => 'id',
        'terms' => array($id),
        'include_children' => true,
      )
    ),
    'posts_per_page' => 1,
    'fields' => 'ids',
    'no_found_rows' => false,
  );
  $query = new WP_Query($args);
  $count = $query->found_posts;
  
  // 結果を5分間キャッシュ
  wp_cache_set($cache_key, $count, 'oyiso', 300);
  
  return $count;
}


// primary/success/danger/warning/info
function notice(string $status, string $icon, string $text) {
  if(!empty($icon)) {
    return "
      <div class='notice {$status} main-reveal'>
        <svg class='icon' aria-hidden='true'>
          <use xlink:href='#{$icon}'></use>
        </svg>
        <p>{$text}</p>
      </div>
    ";
  } else {
    return "
      <div class='notice {$status} main-reveal'>
        <svg class='icon' aria-hidden='true'>
          <use xlink:href='#icon-{$status}-full'></use>
        </svg>
        <p>{$text}</p>
      </div>
    ";
  }
}


// ページネーション
function the_pagination($wp_query) {
  $pagination = paginate_links(array(
    'total' => $wp_query->max_num_pages, // 現在のクエリの最大ページ数
    'prev_text' => '前へ', // 前のページリンクテキスト
    'next_text' => '次へ', // 次のページリンクテキスト
    'mid_size' => 2, // 現在のページ番号の両側に表示するページ番号数
    'end_size' => 1, // 開始と終了ページ番号の表示数
    'type' => 'array', // リスト形式のページネーションリンクを出力
    'before_page_number' => '<span class="page-number">', // ページ番号前のHTML
    'after_page_number' => '</span>' // ページ番号後のHTML
  ));
  if(!empty($pagination)) {
    echo '<div class="pagination main-reveal">';
    foreach ($pagination as $page_link) {
      echo $page_link;
    }
    echo '</div>';
  }
}


// 検索時にページを除外
// add_filter('register_post_type_args', function($args, $post_type){
//   if($post_type == 'page'){
//     $args['exclude_from_search']  = true;
//   }
//   return $args;
// }, 10, 2);


// 管理画面カスタムスタイルを追加
function custom_admin_style() {
  // 管理画面ページにいることを確認
  if (is_admin()) {
    wp_enqueue_style('admin-css', file_uri().'/assets/css/admin.css');
    wp_enqueue_script('marked-js', file_uri().'/assets/js/marked.min.js');
    wp_enqueue_script('editer-js', file_uri().'/assets/js/admin.js');
  }
}
add_action('admin_enqueue_scripts', 'custom_admin_style');


// プライベートモード
function private_mode() {
  if (!current_user_can('administrator') && !is_login()) {
    wp_redirect(wp_login_url(), 302);
  }
}
if(get_option('oyiso_private')) {
  private_mode();
}


// markdownを出力
function oyiso_md($content) {
  $Parsedown = new Parsedown();
  return $Parsedown->text($content);
}


// エディターフィルター
function oyiso_set_default_editor() {
  $screen = get_current_screen();
  if (($screen -> post_type) == 'gallery') {
    return 'html'; // htmlはテキストモード、tinymceはビジュアルモード
  }
}
add_filter('wp_default_editor', 'oyiso_set_default_editor');


// XX日前（最適化版）
function get_days_ago(string $date, bool $daysago = true): string {
  $timestamp = strtotime($date);
  if ($timestamp === false) {
    return '';
  }
  
  if (!$daysago) {
    return date('Y-m-d H:i', $timestamp);
  }
  
  $diff = time() - $timestamp;
  
  // matchを使用してより効率的に
  return match (true) {
    $diff < 60 => 'たった今',
    $diff < 3600 => floor($diff / 60) . '分前',
    $diff < 86400 => floor($diff / 3600) . '時間前',
    $diff < 2592000 => floor($diff / 86400) . '日前',
    $diff < 31536000 => floor($diff / 2592000) . 'ヶ月前',
    default => floor($diff / 31536000) . '年前',
  };
}


// 遅延読み込み画像（最適化版：オプションをキャッシュ）
function lazy_load(): ?string {
  static $lazyload_url = null;
  
  if ($lazyload_url !== null) {
    return $lazyload_url;
  }
  
  if (!get_option('oyiso_lazyload')) {
    $lazyload_url = null;
    return null;
  }
  
  $custom_url = get_option('oyiso_lazyload_custom');
  if (!empty($custom_url)) {
    $lazyload_url = $custom_url;
    return $lazyload_url;
  }
  
  $preset = get_option('oyiso_lazyload_preset') ?: '1';
  $lazyload_url = file_uri() . '/assets/images/loading/loading' . $preset . '.gif';
  
  return $lazyload_url;
}

// 画像を出力（最適化版：重複呼び出しを削減）
function lazy_load_pic(string $pic, string $reveal = ''): string {
  $lazy_pic = lazy_load();
  
  if ($lazy_pic) {
    $class_attr = $reveal ? ' class="' . esc_attr($reveal) . '"' : '';
    return '<img lazyload data-src="' . esc_url($pic) . '" src="' . esc_url($lazy_pic) . '" alt=""' . $class_attr . '>';
  }
  
  $class_attr = $reveal ? ' class="' . esc_attr($reveal) . '"' : '';
  return '<img src="' . esc_url($pic) . '" alt=""' . $class_attr . '>';
}


// home1ランダム画像出力
function home1_random_img() {
  $imgs = str_replace(array(' ', "\r", "\n"), '', get_option('oyiso_home1_cover'));
  if ($imgs) {
    $imgs = array_filter(explode(',', $imgs));
    if (!empty($imgs)) {
      $random_img = $imgs[array_rand($imgs)];
      return $random_img;
    }
  }
}

// phpタグ置換（最適化版：単一正規置換）
function replaceAToSpan(string $string): string {
  // 単一正規置換ですべてのaタグを置換
  $string = preg_replace('/<a[^>]*>/i', '<span>', $string);
  $string = preg_replace('/<\/a>/i', '</span>', $string);
  return $string;
}

// あるタイプの記事が何ページ目にあるかを検索（最適化版：SQLで直接計算）
function get_moment_page($post_id): int {
  global $wpdb;
  
  // ターゲット記事の公開日を取得
  $target_post = get_post($post_id);
  if (!$target_post || $target_post->post_type !== 'moment') {
    return 1;
  }
  
  $posts_per_page = (int) get_option('posts_per_page', 10);
  if ($posts_per_page < 1) {
    $posts_per_page = 10;
  }
  
  // SQLでその記事の前に何件の公開済みmomentがあるかを計算
  $count = $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->posts} 
     WHERE post_type = 'moment' 
     AND post_status = 'publish' 
     AND post_date > %s",
    $target_post->post_date
  ));
  
  $position = (int) $count + 1;
  return (int) ceil($position / $posts_per_page);
}
<h2>
  <span>
    <svg class="icon" aria-hidden="true">
      <use xlink:href="#icon-article-fill"></use>
    </svg>おすすめ記事
  </span>
</h2>

<div class="widget-box recommend-post">
  <?php
    $sticky_posts = get_option('sticky_posts');
    if ($sticky_posts) {
      $sticky_posts = get_posts(array(
        'post__in' => get_option('sticky_posts'), // 固定記事を取得
        'numberposts' => 10 // 取得する記事数を制限
      ));
      echo '<ul>';
      foreach ($sticky_posts as $post) {
        echo '<li>';
        echo '<p><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></p>'; // 記事タイトルとリンクを表示
        echo '</li>';
      }
      echo '</ul>';
    } else {
      echo '<p>おすすめ記事はありません</p>';
    }
  ?>
</div>
<?=get_header()?>

<?php
  if (get_option('oyiso_widgets')) {
    ?>
    <section class="banner banner-reveal">
      <div class="screen">
        <li class="author">
          <a href="/" class="info">
            <div class="avatar">
              <img src="<?=get_avatar_url(1)?>" alt="">
            </div>
            <div class="n-h">
              <div class="name"><?=get_userdata(1)->display_name?></div>
              <?php
                $author_description = get_userdata(1)->description;
                if (!empty($author_description)) {
                  echo "<div class='hobby'>$author_description</div>";
                }
              ?>
            </div>
          </a>
          <?php
            $nav_social = wp_nav_menu(array( 
              'theme_location'  => 'social',
              'container_class' => 'socialize',
              'fallback_cb'     => 'social_fallback',
              'echo'            => false
            ));
            if (!empty($nav_social)) {
              echo $nav_social;
            } else if ($nav_social === false) {
              social_fallback();
            }
          ?>
        </li>
        <li class="widget">
        <?php
          if (!get_option('oyiso_widgets_content')) {
            get_template_part('widgets/recommend-post');
          } else if (get_option('oyiso_widgets_content') == 'comment') {
            get_template_part('widgets/new-comments');
          }
        ?>
        </li>
      </div>
    </section>
  <?php
  }
?>

<section class="lastest">
  <div class="screen">
    <div class="screen-title main-reveal">Latest Posts</div>
    <ul>
      <?php $post_i = 1; ?>
      <?php
        // latest インスタンスを作成
        $latest = new WP_Query(
          array(
            'post_type' => 'post',  // 投稿タイプ
            'posts_per_page' => 9, // 1ページあたりの表示数
            'ignore_sticky_posts' => 1, // 固定投稿を除外
            'orderby' => 'date', // 日付順
            'order' => 'DESC' // 降順
          )
        );

        // ループ開始
        $currentCover = 1;
        if ($latest->have_posts()) :
        while ($latest->have_posts()) : $latest->the_post();

        // カテゴリー
        $categories = get_the_category(); 
      ?>
        <li
          <?php
            if ($post_i == 1) {
              echo 'class="full-width main-reveal"';
            } else if ($post_i == 2 || $post_i == 3) {
              echo 'class="half-width main-reveal"';
            } else {
              echo 'class="quarter-width main-reveal"';
            }
          ?>
        >
        <a href="<?=the_permalink()?>">
          <div class="cover">
            <div class="pic-box">
              <?php
                $pic = the_cover_url('large', $currentCover);
                echo lazy_load_pic($pic); $currentCover++;
              ?>
            </div>
          </div>
          <div class="info">
            <div class="top">
              <div class="cate" <?=theme_color_sync(true) ? 'style="color:'.theme_color_sync(true).'"' : ''?>>
                <?php
                  if ($categories) {
                    foreach ($categories as $category) {
                      echo '<span class="url" data-href="'.esc_url(get_category_link($category->term_id)).'">
                      <svg class="icon" aria-hidden="true"><use xlink:href="#icon-folder-full"></use></svg> '.esc_html($category->name).'</span>';
                    }
                  }
                ?>
              </div>
              <div class="title"><?=get_the_title() ? the_title() : 'タイトルなし'?></div>
            </div>
            <div class="datetime"><?=get_the_date()?></div>
          </div>
        </a>
      </li>
      <?php $post_i++; ?>
      <?php endwhile; else : ?>
        <style>
          main .lastest ul {
            display: block;
          }
        </style>
        <?=notice('warning', '', "There are currently <b>no latest posts</b> available.")?>
      <?php endif; wp_reset_postdata();?>
    </ul>
  </div>
</section>

<section class="popular">
  <div class="screen">
    <div class="screen-title main-reveal">Popular Posts</div>
    <ul>
      <?php
        // popular インスタンスを作成
        $popular = new WP_Query(
          array(
            'post_type' => 'post', // 投稿タイプ
            'posts_per_page' => 8, // 1ページあたりの表示数
            'ignore_sticky_posts' => 1, // 固定投稿を除外
            'meta_key' => 'post_heat', // 閲覧数カスタムフィールドでソート
            'orderby' => 'meta_value_num', // 数値順
            'order' => 'DESC' // 降順
          )
        );
        
        // ループ
        if ($popular->have_posts()) :
        while ($popular->have_posts()) : $popular->the_post();

        // カテゴリーを取得
        $categories = get_the_category();
      ?>
        <li class="main-reveal">
          <a href="<?=the_permalink()?>">
            <div class="cover">
              <div class="box">
                <?php
                  $pic = the_cover_url('medium', $currentCover);
                  echo lazy_load_pic($pic); $currentCover++;
                ?>
              </div>
            </div>
            <div class="info">
              <div class="top">
                <div class="cate" <?=theme_color_sync(true) ? 'style="color:'.theme_color_sync(true).'"' : ''?>>
                  <?php
                    if ($categories) {
                      foreach ($categories as $category) {
                        echo '<span class="url" data-href="'.esc_url(get_category_link($category->term_id)).'">
                        <svg class="icon" aria-hidden="true"><use xlink:href="#icon-folder-full"></use></svg> '.esc_html($category->name).'</span>';
                      }
                    }
                  ?>
                </div>
                <div class="title"><?=get_the_title() ? the_title() : 'タイトルなし'?></div>
              </div>
              <div class="date"><?=get_the_date()?><span class="drop">·</span><?=get_post_heat()?> Heat</div>
            </div>
          </a>
        </li>
      <?php endwhile; else : ?>
        <style>
          main .popular ul {
            display: block;
          }
        </style>
        <?=notice('warning', '', "There are currently <b>no popular posts</b> available.")?>
      <?php endif; wp_reset_postdata();?>
    </ul>
  </div>
</section>

<?=get_footer()?>
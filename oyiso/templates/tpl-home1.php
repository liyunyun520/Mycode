<?=get_header()?>

<?php 
  // テーマ設定のホーム背景画像を取得
  global $def_img;
  $currentCover = 1;

  // ホームタブバーの項目を取得
  $home1_bloger = get_option('oyiso_home1_bloger');
  $home1_post = get_option('oyiso_home1_post');
  $home1_post_num = get_option('oyiso_home1_post_num') ? get_option('oyiso_home1_post_num') : 6;
  $home1_moment = get_option('oyiso_home1_moment');
  $home1_moment_num = get_option('oyiso_home1_moment_num') ? get_option('oyiso_home1_moment_num') : 6;
  $home1_comment = get_option('oyiso_home1_comment');
  $home1_comment_num = get_option('oyiso_home1_comment_num') ? get_option('oyiso_home1_comment_num') : 6;
  $home1_bookmark = get_option('oyiso_home1_bookmark');
  $home1_bookmark_num = get_option('oyiso_home1_bookmark_num') ? get_option('oyiso_home1_bookmark_num') : 6;
  $home1_gallery = get_option('oyiso_home1_gallery');
  $home1_gallery_num = get_option('oyiso_home1_gallery_num') ? get_option('oyiso_home1_gallery_num') : 6;
  $home1_notice = get_option('oyiso_notice');
  $home1_newest = true;
  if (!$home1_bloger && !$home1_post && !$home1_moment && !$home1_comment && !$home1_bookmark && !$home1_gallery && !$home1_notice) {
    $home1_newest = false;
  }
?>

<section class="home1-bannar">
  <div class="screen">
    <div class="preloader">
      <div class="loader"></div>
    </div>
    <div class="imgbox">
      <img src="<?=home1_random_img()?home1_random_img():$def_img?>" alt="">
      <div class="nav-news">
        <div class="text">
          <?php
            $slogan = stripslashes(get_option('oyiso_home1_slogan'));
            $slogan_sub = stripslashes(get_option('oyiso_home1_slogan_sub'));
          ?>
          <h1><?=$slogan?$slogan:get_bloginfo('name')?></h1>
          <p><?=$slogan_sub?$slogan_sub:get_bloginfo('description')?></p>
        </div>
      </div>
    </div>
    <?php if (!get_option('oyiso_home1_waves')) { ?>
      <div class="waves-box">
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
          <defs>
            <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
          </defs>
          <g class="parallax">
            <use xlink:href="#gentle-wave" x="48" y="0"/>
            <use xlink:href="#gentle-wave" x="48" y="3"/>
            <use xlink:href="#gentle-wave" x="48" y="5"/>
            <use xlink:href="#gentle-wave" x="48" y="7"/>
          </g>
        </svg>
      </div>
    <?php } ?>
  </div>
</section>

<?php if ($home1_newest) { ?>
  <section class="home1-newest">
    <div class="screen">
      <div class="screen-title main-reveal">Feature</div>
      <div class="warp">
        <div class="tab main-reveal">
          <ul>
            <?=$home1_bloger ? '<li>ブロガー</li>' : ''?>
            <?=$home1_post ? '<li>固定</li>' : ''?>
            <?=$home1_moment ? '<li>一瞬</li>' : ''?>
            <?=$home1_comment ? '<li>コメント</li>' : ''?>
            <?=$home1_bookmark ? '<li>友人帳</li>' : ''?>
            <?=$home1_gallery ? '<li>ギャラリー</li>' : ''?>
            <?=$home1_notice ? '<li>お知らせ</li>' : ''?>
            <div class="slider"></div>
          </ul>
        </div>
        <div class="main-reveal">
          <ul class="content">
            <?=$home1_bloger ? '<li class="bloger">'.get_home_newest('bloger').'</li>' : ''?>
            <?=$home1_post ? '<li class="post">'.get_home_newest('post', $home1_post_num).'</li>' : ''?>
            <?=$home1_moment ? '<li class="moment">'.get_home_newest('moment', $home1_moment_num).'</li>' : ''?>
            <?=$home1_comment ? '<li class="comment">'.get_home_newest('comment', $home1_comment_num).'</li>' : ''?>
            <?=$home1_bookmark ? '<li class="bookmark">'.get_home_newest('bookmark', $home1_bookmark_num).'</li>' : ''?>
            <?=$home1_gallery ? '<li class="gallery">'.get_home_newest('gallery', $home1_gallery_num).'</li>' : ''?>
            <?=$home1_notice ? '<li class="notice">'.get_home_newest('notice').'</li>' : ''?>
          </ul>
        </div>
      </div>
    </div>
  </section>
<?php } ?>

<section class="lastest">
  <div class="screen">
    <div class="screen-title main-reveal">Newest</div>
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

<section class="popular home1-popular">
  <div class="screen">
    <div class="screen-title main-reveal">Popular</div>
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
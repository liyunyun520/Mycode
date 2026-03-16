<?=get_header()?>

<?=theme_color_sync()?>

<style>
  .container {
    background-color: var(--bgc-single);
  }
</style>

<section class="post-info narrow">
  <div class="screen">
    <div class="banner-reveal">
      <?php
        $categories = get_the_terms(get_the_ID(), 'gallery_category');
        if ($categories) {
          echo '<div class="category">';
          foreach ($categories as $category) {
            echo '<a href="'.esc_url(get_category_link($category->term_id)).'">
            <svg class="icon" aria-hidden="true"><use xlink:href="#icon-folder-full"></use></svg> '.esc_html($category->name).'</a>';
          }
          echo '</div>';
        }
      ?>
      <div class="msg">
        <span><?=get_the_date()?></span>
        <span class="drop">·</span>
        <span><?=set_post_views();get_post_views();set_post_heat();?> Views</span>
      </div>
    </div>
    <div class="title-box main-reveal">
      <div class="title"><?=get_the_title() ? the_title() : 'タイトルなし'?></div>
      <?php
        if (has_excerpt()) {
          echo '<div class="excerpt">'.get_the_excerpt().'</div>';
        }
      ?>
    </div>
  </div>
</section>

<section class="post-cover">
  <?php
    // カバー画像を取得
    // $post_cover_url = get_cover_url(get_the_ID(), 'full', get_the_content());
    if (has_post_thumbnail()) {
      ?>
        <div class="screen">
          <div class="cover">
          <?php
            $pic = get_the_post_thumbnail_url(get_the_ID(), 'full');
            echo lazy_load_pic($pic, 'img-reveal');
          ?>
          </div>
        </div>
      <?php
    } else {
      ?>
        <div class="screen main-reveal hr"><hr></div>
      <?php
    }
  ?>
</section>

<section class="single post-content gallery narrow">
  <div class="screen">
    <?php
      // 投稿の最終更新日時を取得
      $modified_date = get_the_modified_date('Y-m-d H:i:s', get_the_ID());
      if ($modified_date) {
        $modified_timestamp = strtotime($modified_date);
        $time_diff = time() - $modified_timestamp;
        if ($time_diff > 365 * 24 * 60 * 60) {
          // 何日前か
          $time_diff_day = intval($time_diff / 60 / 60 / 24);
          if ($time_diff_day > 1) {
            $time_diff_day = $time_diff_day.' days';
          } else {
            $time_diff_day = $time_diff_day.' day';
          }
          $time_diff_day = "This gallery was updated <b>$time_diff_day ago</b> and some of the ideas may be out of date.";
          echo notice('warning', '', $time_diff_day);
        }
      }
    ?>
    <div class="content-and-toc">
      <div class="content content-gallery main-reveal"><?=oyiso_md(get_the_content())?></div>
      <div class="toc-container main-reveal"></div>
    </div>
  </div>
</section>

<section class="post-cc narrow">
  <div class="screen">
  <?php
    $tags = get_the_tags();
    // var_dump($tags);
    if ($tags) {
      echo '<div class="tag-list main-reveal">';
      foreach ($tags as $tag) { 
        ?>
        <a href="<?=get_tag_link($tag->term_id)?>"><i class="iconfont icon-tag-fill"></i><?=$tag->name?></a>
        <?php
      }
      echo '</div>';
    }
  ?>
  <div class="post-protocol main-reveal">
    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-cc-logo"></use></svg>
    <div class="post-head">
      <p><?=get_the_title() ? the_title() : 'タイトルなし'?></p>
      <?php
        $current_post_link = get_permalink();
        $relative_link = str_replace(array('http://', 'https://'), '', $current_post_link);
      ?>
      <p><a href="<?=$current_post_link?>"><?=$relative_link?></a></p>
    </div>
    <div class="post-cc">
      <?php
        // 作者の公開ニックネームを取得
        $author_name = get_the_author_meta('display_name', $post->post_author);

        // 投稿公開日を取得
        $post_publish_year = get_the_date('Y');
        $post_publish_month = get_the_date('n');
        $post_publish_day = get_the_date('j');
        $month_names = array(
          'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
          'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        );
        (int)$post_publish_month = (int)$post_publish_month - 1;
        $post_publish_date = $month_names[$post_publish_month] . ' ' . $post_publish_day . ', ' . $post_publish_year;

        // 投稿更新日を取得
        $post_modified_year = get_the_modified_date('Y');
        $post_modified_month = get_the_modified_date('n');
        $post_modified_day = get_the_modified_date('j');
        $month_names = array(
          'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
          'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        );
        (int)$post_modified_month = (int)$post_modified_month - 1;
        $post_modified_date = $month_names[$post_modified_month] . ' ' . $post_modified_day . ', ' . $post_modified_year;
      ?>
      <li>
        <p>作者</p>
        <p class="name"><?=$author_name?></p>
      </li>
      <li>
        <p>公開日</p>
        <p><?=$post_publish_date?></p>
      </li>
      <li>
        <p>更新日</p>
        <p><?=$post_modified_date?></p>
      </li>
      <li>
        <p>ライセンス</p>
        <p class="cc">
          <a href="https://creativecommons.org" title="Creative Commons" target="_blank"><i class="iconfont icon-cc-logo"></i></a>
          <a href="https://creativecommons.org/licenses/by/4.0/" title="Attribution" target="_blank"><i class="iconfont icon-cc-by"></i></a>
          <a href="https://creativecommons.org/licenses/by-nc/4.0/" title="NonCommercial" target="_blank"><i class="iconfont icon-cc-nc"></i></a>
          <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" title="ShareAlike" target="_blank"><i class="iconfont icon-cc-sa"></i></a>
        </p>
      </li>
    </div>
  </div>
  </div>
</section>

<section class="post-pre-next narrow">
  <?php 
    $currentCover = time();
    $prev_post = get_previous_post();
    $next_post = get_next_post();

    if (!empty($prev_post) || !empty($next_post)) {
      ?>
      <div class="screen">
          <?php
            echo '<div class="box main-reveal">';
            if (!empty($prev_post)) {
              $prev_id = $prev_post -> ID;
              $prev_title = $prev_post -> post_title;
              $prev_content = get_post_field('post_content', $prev_id);
              // echo $prev_id;
              ?>
              <a class="pre" href="<?=get_permalink($prev_id)?>">
                <div class="cover">
                  <?php
                    $pic = the_cover_url('large', $currentCover, $prev_id, $prev_content);
                    echo lazy_load_pic($pic); $currentCover++;
                  ?>
                </div>
                <div class="text">
                  <span>前のギャラリー</span>
                  <p><?=$prev_title?></p>
                </div>
              </a>
              <?php
            } else {
              echo '<style>
                main .post-pre-next .box a { width: 100%; height:100%; }
                @media screen and (max-width: 550px) {
                  main .post-pre-next .box {
                    height: 110px;
                  }
                }
              </style>';
            }

            if (!empty($next_post)) {
              $next_id = $next_post -> ID;
              $next_title = $next_post -> post_title;
              $next_content = get_post_field('post_content', $next_id);
              // echo $next_id;
              ?>
              <a class="next" href="<?=get_permalink($next_id)?>">
                <div class="cover">
                  <?php
                    $pic = the_cover_url('large', $currentCover, $next_id, $next_content);
                    echo lazy_load_pic($pic);
                  ?>
                </div>
                <div class="text">
                  <span>次のギャラリー</span>
                  <p><?=$next_title?></p>
                </div>
              </a>
              <?php
            } else {
              echo '<style>
                main .post-pre-next .box a { width: 100%; height:100%; }
                @media screen and (max-width: 550px) {
                  main .post-pre-next .box {
                    height: 110px;
                  }
                }
              </style>';
            }
            echo '</div>';
          ?>
        </div>
      <?php
    }
  ?>
</section>

<?php
  $post_id = get_the_ID();
  if (get_option('oyiso_comments_post')) {
    $post = get_post($post_id);
    if ($post -> comment_status == 'open') {
      $comments_status = true;
    } else {
      $comments_status = false;
    }

    if ($comments_status) {
      echo '<section class="post-comments">';
      require_once 'comments.php';
      echo '</section>';
    }
  }
?>

<section class="post-tool narrow">
  <div class="screen">
    <div class="case">
      <div class="box">
        <ul>
          <li class="likes">
           <a data-id="<?=$post_id?>">
              <?php
                $likes = get_post_meta($post_id, 'post_likes', true);
                if ($likes == '') {
                  $likes = 0;
                }
              ?>
              <svg class="icon" aria-hidden="true"><use xlink:href="#icon-heart-fill"></use></svg>
              <p>いいね <span><?=$likes?></span></p>
           </a>
          </li>

          <?php
            if (get_option('oyiso_comments_post')) {
              if ($comments_status) {
                $comments_count = get_comments_number();
                ?>
                <li class="comments" data-id="<?=$post_id?>">
                  <a>
                    <svg class="icon" aria-hidden="true"><use xlink:href="#icon-chat-smile-fill"></use></svg>
                    <p>コメント <span><?=$comments_count ? $comments_count : '0'?></span></p>
                  </a>
                </li>
                <?php
              }
            }
          ?>
          
          <li class="backtop">
            <a>
              <svg class="icon" aria-hidden="true"><use xlink:href="#icon-rocket-2-fill"></use></svg>
              <p>TOP</p>
            </a>
          </li>

          <li class="share">
            <a>
              <svg class="icon" aria-hidden="true"><use xlink:href="#icon-share-forward-fill"></use></svg>
              <p>共有</p>
            </a>
          </li>

          <?php
            if (is_user_logged_in() && current_user_can('administrator')) {
              ?>
              <li class="edit">
                <a href="<?=get_edit_post_link(get_the_ID())?>" target="_blank">
                  <svg class="icon" aria-hidden="true"><use xlink:href="#icon-edit-fill"></use></svg>
                  <p>編集</p>
                </a>
              </li>
              <?php
            }
          ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<?=get_footer()?>
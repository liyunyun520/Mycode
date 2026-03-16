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
      <div class="msg">
        <span><?=get_the_date()?></span>
        <?php
          $reading_time = get_post_meta(get_the_ID(), 'reading_time', true);
          echo $reading_time ? '<span class="drop">·</span><span class="read-time">'.$reading_time.'</span>' : '';
        ?>
      </div>
    </div>
    <div class="title-box main-reveal">
      <div class="title"><?=get_the_title() ? the_title() : 'タイトルなし'?></div>
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

<section class="single post-content narrow">
  <div class="screen">
    <?php
      // ページの最終更新日時を取得
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
          $time_diff_day = "This page was updated <b>$time_diff_day ago</b> and some of the ideas may be out of date.";
          echo notice('warning', '', $time_diff_day);
        }
      }
    ?>
    <div class="content-and-toc">
      <div class="content main-reveal"><?=the_content()?></div>
      <div class="toc-container main-reveal"></div>
    </div>
  </div>
</section>

<?php
  $post_id = get_the_ID();
  if (get_option('oyiso_comments_page')) {
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
        <?php
          $post_id = get_the_ID();
        ?>
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
            if (get_option('oyiso_comments_page')) {
              if ($comments_status) {
                $comments_count = get_comments_number();
                ?>
                <li class="comments">
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
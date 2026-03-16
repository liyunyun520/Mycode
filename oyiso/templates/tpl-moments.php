<?php
/**
 * Template Name: 瞬間
 */
?>

<?=get_header()?>

<style>
  .container {
    background-color: var(--bgc-single);
  }
</style>

<section class="tpl-moment narrow">
  <div class="screen">
    <div class="top">
      <div class="banner banner-reveal">
        <?php
          $moment_cover = get_option('oyiso_moment_cover');
          if (!$moment_cover) {
            $moment_cover = file_uri().'/assets/images/cover-post.jpg';
          }
          $pic = $moment_cover;
          echo lazy_load_pic($pic);
        ?>
        <?php
        
      ?>
      </div>
      <div class="author-info narrow-row main-reveal">
        <div class="msg">
          <div class="avatar">
            <img src="<?=get_avatar_url(1)?>" alt="">
          </div>
          <div class="name"><?=get_userdata(1) -> display_name?></div>
        </div>
        <?php
          $desc = get_option('oyiso_moment_desc');
          $user_desc = get_userdata(1) -> description;
          $moment_desc = $desc ? $desc : ($user_desc ? $user_desc : '');
          if ($moment_desc) {
            echo "<div class='desc'>{$moment_desc}</div>";
          }
        ?>
      </div>
      <?php
        if (get_option('oyiso_moment_prompt')) {
          $moment_prompt_title_option = get_option('oyiso_moment_prompt_title');
          $moment_prompt_excerpt_option = get_option('oyiso_moment_prompt_excerpt');
          
          $moment_prompt_title = $moment_prompt_title_option ? $moment_prompt_title_option : '瞬間';
          $moment_prompt_excerpt = $moment_prompt_excerpt_option ? $moment_prompt_excerpt_option : '短い時間であり、短い美しさであり、短い記憶です。';
          echo '
            <div class="aword narrow-row main-reveal">
              <h2><span>'.$moment_prompt_title.'</span></h2>
              <p>'.$moment_prompt_excerpt.'</p>
            </div>
          ';
        }
      ?>
      
    </div>
    
    <div class="content narrow-row">
      <ul>
        <?php
          // 瞬間コメント総スイッチ
          $comments_moment = get_option('oyiso_comments_moment');

          // moment インスタンスを作成
          $moment = new WP_Query(
            array(
              'post_type' => 'moment',  // 投稿タイプ
              // 'posts_per_page' => 10, // 1ページあたりの表示数
              'paged' => get_query_var('paged', 1), // 現在のページ番号
              'orderby' => 'date', // 日付順
              'order' => 'DESC' // 降順
            )
          );

          // ループ開始
          if ($moment->have_posts()) :
          while ($moment->have_posts()) : $moment->the_post();
          if (!get_the_content()) {
            continue;
          }
          $post_id = get_the_ID();
          $user_id = get_the_author_meta('ID');
          $user_info = get_userdata($user_id);
          $avatar = get_avatar_url($user_id);
          $name = $user_info -> display_name;
          $location_enabled = get_post_meta($post_id, 'oyiso_location_enabled', true);
          $location = get_post_meta($post_id, 'oyiso_location', true);
          $date = get_the_date('Y-m-d H:i:s');
          $post = get_post($post_id);
          $comments_moment_open = $post->comment_status == 'open' ? true : false;
        ?>
          <li id="moment_<?=$post_id?>" class="item main-reveal">
            <div class="user-avatar">
              <img src="<?=$avatar?>" alt="">
            </div>
            <div class="right">
              <h2><span><?=$name?></span></h2>
              <div class="moment-text" id="gallery-<?=$post_id?>"><?=the_content()?></div>
              <?php
                if (get_option('oyiso_comments_ip') && $location_enabled && $location) {
                  echo '<div class="tag location">
                    <svg class="icon" aria-hidden="true">
                      <use xlink:href="#icon-map-pin-fill"></use>
                    </svg>
                    <span>'.$location.'</span>
                  </div>';
                }
              ?>
              <div class="tag date tool">
                <span><?=get_days_ago($date)?></span>
                <div class="func">
                  <div class="more">
                    <span>
                      <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-more-fill"></use>
                      </svg>
                    </span>
                  </div>
                  <div class="box">
                    <div class="like" data-id="<?=$post_id?>">
                      <?php
                        $likes = get_post_meta($post_id, 'post_likes', true);
                        if ($likes == '') {
                          $likes = 0;
                        }
                      ?>
                      <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-heart-fill"></use>
                      </svg><p>いいね <span><?=$likes?></span></p>
                    </div>
                    <?php
                      if ($comments_moment) {
                        if ($comments_moment_open) {
                          $comments_count = get_comments_number() ? get_comments_number() : '0';
                        $moment_comment = 'コメント <span>'.$comments_count.'</span>';
                        $moment_comment_css = true;
                        } else {
                          $moment_comment = 'コメントは閉じられています';
                          $moment_comment_css = false;
                        }
                        ?>
                        <span class="line"></span>
                        <div class="comment <?=$moment_comment_css ? '' : 'close'?>" data-id="<?=$post_id?>">
                          <svg class="icon" aria-hidden="true">
                            <use xlink:href="#icon-chat-smile-fill"></use>
                          </svg><p><?=$moment_comment?></p>
                        </div>
                        <?php
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </li>
        <?php endwhile; else : ?>
          <?=notice('warning', '', "There are currently <b>no moment</b> available.")?>
        <?php endif; wp_reset_postdata();?>
      </ul>
      <?=the_pagination($moment)?>
    </div>
  </div>
</section>

<?php
  if (get_option('oyiso_comments_moment')) {
    echo '<section class="post-comments moments">';
    require_once get_template_directory().'/comments.php';
    echo '</section>';
  }
?>
<?=get_footer()?>
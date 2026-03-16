<?php
/**
 * Template Name: このサイトについて
 */
?>

<?=get_header()?>

<?=theme_color_sync()?>

<section class="tpl-about tpl-friend post-content">
  <div class="screen">
    <div class="screen-title main-reveal">About</div>
    <div class="content main-reveal">
      <?php
        if (!empty(get_the_content())) 
          the_content();
        else 
          echo 'この人は怠け者で、何も書いていません！';
      ?>
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
      require_once get_template_directory().'/comments.php';
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
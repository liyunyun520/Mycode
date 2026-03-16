<?php
/**
 * Template Name: リンク集
 */
?>

<?=get_header()?>

<?=theme_color_sync()?>

<section class="tpl-friend post-content">
  <div class="screen">
    <div class="screen-title main-reveal">My Friends</div>
    <?php
      if (!empty(get_the_content())) {
        echo '<div class="content main-reveal">';
        the_content();
        echo '</div>';
      }

      $bookmark_categories = get_terms(array(
        'taxonomy' => 'link_category',
        'hide_empty' => false,
        'meta_query' => array(
          array(
            'key' => 'link_category_order',
            'value' => 0,
            'compare' => '>',
          )
        ),
        'orderby' => 'meta_value_num',
        'meta_key' => 'link_category_order',
      ));
      if (get_option('oyiso_friend_sup')) {
        $site_name = get_bloginfo('name');
        $site_url = get_bloginfo('url');
        $site_desc = get_bloginfo('description');
        $site_icon = $site_url.'/favicon.png';
      ?>
      <div class="link-sup">
        <h2 class='main-reveal'><span>セルフ登録リンク</span></h2>
        <div class="apply main-reveal">クリックして申請</div>
        <div class="link-form">
          <form class="linkForm">
            <h3><span>リンク申請フォーム</span></h3>
            <div class='label'>
              <span>リンク名</span>
              <input type="text" name="name" placeholder="例：<?=$site_name?>（必須）" required autocomplete="off">
            </div>

            <div class='label'>
              <span>リンクURL</span>
              <input type="text" name="url" placeholder="例：<?=$site_url?>（必須）" required autocomplete="off">
            </div>

            <div class='label'>
              <span>リンク説明</span>
              <input type="text" name="description" placeholder="例：<?=$site_desc?>" autocomplete="off">
            </div>

            <div class='label'>
              <span>リンクアイコン</span>
              <input type="text" name="image" placeholder="例：<?=$site_icon?>" autocomplete="off">
            </div>

            <div class='label category'>
              <span>リンクカテゴリー</span>
              <div class="select">
                <input type="text" <?=$bookmark_categories ? 'placeholder="カテゴリーを選択" data-cate="have"' : 'placeholder="カテゴリーなし" data-cate="none"'?> readonly autocomplete="off">  
                <?php
                  if ($bookmark_categories) {
                    echo '<div class="ul-box"><ul>';
                    foreach ($bookmark_categories as $category) {
                      echo "<li data-value='$category->term_id'>$category->name</li>";
                    }
                    echo '</ul></div>';
                  }
                ?>
              </div>
            </div>

            <div class='label'>
              <span>管理者メール</span>
              <input type="email" name="email" placeholder="審査通知の受け取り用（必須）" required autocomplete="off">
            </div>

            <div class='label'>
              <span><i class="num1">1</i>+<i class="num2">1</i>=?</span>
              <input type="number" name="verify" placeholder="計算してください" required autocomplete="off">
            </div>
            
            <div class="submit-btn">
              <button class="submit" type="submit">
                <svg class="icon" aria-hidden="true">
                  <use xlink:href="#icon-send-tg-fill"></use>
                </svg><span>リンクを送信</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    <?php } ?>

    <div class="bookmark">
      <?php
        function the_links($bookmark_categories, $disconnection=false) {
          foreach ($bookmark_categories as $category) {
            $bookmarks = get_bookmarks(array(
              'category' => $category->term_id,
              // 'hide_invisible' => false,
              'orderby' => 'id'
            ));
            $bookmark_count = count($bookmarks);
            if ($bookmark_count > 0) {
              echo "<h2 class='main-reveal'><span>$category->name ($bookmark_count)</span>";
              if ($category->description) {
                echo "<p>$category->description</p>";
              }
              echo '</h2><ul class="main-reveal">';
              foreach ($bookmarks as $bookmark) {
                if ($disconnection) {
                  $on_or_off = 'off';
                } else {
                  $on_or_off = $bookmark->link_rating === '0' ? 'on' : 'off';
                }
              ?>
                <li class="text-reveal">
                  <a class="<?=$on_or_off?>" href="<?=$bookmark->link_url?>" target="<?=$bookmark->link_target?>">
                    <div class="icon">
                      <?php
                        if ($bookmark->link_image)
                          $image = $bookmark->link_image;
                        else
                          $image = file_uri().'/assets/images/iconshort.png';
                      ?>
                      <img src="<?=$image?>" alt="">
                    </div>
                    <div class="info">
                      <h3><?=$bookmark->link_name?></h3>
                      <?php
                        if ($bookmark->link_description)
                          $desc = $bookmark->link_description;
                        else
                          $desc = 'この人は怠け者で、何も書いていません';
                      ?>
                      <p title="<?=$desc?>"><?=$desc?></p>
                    </div>
                    <div class="profile">
                      <div class="imgbox">
                        <img src="<?=$image?>" alt="">
                      </div>
                    </div>
                  </a>
                </li>
              <?php
              }
              echo '</ul>';
            }
          }
        }
        $link_categories = get_terms(array(
          'taxonomy' => 'link_category',
          'meta_query' => array(array(
            'key' => 'link_category_order',
            'value' => 0,
            'compare' => '>=',
          ))
        ));
        $bookmarks = get_bookmarks();
        // var_dump($bookmarks);
        // var_dump($link_categories);
        $visible_count = 0;
        foreach ($bookmarks as $bookmark) {
          if ($bookmark->link_visible == 'Y') {
            $visible_count++;
          }
        }

        if ($link_categories && $visible_count > 0) {
          $disconnections = get_terms(array(
            'taxonomy' => 'link_category',
            'meta_query' => array(array(
              'key' => 'link_category_order',
              'value' => 0,
              'compare' => '=',
            ))
          ));
          the_links($bookmark_categories);
          the_links($disconnections, true);
        } else {
          echo notice('info', '', "ブロガーはまだ友達がいません。早く申請しましょう！");
        }
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
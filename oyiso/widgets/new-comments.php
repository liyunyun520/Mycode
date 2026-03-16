<h2>
  <span>
    <svg class="icon" aria-hidden="true">
      <use xlink:href="#icon-chat-smile-square-fill"></use>
    </svg>最新コメント
  </span>
</h2>

<div class="widget-box new-comments">
  <?php
    $recent_comments = get_comments(array(
      'number' => 10, // 10件のコメントを取得
      'status' => 'approve', // 承認済みコメントのみ取得
      'orderby' => 'comment_date', // コメント日付順
      'order' => 'DESC' // 降順
    ));
    // var_dump($recent_comments);
    if ($recent_comments){
      echo '<ul>';
      foreach ($recent_comments as $comment) {
        // var_dump($comment);
        if ($comment->user_id > 0) {
          $name = get_userdata($comment->user_id)->display_name;
          $avatar = get_avatar($comment->user_id);
        } else {
          $name = $comment->comment_author;
          $avatar = get_avatar($comment->comment_author_email);
        }

        $comment_parent_id = $comment->comment_parent;
        $comment_parent_author = '';
        if ($comment_parent_id) {
          $comment_parent = get_comment($comment_parent_id);
          $comment_parent_user_id = $comment_parent->user_id;
          if ($comment_parent_user_id > 0) {
            $comment_parent_user_info = get_userdata($comment_parent_user_id);
            $comment_parent_author = $comment_parent_user_info -> display_name;
          } else {
            $comment_parent_author = $comment_parent -> comment_author;
          }
        }

        $post_id = $comment->comment_post_ID;
        if (get_post_type($post_id) != 'moment') {
          $post_url = 'href="'.get_the_permalink($comment->comment_post_ID).'#comment_reply='.$comment->comment_ID.'"';
        } else {
          $post_url = '';
        }
        ?>
        <li>
          <a <?=$post_url?>>
            <div class="avatar">
              <?=$avatar?>
            </div>
            <div class="info">
              <div class="name"><?=$name?></div>
              <?php
                $comment_content = $comment->comment_content;
                $comment_content = str_replace('<a', '<p', $comment_content);
                $comment_content = str_replace('</a>', '</p>', $comment_content);
                $comment_content_title = $comment_parent_author ? "返信 {$comment_parent_author}：{$comment_content}" : $comment_content;
                $comment_reply_author = $comment_parent_author ? "<span>@{$comment_parent_author}：</span>" : '';
                echo "
                  <div class='text' title='{$comment_content_title}'>
                    {$comment_reply_author}{$comment_content}
                  </div>
                ";
              ?>
            </div>
          </a>
        <?php
      }
      echo '</ul>';
    } else {
      echo '<p>最新コメントはありません</p>';
    }
  ?>
</div>
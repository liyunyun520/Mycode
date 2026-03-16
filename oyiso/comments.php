<div class="comment-container" id="<?=get_the_ID()?>">
  <div class="comment-top">
    <h3>ディスカッションに参加</h3>
    <div class="close">
      <svg class="icon" aria-hidden="true">
        <use xlink:href="#icon-close-fill"></use>
      </svg>
    </div>
    <div class="close-tip">
      <svg class="icon" aria-hidden="true">
        <use xlink:href="#icon-arrow-down"></use>
      </svg><span>左にスワイプして閉じる</span>
    </div>
  </div>
  <div class="comment-body">
    <div class="next-comments">
      <a>
        <svg class="icon" aria-hidden="true">
          <use xlink:href="#icon-loading"></use>
        </svg><span>もっと読み込む</span>
      </a>
    </div>
    <ul></ul>
  </div>
  <div class="comment-response">
    <div class="emoji-box">
      <div class="emoji-content">
        <div class="tab">
          <ul>
            <li class="active">😀</li>
            <li>顔文字</li>
          </ul>
        </div>
        <div class="list">
          <ul>
            <li class="active"></li>
            <li></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="go-bottom">
      <svg class="icon" aria-hidden="true">
        <use xlink:href="#icon-arrow-down"></use>
      </svg><span>下部に戻る</span>
    </div>
    <?php
      $post_id = get_the_ID();
      $logged = is_user_logged_in();
      if ($logged) {
        $user_id = get_current_user_id(); // 現在のユーザーIDを取得
        $user_avatar = get_avatar_url($user_id); // 現在のユーザーアバターを取得
        $user_info = get_userdata($user_id); // 現在のユーザー情報を取得
        $user_name = $user_info -> display_name; // 現在のユーザーニックネームを取得
        $user_email = $user_info -> user_email; // 現在のユーザーメールを取得
        $user_url = $user_info -> user_url; // 現在のユーザーURLを取得
      } else {
        if(isset($_COOKIE['visitor_email'])) {
          $cookie_value = $_COOKIE["visitor_email"];
          $user_avatar = get_avatar_url($cookie_value);
        } else {
          $user_avatar = get_avatar_url(0);
        }
          // デフォルトアバターを取得
        $user_name = 'クリックしてユーザー情報を入力'; // デフォルトニックネーム
        $user_email = ''; // デフォルトメール
        $user_url = ''; // デフォルトURL
      }
      
      if (get_option('oyiso_comments_login') && !$logged) {
        $login_to_comment = true;
        $user_name = 'ログイン後にコメントしてください';
      } else {
        $login_to_comment = false;
      }
    ?>
    <form class="commentForm">
      <?php
        if (!$logged && !$login_to_comment) {
          ?>
          <div class="visitor">
            <div class="basic-info">
              <input type="text" name="author" value="" placeholder="ニックネーム*" maxlength="245" require>
              <input type="text" name="email" value="" placeholder="メール*" maxlength="100" require>
              <input type="text" name="url" value="" placeholder="ウェブサイト (http:// または https://)" maxlength="200">

              <label class="comment-cookies">
                <input type="checkbox">
                <svg viewBox="0 0 64 64" height="1em" width="1em">
                  <path d="M 0 16 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 16 L 32 48 L 64 16 V 8 A 8 8 90 0 0 56 0 H 8 A 8 8 90 0 0 0 8 V 56 A 8 8 90 0 0 8 64 H 56 A 8 8 90 0 0 64 56 V 16" pathLength="575.0541381835938" class="path"></path>
                </svg><span>チェックして情報を保存</span>
              </label>
            </div>
          </div>
          <?php
        }
      ?>
      <div class="textarea">
        <textarea name="comment" id="comment" placeholder="コメントを入力..." maxlength="500"></textarea>
      </div>
      <div class="form-tool">
        <div class="reply">
          <button type="button" class="cancel"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-danger-full"></use></svg><span>キャンセル</span></button>
          <button type="button" class="emoji"><svg class="icon" aria-hidden="true"><use xlink:href="#icon-user-smile-fill"></use></svg><span>Emoji</span></button>
          <button type="submit" class="send" disabled><svg class="icon" aria-hidden="true"><use xlink:href="#icon-send-tg-fill"></use></svg><span>送信</span></button>
        </div>
        <?php
          if ($logged) {
            ?>
              <div class="user-info">
                <div class="avatar">
                  <img src="<?=$user_avatar?>" alt="">
                </div>
                <div class="name"><?=$user_name?></div>
              </div>
            <?php
          } else {
            ?>
              <a <?=$login_to_comment ? 'href="'.wp_login_url(home_url(add_query_arg(array()))).'" target="_blank"' : ''?> class="user-info no-login">
                <div class="avatar">
                  <img src="<?=$user_avatar?>" alt="">
                </div>
                <div class="name"><?=$user_name?></div>
              </a>
            <?php
          }
        ?>
      </div>
      <div class="comment_info_hidden">
        <input type="hidden" name="comment_post_ID" value="<?=$post_id?>">
        <input type="hidden" name="comment_parent" value="0">
        <?php
          if($logged) {
            echo '<input type="hidden" name="author" value="'.$user_name.'">';
            echo '<input type="hidden" name="email" value="'.$user_email.'">';
            echo '<input type="hidden" name="url" value="'.$user_url.'">';
          }
        ?>
      </div>
    </form>
  </div>
</div>
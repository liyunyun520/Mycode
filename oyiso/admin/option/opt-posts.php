<div class="item">
  <div class="field">
    <div class="title">
      <span>記事カバー画像</span>
    </div>
    <div class="text">
      <input type="text" name="oyiso_post_cover" value="<?=get_option('oyiso_post_cover')?>">
    </div>
  </div>
  <p class="tip">ユーザーカスタムデフォルト記事カバー画像（ランダム画像API対応）、デフォルト：<code>/assets/images/cover-post.jpg</code></p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>瞬間カバー画像</span>
    </div>
    <div class="text">
      <input type="text" name="oyiso_moment_cover" value="<?=get_option('oyiso_moment_cover')?>">
    </div>
  </div>
  <p class="tip">ユーザーカスタム瞬間ページージカバー画像（ランダム画像API対応）、デフォルト：<code>/assets/images/cover-post.jpg</code></p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>瞬間個人説明</span>
    </div>
    <div class="text">
      <input type="text" name="oyiso_moment_desc" value="<?=get_option('oyiso_moment_desc')?>">
    </div>
  </div>
  <p class="tip">デフォルト：<code><?=get_userdata(1) -> description ? get_userdata(1) -> description : 'なし'?></code></p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>瞬間ヒントテキスト</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput" id="oyiso_moment_prompt" name="oyiso_moment_prompt" <?=get_option('oyiso_moment_prompt') ? 'checked' : ''?>>
      <label for="oyiso_moment_prompt" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">カバー画像の下に表示</p>

  <div class="item-sub">
    <div class="field">
      <div class="title">
        <span>瞬間ヒントテキスト - タイトル</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_moment_prompt_title" value="<?=get_option('oyiso_moment_prompt_title')?>">
      </div>
    </div>
    <p class="tip">デフォルト：<code>瞬間と瞬き</code></p>
  </div>

  <div class="item-sub">
    <div class="field">
      <div class="title">
        <span>瞬間ヒントテキスト - 説明</span>
      </div>
      <div class="text">
        <input type="text" name="oyiso_moment_prompt_excerpt" value="<?=get_option('oyiso_moment_prompt_excerpt')?>">
      </div>
    </div>
    <p class="tip">デフォルト：<code>短い時間であり、短い美しさであり、短い記憶です。</code></p>
  </div>
</div>

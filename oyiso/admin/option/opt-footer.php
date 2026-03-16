<div class="item">
  <div class="field">
    <div class="title">
      <span>著作権年</span>
    </div>
    <div class="text">
      <input type="number" name="oyiso_copyright" value="<?=get_option('oyiso_copyright')?>">
    </div>
  </div>
  <p class="tip">数字を入力、デフォルト：<code><?=date('Y')?></code></p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>ICP登録番号</span>
    </div>
    <div class="text">
      <input type="text" name="oyiso_icp" value="<?=get_option('oyiso_icp')?>">
    </div>
  </div>
  <p class="tip">フッターにICP登録番号を表示</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>公安網備番号</span>
    </div>
    <div class="text">
      <input type="text" name="oyiso_icp_gov" value="<?=get_option('oyiso_icp_gov')?>">
    </div>
  </div>
  <p class="tip">フッターに公安網備番号を表示</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>サイト稼働時間</span>
    </div>
    <div class="text">
      <input type="text" name="oyiso_run_time" value="<?=get_option('oyiso_run_time')?>">
    </div>
  </div>
  <p class="tip">フッターに稼働時間を表示、サイト開設日を入力、例 <code>2024/01/01</code></p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>又拍雲アライアンス</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput" id="oyiso_upyun" name="oyiso_upyun" <?=get_option('oyiso_upyun') ? 'checked' : ''?>>
      <label for="oyiso_upyun" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとフッターに又拍雲アライアンスリンクを表示</p>
</div>

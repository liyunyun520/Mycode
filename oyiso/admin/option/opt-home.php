<div class="item">
  <div class="field">
    <div class="title">
      <span>お知らせ</span>
    </div>
    <div class="text">
      <input type="checkbox" class="checkboxInput"  id="oyiso_notice" name="oyiso_notice" <?=get_option('oyiso_notice') ? 'checked' : ''?>>
      <label for="oyiso_notice" class="toggleSwitch"></label>
    </div>
  </div>
  <p class="tip">有効にするとホームのTabバーにお知らせを表示（デフォルトのホームテンプレートはポップアップ表示のみ対応）</p>

  <div class="item-sub">
    <div class="field">
      <div class="title">
        <span>お知らせポップアップ <i>New-1.5.2</i></span>
      </div>
      <div class="text">
        <input type="checkbox" class="checkboxInput disabled"  id="oyiso_notice_popup" name="oyiso_notice_popup" <?=get_option('oyiso_notice_popup') ? 'checked' : ''?>>
        <label for="oyiso_notice_popup" class="toggleSwitch"></label>
      </div>
    </div>
    <p class="tip">お知らせをポップアップ表示するかどうか（お知らせ更新後に再ポップアップ）</p>
  </div>

  <div class="item-sub">
    <div class="field">
      <div class="title">
        <span>お知らせ内容</span>
      </div>
      <div class="text">
        <textarea rows="5" class="home" name="oyiso_notice_text"><?=stripslashes(get_option('oyiso_notice_text'))?></textarea>
      </div>
    </div>
    <p class="tip">表示する内容を入力</p>
  </div>
</div>

<div class="item home-select">
  <div class="field">
    <div class="title">
      <span>ホームテンプレート</span>
    </div>
    <div class="text">
      <div class="select">
        <input type="hidden" name="oyiso_home_tpl" value="<?=get_option('oyiso_home_tpl') ? get_option('oyiso_home_tpl') : ''?>">
        <input type="text" class="hide" placeholder="ホームテンプレート（デフォルト）" readonly autocomplete="off"> 
        <div class="ul-box">
          <ul>
            <li data-value=''>ホームテンプレート（デフォルト）</li>
            <li data-value='home1'>ホームテンプレート1</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <p class="tip">ホームとして使用するテンプレートを選択</p>
</div>

<div class="item home">
  <div class="item">
    <div class="field">
      <div class="title">
        <span>ホームウィジェット</span>
      </div>
      <div class="text">
        <input type="checkbox" class="checkboxInput"  id="oyiso_widgets" name="oyiso_widgets" <?=get_option('oyiso_widgets') ? 'checked' : ''?>>
        <label for="oyiso_widgets" class="toggleSwitch"></label>
      </div>
    </div>
    <ul class="tip">
      <li>有効にするとホーム上部にウィジェットを表示</li>
      <li>ソーシャルリンクは <code>外観 - メニュー</code> で追加</li>
    </ul>

    <!-- 子項目 -->
    <div class="item-sub">
      <div class="field">
        <div class="title">
          <span>ウィジェット内容</span>
        </div>
        <div class="text">
          <div class="select">
            <input type="hidden" name="oyiso_widgets_content" value="<?=get_option('oyiso_widgets_content') ? get_option('oyiso_widgets_content') : ''?>">
            <input type="text" class="hide" placeholder="おすすめ記事" readonly autocomplete="off"> 
            <div class="ul-box">
              <ul>
                <li data-value=''>おすすめ記事</li>
                <li data-value='comment'>最新コメント</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <ul class="tip">
        <li>おすすめ記事：記事を固定に設定、最大 <code>10</code> 件</li>
        <li>最新コメント：サイトの最新コメントを表示、最大 <code>10</code> 件</li>
      </ul>
    </div>
  </div>
</div>

<div class="item home home1">
  <div class="item">
    <div class="field">
      <div class="title">
        <span>ホームカバー</span>
      </div>
      <div class="text">
        <textarea rows="2" class="home1" name="oyiso_home1_cover"><?=stripslashes(get_option('oyiso_home1_cover'))?></textarea>
      </div>
    </div>
    <p class="tip">カバー画像URLを入力、英語のカンマ<code>,</code>で区切り（改行対応、ランダム画像API対応）、デフォルト：<code>/assets/images/cover-category.jpg</code></p>
  </div>

  <div class="item">
    <div class="field">
      <div class="title">
        <span>ホームスローガン</span>
      </div>
      <div class="text">
        <textarea rows="2" class="home1" name="oyiso_home1_slogan"><?=stripslashes(get_option('oyiso_home1_slogan'))?></textarea>
      </div>
    </div>
    <p class="tip">スローガン、フォント大、テーマカラーが必要な場合はspanタグで囲む、例：<code>&lt;span&gt;Oyiso Theme&lt;/span&gt;</code></p>
  </div>

  <div class="item">
    <div class="field">
      <div class="title">
        <span>ホームサブスローガン</span>
      </div>
      <div class="text">
        <textarea rows="2" class="home1" name="oyiso_home1_slogan_sub"><?=stripslashes(get_option('oyiso_home1_slogan_sub'))?></textarea>
      </div>
    </div>
    <p class="tip">サブスローガン、フォント小、テーマカラーが必要な場合はspanタグで囲む、例：<code>&lt;span&gt;Oyiso Theme&lt;/span&gt;</code></p>
  </div>
  
  <div class="item">
    <div class="field">
      <div class="title">
        <span>波アニメーションを非表示</span>
      </div>
      <div class="text">
        <input type="checkbox" class="checkboxInput"  id="oyiso_home1_waves" name="oyiso_home1_waves" <?=get_option('oyiso_home1_waves') ? 'checked' : ''?>>
        <label for="oyiso_home1_waves" class="toggleSwitch"></label>
      </div>
    </div>
    <p class="tip">有効にすると波アニメーションを非表示、ページ遷移の衔接に使用（デフォルト有効を推奨）</p>
  </div>

  <div class="item">
    <div class="field">
      <div class="title">
        <span>Tabバー / ブロガー情報</span>
      </div>
      <div class="text">
        <input type="checkbox" class="checkboxInput"  id="oyiso_home1_bloger" name="oyiso_home1_bloger" <?=get_option('oyiso_home1_bloger') ? 'checked' : ''?>>
        <label for="oyiso_home1_bloger" class="toggleSwitch"></label>
      </div>
    </div>
    <p class="tip">有効にするとホームTabバーにブロガー情報を表示</p>
  </div>

  <div class="item">
    <div class="field">
      <div class="title">
        <span>Tabバー / 固定記事</span>
      </div>
      <div class="text">
        <input type="checkbox" class="checkboxInput"  id="oyiso_home1_post" name="oyiso_home1_post" <?=get_option('oyiso_home1_post') ? 'checked' : ''?>>
        <label for="oyiso_home1_post" class="toggleSwitch"></label>
      </div>
    </div>
    <p class="tip">有効にするとホームTabバーに固定記事を表示</p>

    <div class="item-sub">
      <div class="field">
        <div class="title">
          <span>Tabバー / 固定記事 / 表示数</span>
        </div>
        <div class="text">
          <input type="number" name="oyiso_home1_post_num" value="<?=get_option('oyiso_home1_post_num')?>">
        </div>
      </div>
      <p class="tip">表示数を入力、デフォルト値：<code>6</code></p>
    </div>
  </div>

  <div class="item">
    <div class="field">
      <div class="title">
        <span>Tabバー / 瞬間</span>
      </div>
      <div class="text">
        <input type="checkbox" class="checkboxInput"  id="oyiso_home1_moment" name="oyiso_home1_moment" <?=get_option('oyiso_home1_moment') ? 'checked' : ''?>>
        <label for="oyiso_home1_moment" class="toggleSwitch"></label>
      </div>
    </div>
    <p class="tip">有効にするとホームTabバーに最新の瞬間を表示</p>

    <div class="item-sub">
      <div class="field">
        <div class="title">
          <span>Tabバー / 瞬間 / 表示数</span>
        </div>
        <div class="text">
          <input type="number" name="oyiso_home1_moment_num" value="<?=get_option('oyiso_home1_moment_num')?>">
        </div>
      </div>
      <p class="tip">表示数を入力、デフォルト値：<code>6</code></p>
    </div>
  </div>

  <div class="item">
    <div class="field">
      <div class="title">
        <span>Tabバー / コメント</span>
      </div>
      <div class="text">
        <input type="checkbox" class="checkboxInput"  id="oyiso_home1_comment" name="oyiso_home1_comment" <?=get_option('oyiso_home1_comment') ? 'checked' : ''?>>
        <label for="oyiso_home1_comment" class="toggleSwitch"></label>
      </div>
    </div>
    <p class="tip">有効にするとホームTabバーに最新のコメントを表示</p>

    <div class="item-sub">
      <div class="field">
        <div class="title">
          <span>Tabバー / コメント / 表示数</span>
        </div>
        <div class="text">
          <input type="number" name="oyiso_home1_comment_num" value="<?=get_option('oyiso_home1_comment_num')?>">
        </div>
      </div>
      <p class="tip">表示数を入力、デフォルト値：<code>6</code></p>
    </div>
  </div>

  <div class="item">
    <div class="field">
      <div class="title">
        <span>Tabバー / リンク</span>
      </div>
      <div class="text">
        <input type="checkbox" class="checkboxInput"  id="oyiso_home1_bookmark" name="oyiso_home1_bookmark" <?=get_option('oyiso_home1_bookmark') ? 'checked' : ''?>>
        <label for="oyiso_home1_bookmark" class="toggleSwitch"></label>
      </div>
    </div>
    <p class="tip">有効にするとホームTabバーに最新のリンクを表示</p>

    <div class="item-sub">
      <div class="field">
        <div class="title">
          <span>Tabバー / リンク / 表示数</span>
        </div>
        <div class="text">
          <input type="number" name="oyiso_home1_bookmark_num" value="<?=get_option('oyiso_home1_bookmark_num')?>">
        </div>
      </div>
      <p class="tip">表示数を入力、デフォルト値：<code>6</code></p>
    </div>
  </div>

  <div class="item">
    <div class="field">
      <div class="title">
        <span>Tabバー / ギャラリー</span>
      </div>
      <div class="text">
        <input type="checkbox" class="checkboxInput"  id="oyiso_home1_gallery" name="oyiso_home1_gallery" <?=get_option('oyiso_home1_gallery') ? 'checked' : ''?>>
        <label for="oyiso_home1_gallery" class="toggleSwitch"></label>
      </div>
    </div>
    <p class="tip">有効にするとホームTabバーに最新のギャラリーを表示</p>

    <div class="item-sub">
      <div class="field">
        <div class="title">
          <span>Tabバー / ギャラリー / 表示数</span>
        </div>
        <div class="text">
          <input type="number" name="oyiso_home1_gallery_num" value="<?=get_option('oyiso_home1_gallery_num')?>">
        </div>
      </div>
      <p class="tip">表示数を入力、デフォルト値：<code>6</code></p>
    </div>
  </div>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>カスタムCSSコード</span>
    </div>
    <div class="text">
      <textarea rows="7" class="custom-code" name="oyiso_custom_css"><?=stripslashes(get_option('oyiso_custom_css'))?></textarea>
    </div>
  </div>
  <p class="tip">&lt;/head&gt;の前に配置、スタイルコードを直接記述、&lt;style&gt;タグは不要。</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>カスタムJavaScriptコード</span>
    </div>
    <div class="text">
      <textarea rows="7" class="custom-code" name="oyiso_custom_js"><?=stripslashes(get_option('oyiso_custom_js'))?></textarea>
    </div>
  </div>
  <p class="tip">下部に配置、JSコードを直接記述、&lt;script&gt;タグは不要。</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>カスタムHTMLコード - ヘッダー</span>
    </div>
    <div class="text">
      <textarea rows="7" class="custom-code" name="oyiso_custom_html_head"><?=stripslashes(get_option('oyiso_custom_html_head'))?></textarea>
    </div>
  </div>
  <p class="tip">&lt;/head&gt;の前に配置、メインコンテンツ表示前に読み込まれるコード、通常はCSSスタイル、カスタム&lt;meta&gt;タグ、全サイトヘッダーJSなど、HTMLタグが必要。</p>
</div>

<div class="item">
  <div class="field">
    <div class="title">
      <span>カスタムHTMLコード - フッター</span>
    </div>
    <div class="text">
      <textarea rows="7" class="custom-code" name="oyiso_custom_html_foot"><?=stripslashes(get_option('oyiso_custom_html_foot'))?></textarea>
    </div>
  </div>
  <p class="tip">&lt;/body&gt;の前に配置、メインコンテンツ読み込み完了後に読み込まれるコード、通常は他のカスタムコンテンツやJSコード、HTMLタグが必要。</p>
</div>

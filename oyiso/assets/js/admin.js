document.addEventListener('DOMContentLoaded', function() {
  const editor = document.querySelector('.post-type-gallery #wp-content-editor-container')
  if(editor) {
    // エディターを初期化
    let textarea = editor.querySelector('#content')
    let editorContainer = document.createElement('div')
    let markdown = document.createElement('div')

    // 非表示ボタン
    let ed_tool = editor.querySelector('#ed_toolbar')
    let hidden_button = document.createElement('input')
    hidden_button.className = 'ed_button button button-small'
    hidden_button.setAttribute('type', 'button')
    
    

    // エディターコンテナを初期化
    editorContainer.classList.add('oyiso-editor')
    markdown.classList.add('oyiso-editor-md')
    
    editorContainer.appendChild(textarea)
    editorContainer.appendChild(markdown)
    editor.appendChild(editorContainer)

    // 高さ同期
    function height_sync() {
      let style = window.getComputedStyle(textarea)
      let text_height = style.getPropertyValue('height')
      markdown.style.height = text_height
    }
    function margin_sync() {
      let text_margin = ed_tool.getBoundingClientRect().height.toFixed(2)
      let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
      if (isMobile) {
        markdown.style.marginTop = '0'
      } else {
        setTimeout(()=> {
          markdown.style.marginTop = text_margin+'px'
        }, 100) 
      }
      
    }
    setTimeout(()=> {
      height_sync()
      margin_sync()
      markdown.style.opacity = 1

      // 非表示ボタンを追加
      ed_tool.appendChild(hidden_button)
    }, 100)

    // 非表示状態
    let isHidden = localStorage.getItem('isHidden')
    if (isHidden == 'true') {
      isHidden = true
      editorContainer.classList.add('full')
      hidden_button.value = 'プレビューを表示'
    } else {
      isHidden = false
      editorContainer.classList.remove('full')
      hidden_button.value = 'プレビューを非表示'
    }

    if (isHidden != 'true') {
      // // ウィンドウサイズ変化
      // function window_size() {
      //   if (window.innerWidth < 1400) {
      //     editorContainer.classList.add('full')
      //     hidden_button.value = 'プレビューを表示'
      //     isHidden = true
      //   } else {
      //     editorContainer.classList.remove('full')
      //     hidden_button.value = 'プレビューを非表示'
      //     isHidden = false
      //   }
      // }

      // window_size()
      window.addEventListener('resize', function() {
        height_sync()
        margin_sync()
      })

      // コードハイライト設定
      let options = {
        codeType: 'default',
        minHeight: '',
        maxHeight: '500px',
      }
      // mdを解析
      let text = textarea.value
      let html = marked.parse(text).trim()
      markdown.innerHTML = html

      // プレビューを非表示
      hidden_button.addEventListener('click', function() {
        editorContainer.classList.toggle('full')
        if (!isHidden) {
          isHidden = true
          hidden_button.value = 'プレビューを表示'
          localStorage.setItem('isHidden', 'true')
          
        } else {
          isHidden = false
          hidden_button.value = 'プレビューを非表示'
          localStorage.setItem('isHidden', 'false')

          // 高さ同期
          height_sync()
          margin_sync()
          // mdを解析
          let text = textarea.value
          let html = marked.parse(text).trim()
          markdown.innerHTML = html

        }
        // 高さ同期
        height_sync()
        margin_sync()
      })

      textarea.addEventListener('input', function() {
        if (!isHidden) {
          // 高さ同期
          height_sync()
          margin_sync()

          // mdを解析
          let text = textarea.value
          let html = marked.parse(text).trim()
          markdown.innerHTML = html

        }
      })
    }
  }


  // 友達リンク審査状態を変更
  let link = document.querySelector('.link_pass')
  if (link) {
    let link_p = link.querySelector('p')
    let link_input = link.querySelector('input[type="submit"]')
    
    link_input.addEventListener('click', function(e) {
      e.preventDefault()
      link_id = this.getAttribute('id')
      let formData = new FormData()
      formData.append('action', 'link_pass')
      formData.append('link_id', link_id)
      fetch(ajaxurl, {
        method: 'POST',
        body: formData,
      })
      .then(response => response.text())
      .then(data => {
        if (data == 'pass') {
          link_p.innerHTML = '審査済み'
          link_p.style.color = 'green'
          this.value = '審査を取消'
        } else {
          link_p.innerHTML = '審査待ち'
          link_p.style.color = 'red'
          this.value = '審査を通過'
        }
      })
    })
  }
})

document.addEventListener('DOMContentLoaded', function() {
  const editor = document.querySelector('.post-type-gallery #wp-content-editor-container')
  if(editor) {
    // 初始化编辑器
    let textarea = editor.querySelector('#content')
    let editorContainer = document.createElement('div')
    let markdown = document.createElement('div')

    // 隐藏按钮
    let ed_tool = editor.querySelector('#ed_toolbar')
    let hidden_button = document.createElement('input')
    hidden_button.className = 'ed_button button button-small'
    hidden_button.setAttribute('type', 'button')
    
    

    // 初始化编辑器容器
    editorContainer.classList.add('oyiso-editor')
    markdown.classList.add('oyiso-editor-md')
    
    editorContainer.appendChild(textarea)
    editorContainer.appendChild(markdown)
    editor.appendChild(editorContainer)

    // 高度同步
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

      // 添加隐藏按钮
      ed_tool.appendChild(hidden_button)
    }, 100)

    // 隐藏状态
    let isHidden = localStorage.getItem('isHidden')
    if (isHidden == 'true') {
      isHidden = true
      editorContainer.classList.add('full')
      hidden_button.value = '显示预览'
    } else {
      isHidden = false
      editorContainer.classList.remove('full')
      hidden_button.value = '隐藏预览'
    }

    if (isHidden != 'true') {
      // // 窗口变化
      // function window_size() {
      //   if (window.innerWidth < 1400) {
      //     editorContainer.classList.add('full')
      //     hidden_button.value = '显示预览'
      //     isHidden = true
      //   } else {
      //     editorContainer.classList.remove('full')
      //     hidden_button.value = '隐藏预览'
      //     isHidden = false
      //   }
      // }

      // window_size()
      window.addEventListener('resize', function() {
        height_sync()
        margin_sync()
      })

      // 代码高亮配置项
      let options = {
        codeType: 'default',
        minHeight: '',
        maxHeight: '500px',
      }
      // 解析md
      let text = textarea.value
      let html = marked.parse(text).trim()
      markdown.innerHTML = html

      // 隐藏预览
      hidden_button.addEventListener('click', function() {
        editorContainer.classList.toggle('full')
        if (!isHidden) {
          isHidden = true
          hidden_button.value = '显示预览'
          localStorage.setItem('isHidden', 'true')
          
        } else {
          isHidden = false
          hidden_button.value = '隐藏预览'
          localStorage.setItem('isHidden', 'false')

          // 高度同步
          height_sync()
          margin_sync()
          // 解析md
          let text = textarea.value
          let html = marked.parse(text).trim()
          markdown.innerHTML = html

        }
        // 高度同步
        height_sync()
        margin_sync()
      })

      textarea.addEventListener('input', function() {
        if (!isHidden) {
          // 高度同步
          height_sync()
          margin_sync()

          // 解析md
          let text = textarea.value
          let html = marked.parse(text).trim()
          markdown.innerHTML = html

        }
      })
    }
  }


  // 改变友链审核状态
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
          link_p.innerHTML = '已审核'
          link_p.style.color = 'green'
          this.value = '取消审核'
        } else {
          link_p.innerHTML = '待审核'
          link_p.style.color = 'red'
          this.value = '通过审核'
        }
      })
    })
  }
})
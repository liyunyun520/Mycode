document.addEventListener('DOMContentLoaded', function() {

  // JS読み込み完了をマーク
  const oyisoContainer = document.querySelector('.oyiso.container')
  if (oyisoContainer) {
    oyisoContainer.classList.add('js-loaded')
  }

  // パスワードの自動入力を禁止
  const opt_input = document.querySelectorAll('.oyiso input')
  opt_input.forEach((input) => {
    input.setAttribute('autocomplete', 'new-password')
  })

  // ページ切り替え
  const opt_content = document.querySelectorAll('.oyiso .content > ul > li')
  const opt_sidebar = document.querySelectorAll('.oyiso .sidebar > ul > li a')
  const opt_sidebar_li = document.querySelectorAll('.oyiso .sidebar > ul > li')

  // 要素が存在しない場合は処理を中断
  if (!opt_content.length || !opt_sidebar.length) {
    console.warn('テーマ設定ページの要素が見つかりません')
    return
  }

  let currentUrl = window.location.href
  let urlObj
  try {
    urlObj = new URL(currentUrl)
  } catch (e) {
    // URL解析エラーの場合は最初のタブを表示
    opt_sidebar[0].parentNode.classList.add('active')
    opt_content[0].classList.add('active')
    return
  }
  let params = urlObj.searchParams
  let hasItemParam = params.has('item')
  let item_name = ''
  if (opt_sidebar_li[0]) {
    item_name = opt_sidebar_li[0].getAttribute('data-item')
  }
  if (!hasItemParam && opt_sidebar[0] && opt_content[0]) {
    params.append('item', item_name)
    window.history.replaceState({}, '', `${urlObj.origin}${urlObj.pathname}?${params}`)
    opt_sidebar[0].parentNode.classList.add('active')
    opt_content[0].classList.add('active')
  } else if (hasItemParam) {
    let item = params.get('item')
    let found = false
    opt_sidebar.forEach((a, index) => {
      if (a.parentNode.getAttribute('data-item') === item) {
        found = true
        if (!a.parentNode.classList.contains('active')) {
          opt_sidebar.forEach((a) => {
            a.parentNode.classList.remove('active')
          })
          a.parentNode.classList.add('active')
        }

        if (!opt_content[index].classList.contains('active')) {
          opt_content.forEach((li) => {
            li.classList.remove('active')
          })
          opt_content[index].classList.add('active')
        }
      }
    })
    // マッチする項目がない場合、最初のタブを表示
    if (!found && opt_sidebar[0] && opt_content[0]) {
      opt_sidebar[0].parentNode.classList.add('active')
      opt_content[0].classList.add('active')
    }
  }

  setTimeout(() => {
    opt_sidebar.forEach((a) => {
      a.style.transition = 'all .3s'
    })
  }, 50)

  opt_sidebar.forEach((a, index) => {
    a.parentNode.setAttribute('index', index)
    a.addEventListener('click', (e) => {
      e.preventDefault()
      
      if (!e.currentTarget.parentNode.classList.contains('active')) {
        opt_sidebar.forEach((a) => {
          a.parentNode.classList.remove('active')
        })
        e.currentTarget.parentNode.classList.add('active')
        let item = e.currentTarget.parentNode.getAttribute('data-item')
        params.set('item', item)
        window.history.replaceState({}, '', `${urlObj.origin}${urlObj.pathname}?${params}`)
      }
      
      if (!opt_content[index].classList.contains('active')) {
        opt_content.forEach((li) => {
          li.classList.remove('active')
        })
        opt_content[index].classList.add('active')
        opt_content[index].style.animation = 'fadeToTop .3s'
      }
    })
  })


  // テーマカラー切り替え
  const opt_color = document.querySelectorAll('.oyiso .theme_color')
  let opt_theme_color
  let opt_style = document.createElement('style')
  opt_color.forEach((color, i) => {
    let color_pri = color.querySelector('label').getAttribute('data-color-pri')
    let color_sub = color.querySelector('label').getAttribute('data-color-sub')
    opt_style.innerHTML += `
      .oyiso .theme_color:nth-child(${i+1}) label {
        background: linear-gradient(120deg, ${color_pri}, ${color_sub});
      }
    `
    if (i != opt_color.length - 1) {
      opt_style.innerHTML += `
        .oyiso .theme_color:nth-child(${i+1}) label::after {
          background: linear-gradient(120deg, ${color_pri}, ${color_sub});
          -webkit-background-clip: text;
        } 
      `
    } else {
      document.documentElement.style.setProperty('--linear-color', `linear-gradient(120deg, ${color_pri}, ${color_sub})`)
    }
    
    document.head.appendChild(opt_style)

    color.querySelector('label').addEventListener('click', function() {
      opt_theme_color = this.getAttribute('data-color-pri')
      document.documentElement.style.setProperty('--theme-color-pri', opt_theme_color)
      document.documentElement.style.setProperty('--theme-color-op10', opt_theme_color+'1a')
    })
  })

  // カスタムテーマカラー
  const opt_colors = document.querySelectorAll('.oyiso .theme_color input')
  opt_colors.forEach((color) => {
    if (color.checked && color.value == 'custom') {
      color.closest('.item').classList.add('active')
    } else {
      color.closest('.item').classList.remove('active')
    }

    color.addEventListener('change', () => {
      if (color.checked && color.value == 'custom') {
        color.closest('.item').classList.add('active')
      } else {
        color.closest('.item').classList.remove('active')
      }
    })
    
  })

  // カスタムカラーパネル
  const custom_color_panel = document.querySelector('#custom-color-panel')
  if (custom_color_panel) {
    const custom_color_label = custom_color_panel.querySelectorAll('label')
    const show_label = document.querySelector('.oyiso .theme_color label[for="custom"]')
    let color_obj = {
      pri: show_label.getAttribute('data-color-pri'),
      sub: show_label.getAttribute('data-color-sub')
    }
    console.log(color_obj);
    
    custom_color_label.forEach((label) => {
      let color_input = label.querySelector('input')
      label.style.background = color_input.value

      color_input.addEventListener('input', () => {
        label.style.background = color_input.value
        if (color_input.classList.contains('color-pri')) {
          document.documentElement.style.setProperty('--theme-color-pri', color_input.value)
          document.documentElement.style.setProperty('--theme-color-op10', color_input.value+'1a')

          show_label.setAttribute('data-color-pri', color_input.value)
          color_obj.pri = color_input.value
        } else {
          show_label.setAttribute('data-color-sub', color_input.value)
          color_obj.sub = color_input.value
        }

        // カスタムカラーをテーマカラーに同期
        show_label.style.background = `linear-gradient(120deg, ${color_obj.pri}, ${color_obj.sub})`
        document.documentElement.style.setProperty('--linear-color', `linear-gradient(120deg, ${color_obj.pri}, ${color_obj.sub})`)
      })
    })
  }


  // データ変更後のヒント
  const opt_form = document.querySelector('.oyiso form')
  const opt_form_input = opt_form.querySelectorAll('input:not(.hide), textarea')
  const opt_form_tip = document.querySelector('.oyiso .banner .tip')
  const opt_submit = document.querySelector('.oyiso button[type="submit"]')

  let initialValues = {}
  let updateValues = {}
  let isChanged = {}
  // ページ読み込み時に各入力フィールドの初期値を保存し、isChangedを初期化
  opt_form_input.forEach((input) => {
    if (input.type === 'checkbox') {
      initialValues[input.name] = input.checked
    } else if (input.type === 'radio') {
      input.checked ? initialValues[input.name] = input.value : ''
    } else {
      initialValues[input.name] = input.value
    }
  })

  // console.log(initialValues)
  
  opt_form_input.forEach((input) => {
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && input.tagName !== 'TEXTAREA') {
        e.preventDefault()
      }
    })
    input.addEventListener('input', () => {
      // console.log(input.name, input.value)
      // 新しい値が初期値と異なるか確認
      let currentValue
      if (input.type === 'checkbox') {
        currentValue = input.checked
      } else if (input.type === 'radio') {
        input.checked ? currentValue = input.value : ''
      } else {
        currentValue = input.value
      }
      if (currentValue !== initialValues[input.name]) {
        opt_form_tip.innerText = 'データを変更しました。保存してください！'
        opt_submit.removeAttribute('disabled')
        if (opt_form_tip.classList.contains('success')) {
          opt_form_tip.classList.remove('success')
        }
        
        isChanged[input.name] = true
        updateValues[input.name] = currentValue
      } else {
        isChanged[input.name] = false
        delete updateValues[input.name]
      }
  
      // いずれかのフィールドが変更されたか確認
      let anyChanged = Object.values(isChanged).some(value => value)
      if (!anyChanged) {
        opt_form_tip.innerText = ''
        opt_submit.setAttribute('disabled', 'disabled')
      }
    })
  })
  
  window.addEventListener('beforeunload', (e) => {
    // いずれかのフィールドが変更されたか確認
    let anyChanged = Object.values(isChanged).some(value => value)
    if (anyChanged) {
      let message = 'データを変更しましたが、保存していません。このページを離れますか？'
      e.returnValue = message
      return message
    }
  })


  // データを保存
  let opt_submit_icon = opt_submit.querySelector('svg use').getAttribute('xlink:href')
  function loading(loading=false) {
    if (loading == true) {
      opt_submit.setAttribute('disabled', 'disabled')
      opt_submit.querySelector('svg use').setAttribute('xlink:href', '#icon-loading')
      opt_submit.classList.add('loading')
    } else if (loading == 'success') {
      opt_submit.setAttribute('disabled', 'disabled')
      opt_submit.querySelector('svg use').setAttribute('xlink:href', opt_submit_icon)
      opt_submit.classList.remove('loading')
    } else {
      opt_submit.removeAttribute('disabled')
      opt_submit.querySelector('svg use').setAttribute('xlink:href', opt_submit_icon)
      opt_submit.classList.remove('loading')
    }
  }

  function update_option() {
    let formData = new FormData()

    // console.log(updateValues);

    for (let key in updateValues) {
      formData.append(key, updateValues[key])
    }

    formData.append('action', 'oyiso_options')
    
    // 処理：テーマカラー
    if (formData.has('oyiso_theme_color')) {
      const color = document.querySelector('.oyiso .theme_color input:checked+label')
      let pri = color.getAttribute('data-color-pri')
      let sub = color.getAttribute('data-color-sub')
      let oyiso_theme_colors = JSON.stringify({pri, sub})
      formData.set('oyiso_theme_colors', oyiso_theme_colors)
    }

    if (formData.has('oyiso_custom_color_pri') || formData.has('oyiso_custom_color_sub')) {
      let oyiso_theme_colors_custom = {
        pri: formData.get('oyiso_custom_color_pri') || document.querySelector('#custom_color_pri').value,
        sub: formData.get('oyiso_custom_color_sub') || document.querySelector('#custom_color_sub').value
      }
      formData.set('oyiso_theme_colors_custom', JSON.stringify(oyiso_theme_colors_custom))
    }

    // console.log(Array.from(formData.entries()))

    // return

    loading(true)

    fetch(ajaxurl, {
      method: 'POST',
      body: formData,
    })
    .then(response => response.json())
    .then(data => {
      // console.log(data)
      if (data.success) {
        loading('success')
        opt_form_tip.innerText = 'データを保存しました！'
        opt_form_tip.classList.add('success')
        opt_form_input.forEach((input) => {
          if (input.type === 'checkbox') {
            initialValues[input.name] = input.checked
          } else if (input.type === 'radio') {
            input.checked ? initialValues[input.name] = input.value : ''
          } else {
            initialValues[input.name] = input.value
          }
          isChanged[input.name] = false
        })
        updateValues = {}
      } else {
        loading(false)
        opt_form_tip.innerText = '保存に失敗しました。後でもう一度お試しください！'
      }
    })
    .catch(error => {
      loading(false)
      opt_form_tip.innerText = '保存に失敗しました。後でもう一度お試しください！'
    })
  }

  opt_submit.addEventListener('click', (e) => {
    e.preventDefault()
    update_option()
  })

  document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 's') {
      e.preventDefault()
      if (!opt_submit.hasAttribute('disabled')) {
        update_option()
      }
    }
  })


  // セレクター
  const opt_select = document.querySelectorAll('.oyiso .select')
  opt_select.forEach((opt_select) => {
    let opt_select_input = opt_select.querySelector('input[type="hidden"]')
    let opt_select_input_2 = opt_select.querySelector('input:not([type="hidden"])')
    let opt_select_ul = opt_select.querySelector('.ul-box')
    let opt_select_li = opt_select.querySelectorAll('li')

    // 二次セレクターの表示判定
    let opt_select_input_value = opt_select_input.value
    if (opt_select_input_value) {
      let itemSub = opt_select.closest('.item-sub');
      if (itemSub) {
          itemSub.classList.add('active');
      }
    }

    // placeholder
    opt_select_li.forEach((li) => {
      if (li.getAttribute('data-value') == opt_select_input.value) {
        opt_select_input_2.placeholder = li.textContent
      }
    })

    opt_select.addEventListener('click', (e) => {
      e.preventDefault()
      // 二次セレクターの表示判定
      let opt_select_input_value = opt_select_input.value
      let item_sub = e.target.closest('.item-sub')
      if(item_sub) {
        if (opt_select_input_value) {
          item_sub.classList.add('active')
        } else {
          item_sub.classList.remove('active')
        }
      }

      // セレクターの開閉
      if (e.target != opt_select_ul && !opt_select_ul.contains(e.target)) {
        opt_select.classList.add('active')
        opt_select_input.disabled = true
      }
    })
    document.addEventListener('click', (e) => {
      if (!opt_select.contains(e.target)) {
        opt_select.classList.remove('active')
        opt_select_input.disabled = false
      }
    })
    opt_select_li.forEach((li) => {
      if (li.getAttribute('data-value') == opt_select_input.value) {
        li.classList.add('active')
      }
      li.addEventListener('click', () => {
        opt_select_li.forEach((li) => {
          li.classList.remove('active')
        })
        li.classList.add('active')
        let newValue = li.getAttribute('data-value')
  
        opt_select_input.value = newValue
        opt_select_input_2.placeholder = li.textContent
        opt_select_input.setAttribute('placeholder', li.textContent)
        opt_select.classList.remove('active')
        opt_select_input.disabled = false
  
        let event = new Event('input', {
          bubbles: true,
          cancelable: true,
        })
        opt_select_input.dispatchEvent(event)
      }) 
    })
  })


  // switch
  const opt_switch = document.querySelectorAll('.oyiso .item .checkboxInput:not(.disabled)')
  opt_switch.forEach((opt_switch) => {
    // 二次セレクターの表示判定
    if (opt_switch.checked) {
      opt_switch.closest('.item').classList.add('active')
    }

    // 二次セレクタースイッチ
    opt_switch.addEventListener('click', (e) => {
      let item = e.target.closest('.item')
      if (item) {
        if (e.target.checked) {
          item.classList.add('active')
        } else {
          item.classList.remove('active')
        }
      }
    })
  })
  

  // 管理者アバター
  const opt_avatar = document.querySelector('.oyiso .sidebar .admin .avatar')
  const opt_avatar_img = opt_avatar.querySelector('img')
  const opt_avatar_input = document.querySelector('.oyiso .sidebar .admin .avatar .choose input')
  const opt_avatar_media = document.querySelector('.oyiso .sidebar .admin .avatar .media')
  const opt_avatar_save = document.querySelector('.oyiso .sidebar .admin .avatar .save')
  const opt_avatar_restore = document.querySelector('.oyiso .sidebar .admin .avatar .restore')

  function open_meida() {
    // メディアライブラリを作成
    let mediaUploader = wp.media({
      title: 'メディアファイルを選択',
      button: {
        text: '選択'
      },
      multiple: false // 複数ファイルの選択を許可するかどうか
    })
    // 選択完了時
    mediaUploader.on('select', function() {
      let attachment = mediaUploader.state().get('selection').first().toJSON()
      // 選択したメディアファイルのURLをページに表示
      let avatar_url = attachment.url
      update_admin_avatar(avatar_url)
    })
    // メディアセレクターを開く
    mediaUploader.open()
  }

  function update_admin_avatar(avatar_url) {
    let data = new FormData()
    data.append('action', 'update_avatar')
    data.append('avatar_url', avatar_url)
    fetch(ajaxurl, {
      method: 'POST',
      body: data
    })
    .then(response => response.text())
    .then(data => {
      // console.log(data)
      if (data == 'success') {
        opt_avatar_img.src = avatar_url
        opt_avatar_input.value = avatar_url
        // if (opt_avatar.classList.contains('active')) {
        //   opt_avatar.classList.remove('active')
        // }
      } else if (data != 'failed') {
        opt_avatar_img.src = data
        opt_avatar_input.value = ''
        // if (opt_avatar.classList.contains('active')) {
        //   opt_avatar.classList.remove('active')
        // }
      }
    })
  }

  opt_avatar_img.addEventListener('click', (e) => {
    e.preventDefault()
    opt_avatar.classList.toggle('active')
  })

  document.addEventListener('click', (e) => {
    if (opt_avatar.classList.contains('active') &&!opt_avatar.contains(e.target)) {
      opt_avatar.classList.remove('active')
    }
  })

  // メディアライブラリを選択
  opt_avatar_media.addEventListener('click', (e) => {
    e.preventDefault()
    open_meida()
  })

  // 保存ボタン
  opt_avatar_save.addEventListener('click', (e) => {
    e.preventDefault()
    let avatar_url = opt_avatar_input.value
    update_admin_avatar(avatar_url)
  })

  // デフォルトに戻す
  opt_avatar_restore.addEventListener('click', (e) => {
    e.preventDefault()
    let avatar_url = ''
    update_admin_avatar(avatar_url)
  })

  
  // 又拍雲ストレージ使用量
  const upyun_usage = document.querySelector('.oyiso #upyun-usage')
  const oyiso_upyun_uss = document.querySelector('.oyiso #oyiso_upyun_uss')
  if (oyiso_upyun_uss.checked) {
    fetch(ajaxurl, {
      method: 'POST',
      body: 'action=upyun_usage',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    })
    .then(response => response.json())
    .then(response => {
      if (response.data) {
        upyun_usage.innerHTML = response.data
      } else {
        upyun_usage.innerHTML = '取得失敗'
      }
      
    })
  }

  // ホームテンプレート切り替え
  const home_select = document.querySelectorAll('.oyiso .home-select .ul-box li')
  const home_templates = document.querySelectorAll('.oyiso .item.home')
  home_select.forEach((home, i) => {
    home_templates[i].classList.remove('show')
    if (home_select[i].classList.contains('active')) {
      home_templates[i].classList.add('show')
    }
    home.addEventListener('click', (e) => {
      home_templates.forEach((tpl, j) => {
        tpl.classList.remove('show')
        if (i === j) {
          tpl.classList.add('show')
        }
      })
    })
  })
  

  // テーマを更新
  const update_btn = document.querySelector('.oyiso .update-btn')
  const update_tip = document.querySelector('.oyiso .opt-update-box .tip')
  if (update_btn) {
    update_btn.addEventListener('click', (e) => {
      e.preventDefault()
      update_btn.classList.add('disabled')
      update_tip.innerText = '更新中です。ページを更新しないでください！'
      if (update_tip.classList.contains('success')) {
        update_tip.classList.remove('success')
      }
      let formData = new FormData()
      formData.append('action', 'oyiso_update')
      formData.append('url', update_btn.href)
      formData.append('version', update_btn.getAttribute('data-version'))
      fetch(ajaxurl, {
        method: 'POST',
        body: formData,
      })
      .then(response => response.json())
      .then(data => {
        update_tip.innerHTML = data.data
        if (!data.success) {
          update_btn.classList.remove('disabled')
        } else {
          update_tip.classList.add('success')
        }
      }).catch(error => {
        update_btn.classList.remove('disabled')
        update_tip.innerText = 'ネットワーク接続エラー！'
      })
    })
  }

})

getThemeInfo()// テーマ情報を取得
loadBar() // ローディングバー - 読み込み中

// ホーム設定
const homeView = (def_img) => {
  if (!def_img) return
  
  // home1
  let imgbox = document.querySelector('.home1-bannar .imgbox')
  if (imgbox) {
    let preloader = document.querySelector('.home1-bannar .preloader')
    let img = imgbox.querySelector('img')
    const handleImageLoad = () => {
      preloader.classList.add('hidden')
      imgbox.classList.add('loaded')
    }
    const handleImageError = (attempts) => {
      if (attempts > 0) {
        setTimeout(() => {
          img.src = img.src; // 画像を再読み込み
          img.onload = () => handleImageLoad();
          img.onerror = () => handleImageError(attempts - 1)
        }, 1000); // 1秒遅延して再試行
      } else {
        img.src = def_img
        preloader.classList.add('hidden')
        imgbox.classList.add('loaded')
      }
    }
    // 画像が既に読み込み完了かチェック
    if (img.complete) {
      setTimeout(handleImageLoad, 10)
      
    } else {
      img.onload = handleImageLoad
      img.onerror = () => handleImageError(2)
    }
  }
}

const home1_newest = () => {
  // ホームタブ切替
  const home_newest = document.querySelector('.home1-newest')
  if (home_newest) {
    const home1_slider = home_newest.querySelector('.tab .slider')
    const home1_tab = home_newest.querySelectorAll('.tab li')
    const home1_content = home_newest.querySelector('.content')
    const home1_content_li = home1_content.querySelectorAll('.content > li')

    if (home1_tab[0]) {
      home1_tab[0].classList.add('active')
    }

    window.addEventListener('resize', () => {
      home1_content_li.forEach((content) => {
        if (content.classList.contains('active')) {
          home1_content.style.height = content.offsetHeight + 'px'
        }
      })
    })
    
    home1_tab.forEach((tab, i) => {
      // sliderの初期幅と位置を設定
      if (tab.classList.contains('active')) {
        home1_slider.style.width = `${tab.offsetWidth}px`;
        home1_slider.style.transform = `translateX(${tab.offsetLeft}px)`;

        if (home1_content_li[i]) {
          home1_content_li[i].classList.add('active')
          home1_content.style.height = home1_content_li[i].offsetHeight + 'px'
        }
      }

      // タブクリックで切替
      tab.addEventListener('click', () => {
        if (!tab.classList.contains('active')) {
          home1_tab.forEach((t, j) => {
            if (t.classList.contains('active')) {
              if (i > j) {
                home1_content_li[i].style.animation = 'home1FadeToLeft .3s ease forwards'
                home1_content_li[j].style.animation = 'home1FadeOutRight .3s ease forwards'
              } else if (i < j) {
                home1_content_li[i].style.animation = 'home1FadeToRight .3s ease forwards'
                home1_content_li[j].style.animation = 'home1FadeOutLeft .3s ease forwards'
              }
            }
            t.classList.remove('active')
          })
          
          // sliderの幅と位置を設定
          tab.classList.add('active')
          tab.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'center'
          })
          home1_content_li.forEach((content) => {
            content.classList.remove('active')
          })
          home1_content_li[i].classList.add('active')
          home1_slider.style.width = `${tab.offsetWidth}px`;
          home1_slider.style.transform = `translateX(${tab.offsetLeft}px)`
          home1_content.style.height = home1_content_li[i].offsetHeight + 'px'
          home1_content.addEventListener('transitionend', () => {
            sr.delegate()
          })
        }
      })
    })
  }
}

// メニューパネルボタン
const menuSwitch = () => {
  // banner
  const author = document.querySelector('main .banner .author')
  const widget = document.querySelector('main .banner .widget')
  if (author) {
    window.addEventListener('resize', () => {
      let author_height = author.getBoundingClientRect().height.toFixed(2)
      if (widget) {
        widget.style.height = author_height+'px'
      }
    })
    let author_height = author.getBoundingClientRect().height.toFixed(2)
    if (widget) {
      widget.style.height = author_height+'px'
    }
  }

  // user
  const user = document.querySelector('header .user')
  user.querySelector('.box').addEventListener('click', function(e) {
    e.preventDefault()
    user.classList.toggle('active')
  })
  document.addEventListener('click', (e) => {
    if (!user.contains(e.target) && user.classList.contains('active')) {
      user.classList.remove('active')
    }
  })
  

  // headerボタンとパネル、マスクを取得
  const menu_btn = document.querySelector('.menu-btn')
  const header = document.querySelector('header')
  // const menu_group = document.querySelector('header .nav-menu .menu-group')
  const overlay = document.querySelector('.overlay')
  const menu_item = document.querySelectorAll('header .search-m, header .nav-menu .menu > li')
  
  // コメントパネルとボタンを取得
  const comment_box = document.querySelector('main .post-comments .comment-container')
  const comment_btn = document.querySelector('main .post-tool .comments a')
  const comment_moment_btn = document.querySelectorAll('main .tpl-moment .content li.item .tool .func .comment:not(.close)')
  
  // headerロジック処理
  if(!header.classList.contains('active')) {
    document.body.style.overflowY = 'auto'
  }
  let delay = 0
  menu_item.forEach(function(item) {
    item.style.transitionDelay = delay + 's'
    delay += 0.01
  })
  menu_btn.addEventListener('click', function(e) {
    e.preventDefault
    if (comment_box && comment_box.classList.contains('active')) {
      comment_box.classList.remove('active') //コメントパネルを閉じる
    }

    if (comment_btn && comment_btn.classList.contains('active')) {
      comment_btn.classList.remove('active') //コメントボタンを閉じる
    }

    comment_moment_btn.forEach((btn) => {
      if (btn.classList.contains('active')) {
        btn.classList.remove('active')
      }
    })

    let toc_container = document.querySelector('.toc-container')
    if (toc_container && toc_container.classList.contains('active')) {
      toc_container.classList.remove('active')
    }

    if(!header.classList.contains('active')) {
      header.classList.add('active')
      overlay.classList.add('active')
      document.body.style.overflowY = 'hidden'
    } else {
      header.classList.remove('active')
      overlay.classList.remove('active')
      document.body.style.overflowY = 'auto'
    }
  })
  // document.addEventListener('click', (e) => {
  //   if(!menu_btn.contains(e.target) && !menu_group.contains(e.target)) {
  //     header.classList.remove('active')
  //     overlay.classList.remove('active')
  //   }
  // })
  overlay.addEventListener('click', function() {
    if(this.classList.contains('active')) {
      header.classList.remove('active')
      this.classList.remove('active')
      document.body.style.overflowY = 'auto'
    }
  })

  window.addEventListener('scroll', () => {
    if (user.classList.contains('active')) {
      user.classList.remove('active')
    }

    if (overlay.classList.contains('active') || header.classList.contains('active')) {
      header.classList.remove('active')
      overlay.classList.remove('active')
    }
  })

  // コメントパネルロジック処理
  if (comment_box) {
    if (comment_btn) {
      let tip_animation = 'fadeOutLeft .4s ease 3s forwards'
      let tip_icon_animation = 'levelMove .5s ease .5s 2'
      // コメント返信がある場合、コメントパネルを開く
      let hash = window.location.hash
      if (hash.startsWith('#comment_reply=')) {
        comment_btn.classList.add('active')
        comment_box.classList.add('active')
        comment_box.querySelector('.close-tip').style.animation = tip_animation
        comment_box.querySelector('.close-tip svg').style.animation = tip_icon_animation
      }

      // クリックしてコメントパネルを開く
      comment_btn.addEventListener('click', function() {
        comment_btn.classList.toggle('active')
        comment_box.classList.toggle('active')
        // comment_box.querySelector('.close-tip').style.display = 'flex'
        if (comment_box.classList.contains('active')) {
          comment_box.querySelector('.close-tip').style.animation = tip_animation
          comment_box.querySelector('.close-tip svg').style.animation = tip_icon_animation
        }
        let toc_container = document.querySelector('.toc-container')
        if (toc_container && toc_container.classList.contains('active')) {
          toc_container.classList.remove('active')
        }

        if (window.innerWidth <= 650) {
          if (comment_box.classList.contains('active')) {
            document.body.style.overflowY = 'hidden'
          } else {
            document.body.style.overflowY = 'auto'
          }
        }
      })
    }
    
    // コメントパネルを閉じる
    comment_box.querySelector('.close').addEventListener('click', function() {
      document.body.style.overflowY = 'auto'
      if (comment_box && comment_box.classList.contains('active')) {
        comment_box.classList.remove('active')
      }
      if (comment_btn && comment_btn.classList.contains('active')) {
        comment_btn.classList.remove('active')
      }
      comment_moment_btn.forEach((btn) => {
        if (btn.classList.contains('active')) {
          btn.classList.remove('active')
        }
      })
    })

    window.addEventListener('resize', function() {
      if (header.classList.contains('active') && overlay.classList.contains('active')) {
        document.body.style.overflowY = 'hidden'
      } else if (window.innerWidth < 650 && comment_box.classList.contains('active')) {
        document.body.style.overflowY = 'hidden'
      } else {
        document.body.style.overflowY = 'auto'
      }
    })

    // 左スワイプでコメントパネルを閉じる
    let startX = 0;
    comment_box.addEventListener('touchstart', function(e) {
      startX = e.touches[0].clientX;
    })
    comment_box.addEventListener('touchmove', function(e) {
      let moveX = e.touches[0].clientX;
      let distance = startX - moveX; 
      if (distance > 100) {
        if (comment_box && comment_box.classList.contains('active')) {
          comment_box.classList.remove('active')
        }
        if (comment_btn && comment_btn.classList.contains('active')) {
          comment_btn.classList.remove('active')
        }
        comment_moment_btn.forEach((btn) => {
          if (btn.classList.contains('active')) {
            btn.classList.remove('active')
          }
        })
        document.body.style.overflowY = 'auto'
      }
    })
  }

  window.addEventListener('resize', function() {
    if (window.innerWidth > 1072) {
      if (header.classList.contains('active') && overlay.classList.contains('active')) {
        header.classList.remove('active')
        overlay.classList.remove('active')
      }
    }

    if (header.classList.contains('active') && overlay.classList.contains('active')) {
      document.body.style.overflowY = 'hidden'
    } else if (window.innerWidth < 650 && comment_box && comment_box.classList.contains('active')) {
      document.body.style.overflowY = 'hidden'
    } else {
      document.body.style.overflowY = 'auto'
    }
  })
}


// テーマモード切替
const lightSwitch = () => {
  const lighting_li = document.querySelectorAll('footer nav .lighting ul li')
  const lighting_slider = document.querySelector('footer nav .lighting .slider')
  
  // テーマモードを確認、ない場合はsystemに設定
  const storedThemeColor = localStorage.getItem('themeColor')

  if (storedThemeColor) {
    lighting_li.forEach(function(item) {
      if(item.getAttribute('data-theme-color') == storedThemeColor) {
        item.classList.add('active')
        lighting_slider.style.transform = 'translateX(' + item.offsetLeft + 'px)'
      } else {
        item.classList.remove('active')
      }
    })
  } else {
    localStorage.setItem('themeColor', 'system')
    const system_li = document.querySelector('footer nav .lighting ul li[data-theme-color="system"]')
    system_li.classList.add('active')
    lighting_slider.style.transform = 'translateX(' + system_li.offsetLeft + 'px)'
  }

  setTimeout(() => {
    lighting_slider.style.transition = 'all .3s'
  }, 50)

  lighting_li.forEach((li) => {

    setTimeout(() => {
      li.style.transition = 'all .3s'
    }, 50)

    li.addEventListener('click', () => {
      lighting_li.forEach((item) => {
        item.classList.remove('active')
      })
      let old_mode = document.querySelector('html').getAttribute('data-theme-mode')
      li.classList.add('active')
      lighting_slider.style.transform = 'translateX(' + li.offsetLeft + 'px)'

      let themeMode = li.getAttribute('data-theme-color')
      localStorage.setItem('themeColor', themeMode)

      changeMode(themeMode)
      CodeDarkMode()

      // カラー切替通知
      let new_mode = document.querySelector('html').getAttribute('data-theme-mode')
      // console.log(new_mode)
      if (old_mode != new_mode && new_mode == 'dark') {
        toast('ダークモードに切り替えました！')
      } else if (old_mode != new_mode) {
        toast('ライトモードに切り替えました！')
      }

      // システムモードに切り替え時、システムモードを監視
      let current_mode = localStorage.getItem('themeColor')
      if (current_mode != 'system') {
        // darkMode.removeEventListener('change', handleDarkModeChange)
    // ios13 safari 互換処理
    if (darkMode.removeListener) {
      darkMode.removeListener(handleDarkModeChange);
    } else {
      darkMode.removeEventListener('change', handleDarkModeChange)
    }
      } else {
        // darkMode.addEventListener('change', handleDarkModeChange)
    // ios13 safari 互換処理
    if (darkMode.addListener) {
      darkMode.addListener(handleDarkModeChange);
    } else {
      darkMode.addEventListener('change', handleDarkModeChange)
    }
      }
    })
  })
  
  // テーマモード切替通知
  function handleDarkModeChange(e) {
    if (e.matches) {
      toast('ダークモードに切り替えました！')
    } else {
      toast('ライトモードに切り替えました！')
    }
  }
  let current_mode = localStorage.getItem('themeColor')
  if (current_mode == 'system') {
    // darkMode.addEventListener('change', handleDarkModeChange)
    // ios13 safari 互換処理
    if (darkMode.addListener) {
      // 旧バージョンの Safari 向け
      darkMode.addListener(handleDarkModeChange);
    } else {
      // 新バージョンの Safari と他のブラウザ向け
      darkMode.addEventListener('change', handleDarkModeChange)
    }
  }
}

// スクロールエフェクト
const scrollReveal = (reset=false) => {
  if (reset) {
    sr.clean('.header-reveal')
    sr.clean('.banner-reveal')
    sr.clean('.main-reveal')
    sr.clean('.text-reveal')
    sr.clean('.img-reveal')
    sr.clean('.homeimg-reveal')
  }
  const view_offset = {
    top: -100,
    right: 0,
    bottom: -100,
    left: 0,
  }

  // ScrollRevealインスタンスを初期化
  sr = ScrollReveal()

  // header-reveal
  sr.reveal('.header-reveal', {
    reset: false,
    origin: 'top',
    distance: '60px',
    duration: 1000,
    delay: 0,
    opacity: 0,
    viewOffset: view_offset,
  })

  // banner-reveal
  sr.reveal('.banner-reveal', {
    reset: false,
    origin: 'top',
    distance: '30px',
    duration: 1000,
    delay: 0,
    opacity: 0,
    interval: 50,
    viewOffset: view_offset,
  })

  // main-reveal
  sr.reveal('.main-reveal', {
    reset: false, 
    origin: 'bottom',
    distance: '50px',
    duration: 1000,
    delay: 0,
    opacity: 0,
    interval: 50,
    viewOffset: view_offset,
  })

  // text-reveal
  sr.reveal('.text-reveal', {
    reset: false,
    origin: 'bottom',
    distance: '30px',
    duration: 1000,
    delay: 0,
    opacity: 0,
    viewOffset: view_offset,
  })

  // img-reveal
  sr.reveal('.img-reveal', {
    reset: false,
    origin: 'bottom',
    distance: '50px',
    duration: 1000,
    delay: 50,
    opacity: 0,
    viewOffset: view_offset,
  })

  // home1img-reveal
  sr.reveal('.home1img-reveal', {
    reset: false,
    duration: 1500,
    delay: 0,
    opacity: 0,
    scale: 1.25,
    viewOffset: view_offset,
  })
}

// 記事処理
const ArticleProcessing = () => {
  // 見出し処理
  const headings = document.querySelectorAll('main .post-content .content h1, main .post-content .content h2, main .post-content .content h3, main .post-content .content h4')
  headings.forEach(function(heading) {
    let span = document.createElement('span')
    span.textContent = heading.textContent
    heading.textContent = ''
    heading.appendChild(span)
  })

  // 以下はツールバー処理

  // 記事いいね処理
  const likes = document.querySelectorAll('main .post-tool .likes a, main .tpl-moment .content li.item .tool .like')
  likes.forEach((like) => {
    let like_count = like.querySelector('span')
    let post_id = like.getAttribute('data-id')
    function getCookieKey(key) {
      let cookies = document.cookie.split('; ')
      let cookie = cookies.find(item => item.startsWith(key + '='))
      if (cookie) {
        return cookie.split('=')[0]
      } else {
        return null
      }
    }

    if (getCookieKey(post_id)) {
      like.style.color = 'var(--theme-color-pri)'
    }

    like.addEventListener('click', function() {
      if (getCookieKey(post_id) == null) {
        fetch('/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: new URLSearchParams({
            action: 'post_like',
            post_id: post_id
          })
        })
        .then(response => response.text())
        .then(response => {
          toast('いいね！ありがとうございます！')
          like.style.color = 'var(--theme-color-pri)'
          like_count.innerText = response
          document.cookie = post_id + '=liked; expires=' + new Date(new Date().getTime() + 86400000).toUTCString()
        })
        .catch(() => {
          toast('いいねに失敗しました。後でもう一度お試しください！')
        })
      } else {
        toast('すでにいいね済みです。また明日お越しください！')
      }

    })
  })

  // momentテンプレートパネルボタン
  const tool = document.querySelectorAll('main .tpl-moment .content li.item .tool .func')
  tool.forEach((tool) => {
    let btn = tool.querySelector('.more span')
    btn.addEventListener('click', () => {
      btn.parentNode.classList.toggle('active')
    })

    document.addEventListener('click', (e) => {
      if (btn.parentNode.classList.contains('active')) {
        if (!tool.contains(e.target)) {
          btn.parentNode.classList.remove('active')
        }
      }
    })
  })


  // コメントエリア
  const comment_container = document.querySelector('section.post-comments .comment-container')
  if (comment_container) {
    const comment_body = comment_container.querySelector('.comment-body')
    const comment_next = comment_body.querySelector('.next-comments a')
    const comment_ul = comment_body.querySelector('ul')

    // コメントを取得
    function get_comments(post_id, offset, query, comments_per_page = 10) {
      // console.log(post_id, offset, comments_per_page, query)
      // console.log(123);
      comment_next.classList.add('loading')
      comment_next.querySelector('span').innerText = '読み込み中'
      let visitor_author = getCookie('visitor_author')
      let visitor_email = getCookie('visitor_email')

      function add_more() {
        if (comment_next.classList.contains('loading')) {
          comment_next.classList.remove('loading')
          comment_next.className = ''
          comment_next.querySelector('span').innerText = 'もっと読み込む'
        }
      }
      function remove_more() {
        if (comment_next.classList.contains('loading')) {
          comment_next.classList.remove('loading')
          comment_next.classList.add('end')
          comment_next.querySelector('span').innerText = 'これ以上コメントはありません！'
        }
      }
      function remove_same_time() {
        let comment_date = comment_ul.querySelectorAll('.datetime')
        let old_date
        comment_date.forEach((date) => {
          new_date = date.textContent
          if (old_date == new_date) {
            date.remove()
          } else {
            old_date = new_date
          }
        })
      }
      // console.log(offset, query)
      return fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: new URLSearchParams({
          action: 'load_comments',
          post_id: post_id,
          offset: offset,
          query: query,
          comments_per_page: comments_per_page,
          visitor_author: visitor_author,
          visitor_email: visitor_email,
          timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
        }),
      })
      .then(response => response.json())
      .then(response => {

        // console.log(response)
        // console.log(response.ended)
    
        if (response) {
            // コメントが閉じている場合
            if (response.data == 'closed') {
              toast('このコメントは閉じられています！')
            return
          }

            // データがある場合
            let data = response.comments
          if (data) {
            let lis = Array.from(
              new DOMParser().parseFromString(data, 'text/html').querySelectorAll('li')
            )
            // console.log(lis)
            if (lis.length == 1) {
              comment_ul.insertAdjacentHTML('afterbegin', data)
            } else if (lis.length > 1) {
              lis = lis.reverse()
              let reversedStr = lis.map(li => li.outerHTML).join('')
              comment_ul.insertAdjacentHTML('afterbegin', reversedStr)
            }

            // コメント返信
            let hash = window.location.hash;
            if (hash.startsWith('#comment_reply=')) {
              let value = hash.split('=')[1];
              comment_ul.querySelectorAll('li').forEach((li) => {
                if (li.id == value) {
                  li.classList.add('at')
                }
              })
            }

            // 終了かどうか判定
            if (!response.ended) {
              add_more()
              remove_same_time()
              return response.offset
            } else {
              remove_more()
              remove_same_time()
              return false
            }
          } else {
            remove_more()
          }
        }
      })
      .catch(() => {
        remove_more()
        toast('コメントの取得に失敗しました。後でもう一度お試しください！')
      })
    }

    // cookieを取得
    function getCookie(name) {
      let nameEQ = name + '='
      let ca = document.cookie.split(';')
      for (let i=0; i < ca.length; i++) {
        let c = ca[i]
        while (c.charAt(0) == ' ') c = c.substring(1, c.length)
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length)
      }
      return null
    }


    let info = {
      post_id: null,
      offset: 0,
      query: 1
    }
    let isFetching = false

    function get_comments_func(post_id, offset = 0, query = 1) {
      // console.log(post_id, comments_per_page, offset, query)

      if (scrollRequest) {
        // console.log('removed scrollRequest');
        comment_body.removeEventListener('scroll', scrollRequest)
      }
      if (clickRequest) {
        comment_next.removeEventListener('click', clickRequest)
      }

      let scrolled = false
      let scrollHeight = comment_body.scrollHeight
      let observer_init = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.addedNodes.length > 0 && !scrolled) {
            // console.log('Element has been rendered')
            if (comment_body && comment_body.scrollHeight > scrollHeight) {
              comment_body.scrollTop = comment_body.scrollHeight
              scrolled = true
            }
            atUser()
          }
        })
      })
      // observer_initを実行
      observer_init.observe(comment_body, { childList: true, subtree: true })

      info.post_id = post_id;
      info.offset = offset;
      info.query = query;
      get_comments(post_id, offset, query).then((res) => {
        if (res) {
          info.offset = res
          info.query ++
          // console.log(offset, query)
          comment_body.addEventListener('scroll', scrollRequest)
          comment_next.addEventListener('click', clickRequest)
        } else {
          if (scrollRequest) {
            comment_body.removeEventListener('scroll', scrollRequest)
          }
          if (clickRequest) {
            comment_next.removeEventListener('click', clickRequest)
          }
        }
      })
    }

    // コメントを取得
    function scrollRequest() {
      if (isFetching) return
      if (comment_body.scrollTop === 0) {
        // console.log('added scrollRequest');
        isFetching = true
        // console.log(info.post_id, info.offset, info.query);

        let prevScrollHeight = comment_body.scrollHeight
        let observer_scroll = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
              let newScrollTop = comment_body.scrollHeight - prevScrollHeight
              comment_body.scrollTop = newScrollTop
            }
            atUser()
          })
        })
        observer_scroll.observe(comment_body, { childList: true, subtree: true })

        get_comments(info.post_id, info.offset, info.query).then((res) => {
          isFetching = false;
          if (res) {
            info.offset = res
            info.query++
          } else {
            if (scrollRequest) {
              comment_body.removeEventListener('scroll', scrollRequest)
            }
          }
        })
      }
    }

    function clickRequest() {
      if (isFetching) return
      // console.log('added clickRequest');
      isFetching = true
      // console.log(info.post_id, info.offset, info.query);

      let prevScrollHeight = comment_body.scrollHeight
      let observer_click = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.addedNodes.length > 0) {
            let newScrollTop = comment_body.scrollHeight - prevScrollHeight
            comment_body.scrollTop = newScrollTop
          }
          atUser()
        })
      })
      observer_click.observe(comment_body, { childList: true, subtree: true })

      get_comments(info.post_id, info.offset, info.query).then((res) => {
        isFetching = false;
        if (res) {
          info.offset = res
          info.query++
        } else {
          if (clickRequest) {
            comment_next.removeEventListener('click', clickRequest)
          }
        }
      })
    }

    if (comment_container.parentNode.classList.contains('moments')) {
      let tip_animation = 'fadeOutLeft .4s ease 3s forwards'
      let tip_icon_animation = 'levelMove .5s ease .5s 2'
      // console.log(1)
      const moment_comment_btn = document.querySelectorAll('main .tpl-moment .content li.item .tool .func .box .comment:not(.close)')
      const moment_comment_form = comment_container.querySelector('.comment_info_hidden input[name="comment_post_ID"]')
      moment_comment_btn.forEach((btn) => {
        btn.addEventListener('click', () => {

          if (!comment_container.classList.contains('active')) {
            comment_container.querySelector('.close-tip').style.animation = tip_animation
            comment_container.querySelector('.close-tip svg').style.animation = tip_icon_animation
          }
          // パネル
          if (btn.classList.contains('active') == comment_container.classList.contains('active')) {
            btn.classList.toggle('active')
            comment_container.classList.toggle('active')
          } else {
            moment_comment_btn.forEach((btn) => {
              btn.classList.remove('active')
            })
            btn.classList.add('active')
            if (!comment_container.classList.contains('active')) {
              comment_container.classList.add('active')
            }
          }
          if (window.innerWidth <= 650) {
            if (comment_container.classList.contains('active')) {
              document.body.style.overflowY = 'hidden'
            } else {
              document.body.style.overflowY = 'auto'
            }
          }

          // コメント
          let post_id = btn.getAttribute('data-id')
          let form_id = moment_comment_form.value
          if (post_id != form_id) {
            moment_comment_form.value = post_id
            comment_body.querySelector('ul').innerHTML = ''
            get_comments_func(post_id)
          }
        })
      })
    } else {
      get_comments_func(comment_container.id)
    }

    // コメントフォーム処理
    const comment_form = document.querySelector('main .post-comments .comment-response .commentForm')
    if (comment_form) {
      const comment_form_visitor = comment_form.querySelector('.visitor')
      const comment_form_visitor_btn = comment_form.querySelector('.form-tool .user-info.no-login')
      const comment_author = comment_form.querySelector('input[name="author"]')
      const comment_email = comment_form.querySelector('input[name="email"]')
      const comment_url = comment_form.querySelector('input[name="url"]')
      const comment_cookie = comment_form.querySelector('.comment-cookies input')
      let comment_body = document.querySelector('main .post-comments .comment-body')
      // 一番下に戻る
      const comment_go_bottom = document.querySelector('main .post-comments .comment-response .go-bottom')
      // Emoji
      const comment_emoji_btn = document.querySelector('main .post-comments .comment-response .emoji')
      const comment_emoji_box = document.querySelector('main .post-comments .comment-response .emoji-box')
      const comment_emoji_tab = comment_emoji_box.querySelectorAll('.tab ul li')
      const comment_emoji_list = comment_emoji_box.querySelectorAll('.list ul li')

      let lastScrollTop = 0
      comment_body.addEventListener('scroll', function() {
        if (this.scrollHeight - this.scrollTop > 1500) {
          let st = comment_body.scrollTop
          if (st > lastScrollTop){
            comment_go_bottom.classList.add('active')
            comment_go_bottom.addEventListener('click', function() {
              comment_body.scroll({
                top: comment_body.scrollHeight,
                behavior: 'smooth'
              })
            })
          } else {
            if (comment_go_bottom.classList.contains('active')) {
              comment_go_bottom.classList.remove('active')
            }
          }
          lastScrollTop = st
        } else {
          if (comment_go_bottom.classList.contains('active')) {
            comment_go_bottom.classList.remove('active')
          }
        }
      }, false)

      // Emoji
      comment_emoji_btn.addEventListener('click', () => {
        comment_emoji_box.classList.toggle('active')
      })
      // emoji list 1
      const emoji_1 = [
        '😀','😁','😂','🤣','😃','😄',
        '😅','😆','😉','😊','😋','😎',
        '😍','😘','🥰','😗','😙','🥲',
        '😚','🧐','🙂','🤗','🤩','🤔',
        '🫡','🤨','😐','😑','😶','🫥',
        '😶‍🌫️','🙄','😏','😣','😥','😮',
        '🤐','😯','😪','😫','🥱','😴',
        '😌','😛','😜','😝','🤤','😒',
        '😓','😔','😕','🫤','🙃','🫠',
        '🤑','😲','☹️','🙁','😖','😞',
        '😟','😤','😢','😭','😦','😧',
        '😨','😩','🤯','😬','😮‍💨','😰',
        '😱','🥵','🥶','😳','🤪','😵',
        '😵‍💫','🥴','😠','😡','🤬','😷',
        '🤒','🤕','🤢','🤮','🤧','😇',
        '🥳','🥸','🥺','🥹','🤠','🤡',
        '🤥','🤫','🤭','🫢','🫣','🤓',
        '💀','💩','👻'
      ]
      let emoji_list_1 = emoji_1.map(item => `<li class="item">${item}</li>`).join('')
      comment_emoji_list[0].innerHTML = '<ul class="emoji-1">'+emoji_list_1+'</ul>'
      
      // emoji list 2
      const emoji_2 = {
        '起きたばかり':'(*>.<*)',
        'え？驚き':'・ࡇ・',
        'パクパク':'´༥`',
        'ううう悲しい~':'ᵕ᷄≀ ̠ᵕ᷅ ',
        'べーんだ':'˙ϖ˙',
        '両手を上げて賛成！！':'ฅ ˘ฅ',
        '可愛い私が同意！！':'˙Ⱉ˙ฅ',
        '恥ずかしい':'˃̶͈ ˂̶͈ ',
        '顔を覆う':'ଲଇଉକ',
        '嫌い~！！':'ꈍ◡ꈍ',
        '私 超可愛い':'꒰⑅•ᴗ•⑅꒱',
        '安心して、任せて':'(•̤̀ᵕ•̤́๑)ᵒᵏᵎᵎᵎᵎ',
        '降参！':'ฅ(๑ ̀ㅅ ́๑)ฅ',
        '照れ':'꒰ᐢ⸝⸝•༝•⸝⸝ᐢ꒱',
        '私のビーム銃は本物だよ':'u (҂`･ｪ･´) <,︻╦̵̵̿╤─ ҉ - --',
        'ハートを受け取って':'(* ⁰̷̴͈꒨⁰̷̴͈)=͟͟͞͞➳♥',
        '泣いた':'˃̣̣̥᷄⌓˂̣̣̥᷅ ',
        '嬉しい！':'♪⸜(๑ ॑꒳ ॑๑)⸝♪✰',
        '照れ三連':'(๑ᵒ̴̶̷͈᷄ᗨᵒ̴̶̷͈᷅) (৹ᵒ̴̶̷᷄́ฅᵒ̴̶̷᷅৹) (⸝⸝⸝ᵒ̴̶̷̥́ ⌑ ᵒ̴̶̷̣̥̀⸝⸝⸝)',
        '筋肉見て':'ꉂ೭(˵¯̴͒ꇴ¯̴͒˵)౨',
        '毎朝起きたら自分を励ます！':'( ง⁼̴̀ω⁼̴́)ง⁼³₌₃',
        'イェイ！':'( ๑╹ ꇴ╹) ｸﾞｯ!',
        '全て見透かす':'✧(≖ ◡ ≖✿)',
        'ショック三連':'Σ(๑º㉨º๑ ) d(ŐдŐ๑) Σ(ﾟдﾟlll)',
        'やばい！ときめき':'(๑♡3♡๑)',
        '自信満々':'(｡˘•ε•˘｡)',
        '両手を貸して':'ฅ՞•ﻌ•՞ฅ',
        'はい、あげる':'ฅฅ*~',
        '見せて見せて':'(♡ര‿ര)',
        'つねるよ':'ԅ(≖‿≖ԅ)',
        '強気に見守る':'| ू•ૅω•́)ᵎᵎᵎ',
        'ふん、悔しい':'(｡•ˇ‸ˇ•｡)',
        '言うこと聞かない':'¦•ˇ₃ˇ•｡)',
        '私は羊さん...いや、豚羊さん':"°꒰๑'ꀾ'๑꒱°",
        '君だけに':'( ॢꈍ૩ꈍ) ॢḟ৹ʳᵧ৹ᵤ',
        '傘を持ってね':'☂꒰´•௰•`๑꒱…',
        '何？何て言った？？':'⚆_⚆？',
        '私は雲':'( ˃̶͈◡˂̶͈ ) hi!',
      }
      let emoji_list_box_2 = ''
      Object.entries(emoji_2).forEach(([key, value]) => {
        let emoji_list_2 = `<li class="item" title="${key}">${value}</li>`
        emoji_list_box_2 += emoji_list_2
      })
      comment_emoji_list[1].innerHTML = '<ul class="emoji-2">'+emoji_list_box_2+'</ul>'
      

      // Emojiメニュー切替
      comment_emoji_tab.forEach((tab, index) => {
        tab.addEventListener('click', () => {
          comment_emoji_tab.forEach((tab) => {
            tab.classList.remove('active')
          })
          tab.classList.add('active')
          comment_emoji_list.forEach((list) => {
            list.classList.remove('active')
          })
          comment_emoji_list[index].classList.add('active')
        })
      })

      // Emojiクリックでテキストボックスに挿入
      const comment_textarea = comment_form.querySelector('textarea')
      const comment_emoji_item = document.querySelectorAll('main .post-comments .comment-response .emoji-box .list ul li.item')
      comment_emoji_item.forEach((item) => {
        item.addEventListener('click', () => {
          // クリックした絵文字を取得
          if (item.hasAttribute('title')) {
            const emoji = item.textContent+'（'+item.getAttribute('title')+'）';
            // 絵文字をテキストエリアのカーソル位置に挿入する関数を呼び出し
            insertAtCursor(comment_textarea, emoji);
          } else {
            const emoji = item.textContent;
            // 絵文字をテキストエリアのカーソル位置に挿入する関数を呼び出し
            insertAtCursor(comment_textarea, emoji);
          }
        })
      })
      // カーソル位置にテキストを挿入する関数
      function insertAtCursor(textarea, text) {
        // テキストエリアにフォーカス
        textarea.focus();
        // document.execCommandメソッドをサポートしているか
        if (document.execCommand) {
          document.execCommand('insertText', false, text)
        } else { // サポートしていない場合は別の挿入方法を使用
          // カーソル位置を取得
          const startPos = textarea.selectionStart
          const endPos = textarea.selectionEnd
          // テキストエリアの内容を更新
          textarea.value = textarea.value.substring(0, startPos) + text + textarea.value.substring(endPos, textarea.value.length)
          // カーソル位置を調整
          textarea.selectionStart = startPos + text.length
          textarea.selectionEnd = startPos + text.length
        }
      }


      // コメント返信
      function atUser() {
        let comment_ul = document.querySelector('main .post-comments .comment-body ul')
        comment_ul.querySelectorAll('li').forEach((li) => {
          let atUserElement = li.querySelector('.avatar')
          if (atUserElement) {
            if (li.clickEvent) {
              atUserElement.removeEventListener('click', li.clickEvent);
            }
            li.clickEvent = function() {
              let comment_id = li.id
              let comment_user = li.querySelector('.reply-user .name').textContent
              // console.log(comment_user);
              comment_ul.querySelectorAll('li').forEach((item) => {
                item.querySelector('.avatar').classList.remove('at')
                item.classList.remove('at')
              })
              li.querySelector('.avatar').classList.add('at')
              li.classList.add('at')
              comment_form.querySelector('textarea').placeholder = '@' + comment_user
              comment_form.querySelector('textarea').focus()
              comment_form.querySelector('input[name="comment_parent"]').value = comment_id
              comment_form.querySelector('.reply .cancel').classList.add('active')
            }
            atUserElement.addEventListener('click', li.clickEvent);
          }
        })
      }

      // コメント返信クリア
      const comment_cancel = document.querySelector('main .post-comments .comment-response .commentForm .reply .cancel')
      const comment_form_placeholder = comment_form.querySelector('textarea').placeholder
      if (comment_cancel) {
        comment_cancel.addEventListener('click', function() {
          comment_ul.querySelectorAll('li').forEach((item) => {
            item.querySelector('.avatar').classList.remove('at')
            item.classList.remove('at')
          })
          comment_form.querySelector('textarea').placeholder = comment_form_placeholder
          comment_form.querySelector('input[name="comment_parent"]').value = '0'
          this.classList.remove('active')
        })
      }

      // テキストボックスの内容を検出
      const comment_submit_btn = comment_form.querySelector('button[type="submit"]')
      const comment_container = document.querySelector('main .post-comments .comment-container')

      if (comment_textarea.value.trim().length >= 1) {
        comment_submit_btn.removeAttribute('disabled')
      }

      let comment_textarea_listen = () => {
        if (comment_textarea.value.trim().length >= 1) {
          comment_submit_btn.removeAttribute('disabled')
        } else {
          comment_submit_btn.setAttribute('disabled', 'disabled')
        }
      }

      ['focus', 'blur', 'input'].forEach(event => {
        comment_textarea.addEventListener(event, comment_textarea_listen)
      })


      // テキストエリア自動拡張
      let auto_expand = () => {
        let comment_body = comment_container.querySelector('.comment-body');
        // comment_body.scrollTop = comment_body.scrollHeight;
        let lastComment = comment_body.lastElementChild;
        if (lastComment) {
          let scrollPosition = comment_body.scrollTop + comment_body.clientHeight;
          let nearBottom = comment_body.scrollHeight - scrollPosition < 200;
          if (nearBottom) {
            lastComment.scrollIntoView({ 
              behavior: 'smooth', 
              block: 'end', 
              inline: 'nearest' 
            });
          }
        }
      }
      // フォーカス時に展開
      comment_textarea.addEventListener('focus', () => {
        comment_container.classList.add('expand')
        comment_container.addEventListener('transitionend', auto_expand)
      })

      // 他の場所をクリックして閉じる
      document.addEventListener('click', (e) => {
        if (e.target != comment_textarea) {
          comment_container.removeEventListener('transitionend', auto_expand)
          if (document.activeElement === comment_textarea) {
            comment_container.classList.add('expand')
          } else if (comment_textarea.value.trim().length < 1) {
            if (!comment_form_visitor) {
              comment_container.classList.remove('expand')
            }
          }
        }
        // emoji
        if (!comment_emoji_btn.contains(e.target) && 
          comment_emoji_box.classList.contains('active') && 
          !comment_emoji_box.contains(e.target) &&
          !comment_textarea.contains(e.target)
        ) {
          comment_emoji_box.classList.remove('active')
        }
      })
      

      // 未ログインユーザー情報
      function isQQEmail(email) {
        let regex = /^[0-9]+@qq\.com$/
        if (regex.test(email)) {
          return 'https://q1.qlogo.cn/g?b=qq&nk=' + email.replace('@qq.com', '') + '&s=640'
        }
      }

      // メールアドレス形式をチェック
      function isValidEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
      }

      if (comment_form_visitor && comment_form_visitor_btn) {
        let comment_visitor_author = comment_form_visitor_btn.querySelector('.name')
        let comment_visitor_author_name = comment_visitor_author.textContent

        if (getCookie('visitor_author') && getCookie('visitor_email')) {
          comment_author.value = getCookie('visitor_author')
          comment_email.value = getCookie('visitor_email')
          comment_url.value = getCookie('visitor_url')
          comment_cookie.checked = true
          comment_form_visitor_btn.querySelector('.name').innerText = comment_author.value
          if (isQQEmail(getCookie('visitor_email'))) {
            comment_form_visitor_btn.querySelector('img').src = isQQEmail(getCookie('visitor_email'))
          }
        } else {
          comment_form.reset()
          comment_visitor_author.innerText = comment_visitor_author_name
        }
        
        comment_form_visitor_btn.addEventListener('click', () => {
          comment_form_visitor.classList.toggle('active')
          if (!comment_container.classList.contains('expand')) {
            comment_container.classList.add('expand')
          } else {
            if (!comment_form_visitor.classList.contains('active') && comment_textarea.value.trim().length < 1) {
              comment_container.classList.remove('expand')
            }
          }
        })

        // 他の場所をクリックして閉じる
        document.addEventListener('click', (e) => {
          if(!comment_form_visitor_btn.contains(e.target) && !comment_form_visitor.contains(e.target)) {
            comment_form_visitor.classList.remove('active')
            if (document.activeElement === comment_textarea) {
              comment_container.classList.add('expand')
            } else if (comment_textarea.value.trim().length < 1) {
              comment_container.classList.remove('expand')
            }
          }
        })

        // リアルタイムでユーザー名を更新
        let comment_author_listen = () => {
          if (comment_author.value.trim()) {
            comment_visitor_author.innerText = comment_author.value
          } else {
            comment_visitor_author.innerText = comment_visitor_author_name
          }
        }
        comment_author.addEventListener('focus', comment_author_listen)
        comment_author.addEventListener('blur', comment_author_listen)
        comment_author.addEventListener('input', comment_author_listen)
        
        // リアルタイムでメールアドレス情報を更新
        let comment_email_listen = () => {
          if (isQQEmail(comment_email.value)) {
            comment_form_visitor_btn.querySelector('img').src = isQQEmail(comment_email.value)
          }
        }
        comment_email.addEventListener('focus', comment_email_listen)
        comment_email.addEventListener('blur', comment_email_listen)
        comment_email.addEventListener('input', comment_email_listen)
      }

      // コメントを送信
      const comment_form_reply = comment_form.querySelector('.reply')
      const comment_form_reply_icon = comment_form.querySelector('.reply button.send .icon use')
      const comment_form_reply_send = comment_form_reply_icon.getAttribute('xlink:href')

      // コメントを追加
      function add_comment(commentFormData) {
        // console.log(Array.from(formData.entries()))
        comment_form_reply_icon.setAttribute('xlink:href', '#icon-loading')
        comment_form_reply.classList.add('loading')
        comment_submit_btn.setAttribute('disabled', 'disabled')

        // エラー戻り
        function action_back(message) {
          toast(message)
          comment_form_reply.classList.remove('loading')
          comment_form_reply_icon.setAttribute('xlink:href', comment_form_reply_send)
          comment_submit_btn.removeAttribute('disabled')
        }

        // リクエストを送信
        fetch('/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: commentFormData
        })
        .then(response => response.text())
        .then(response => {

          if (response == 'no_author') {
            action_back('ニックネームを入力してください！')
            return
          } else if (response == 'short_author') {
            action_back('ニックネームが短すぎます！')
            return
          } else if (response == 'no_email') {
            action_back('メールアドレスを入力してください！')
            return
          } else if (response == 'no_comment') {
            action_back('コメント内容を入力してください！')
            return
          } else if (response == 'short_comment') {
            action_back('コメント内容が短すぎます！')
            return
          } else if (response == 'exist') {
            action_back('同じコメントを既に投稿しています！')
            return
          } else if (response == 'flood') {
            action_back('コメントの送信が速すぎます。後でもう一度お試しください！')
            return
          } else if (response == 'no_login') {
            action_back('ログイン後にコメントを投稿してください！')
            return
          } else if (response == 'closed') {
            action_back('コメントは閉じられています！')
            return
          } else if (response == 'error') {
            action_back('コメントの投稿に失敗しました。後でもう一度お試しください！')
            return
          }

          toast('コメントを投稿しました！')
          response = JSON.parse(response)
          // console.log(response);
          // return
          comment_cancel.classList.remove('active')
          comment_ul.querySelectorAll('li').forEach((li) => {
            li.querySelector('.avatar').classList.remove('at')
            li.classList.remove('at')
          })
          comment_form.querySelector('textarea').placeholder = comment_form_placeholder
          comment_form.querySelector('textarea').value = ''
          comment_form.querySelector('input[name="comment_parent"]').value = '0'
          comment_container.classList.remove('expand')
          comment_form_reply.classList.remove('loading')
          comment_form_reply_icon.setAttribute('xlink:href', comment_form_reply_send)
          comment_submit_btn.setAttribute('disabled', 'disabled')
          comment_ul.insertAdjacentHTML('beforeend', response.comment)

          if (response.approved) {
            let post_comment =  document.querySelector('main .post-tool .case .box .comments')
            let moment_comment = document.querySelectorAll('main .tpl-moment .content li.item .tool .comment:not(.close)')
            if (post_comment) {
              let post_id =  post_comment.getAttribute('data-id')
              let comment_count = post_comment.querySelector('p span')
              if (post_id == comment_container.id) {
                comment_count.innerText = Number(comment_count.textContent) + 1
              }
            }

            let moment_comment_form = comment_container.querySelector('.comment_info_hidden input[name="comment_post_ID"]')
            moment_comment.forEach((btn) => {
              if (btn.getAttribute('data-id') == moment_comment_form.value) {
                let comment_count = btn.querySelector('span')
                comment_count.innerText = Number(comment_count.textContent) + 1
              }
            })
          }
          let comment_date = comment_ul.querySelectorAll('.datetime')
          let old_date
          comment_date.forEach((date) => {
            new_date = date.textContent
            if (old_date == new_date) {
              date.remove()
            } else {
              old_date = new_date
            }
          })

          if (response.avatar && comment_form_visitor_btn) {
            comment_form_visitor_btn.querySelector('img').src = response.avatar
          }
        })
        .catch((error) => {
          // console.log(error);
          toast('コメントの投稿に失敗しました。後でもう一度お試しください！')
          comment_form_reply_icon.setAttribute('xlink:href', comment_form_reply_send)
          comment_form_reply.classList.remove('loading')
          comment_submit_btn.removeAttribute('disabled')
        })
      }
      
      comment_form.addEventListener('submit', (e) => {
        e.preventDefault()

        
        if (comment_form_visitor_btn && comment_form_visitor_btn.hasAttribute('href')) {
          toast('ログイン後にコメントを投稿してください！')
          return
        }
        // comment_submit_btn.setAttribute('disabled', 'disabled')

        // ユーザー情報をチェック
        if (!comment_author.value.trim()) {
          comment_form_visitor.classList.add('active')
          setTimeout(() => {
            comment_author.focus()
          }, 50)
          return
        } else if (!comment_email.value.trim() || !isValidEmail(comment_email.value)) {
          comment_form_visitor.classList.add('active')
          setTimeout(() => {
            comment_email.focus()
          }, 50)
          return
        }

        // observer_sendを作成
        let commentFormData = new FormData(e.target)
        commentFormData.append('action', 'reply_comment')
        commentFormData.append('timezone', Intl.DateTimeFormat().resolvedOptions().timeZone)
        
        let comment_body = document.querySelector('main .post-comments .comment-body')
        let scrollHeight = comment_body.scrollHeight
        let observer_send = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
              let comment_body = document.querySelector('main .post-comments .comment-body')
              if (comment_body.scrollHeight > scrollHeight) {
                comment_body.scrollTop = comment_body.scrollHeight
              }
              atUser() //コメント返信
            }
          })
        })
        // observer_sendを実行
        observer_send.observe(comment_body, { childList: true, subtree: true })
        add_comment(commentFormData)

        function setCookie(name, value, days) {
          let date = new Date()
          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000))
          let expires = '; expires=' + date.toUTCString()
          document.cookie = name + '=' + value + expires + '; path=/'
        }
        function deleteCookie(name) {
          document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'
        }
        
        // ユーザー情報を保存
        if (comment_cookie && comment_cookie.checked) {
          setCookie('visitor_author', comment_author.value, 30)
          setCookie('visitor_email', comment_email.value, 30)
          setCookie('visitor_url', comment_url.value, 30)
        } else {
          if (getCookie('visitor_author') || getCookie('visitor_email') || getCookie('visitor_url') || deleteCookie('visitor_avatar')) {
            deleteCookie('visitor_author')
            deleteCookie('visitor_email')
            deleteCookie('visitor_url')
          }
        }
      })
    }
  }
  
  // シェア処理
  const share = document.querySelector('main .post-tool .share a')
  if (share) {
    share.addEventListener('click', function(e) {
      e.preventDefault()
      if (navigator.clipboard) {
        // navigator.clipboard APIを使用
        navigator.clipboard.writeText(window.location.href)
          .then(() => {
            toast('リンクをコピーしました！当サイトの著作権規約をご確認ください！')
          })
          .catch(() => {
            toast('リンクのコピーに失敗しました！')
          })
      } else {
        // 代替方法を使用
        let textarea = document.createElement('textarea')
        textarea.textContent = window.location.href
        document.body.appendChild(textarea)
        textarea.select()
        try {
          document.execCommand('copy')
          toast('リンクをコピーしました！当サイトの著作権規約をご確認ください！')
        } catch (err) {
          toast('リンクのコピーに失敗しました！')
        } finally {
          document.body.removeChild(textarea)
        }
      }
    })
  }

  // トップに戻る処理
  const backtop = document.querySelector('main .post-tool .backtop')
  if (backtop) {
    backtop.addEventListener('click', () => {
      window.scrollTo({
        top: 0, 
        behavior: 'smooth'
      })
    })
  }
}

// 記事コンテンツノードにclassを追加 / aタグにリンクアイコンを追加
const nodesEdit = () => {
  let nodes = document.querySelectorAll('main .post-content .content > *')
  nodes.forEach(function(node) {
    node.classList.add('text-reveal')

    // 画像かどうかを判定
    // if (node.classList.contains('wp-block-image')) {
    //   node.classList.add('text-reveal')
    // } else {
    //   node.classList.add('text-reveal')
    // }
  })

  let nodes_a = document.querySelectorAll(`
    main .post-content .content p a:not([data-fancybox]), 
    main .post-content .content ul a:not([data-fancybox]), 
    main .post-content .content table a:not([data-fancybox]),
    main .post-content .content cite a:not([data-fancybox]),
    main .post-content .content .wp-element-caption a:not([data-fancybox])
  `)
  nodes_a.forEach((node_a) => {
    node_a.innerHTML = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-link-circle"></use></svg>' + node_a.innerHTML
  })

  // URLを持つすべてのspanタグを置換
  const spans = document.querySelectorAll('span[data-href]')
  if (spans) {
    spans.forEach((span) => {
      // console.log(span.innerHTML)
      let a = document.createElement('a')
      a.classList.add('url')
      a.href = span.getAttribute('data-href')
      a.innerHTML = span.innerHTML
      span.parentNode.replaceChild(a, span)
    })
  }

  // 高さ変更後の要素にスクロールエフェクトをリセット
  const post_content = document.querySelector('main .post-content .content')
  if (post_content) {
    const details = post_content.querySelectorAll('.wp-block-details summary') // すべての詳細要素
    details.forEach((el) => {
      el.addEventListener('click', function() {
        sr.delegate()
      })
    })
  }
}


// 画像遅延読み込み
const lazyLoad = (loading_img=false) => {
  if (loading_img) {
    const wp_block_images = document.querySelectorAll('main .post-content .content img, main .tpl-moment .moment-text img')
    wp_block_images.forEach((img) => {
      img.setAttribute('lazyload', '')
      img.setAttribute('data-src', img.src)
      img.src = loading_img
    })

    const lazyload_images = document.querySelectorAll('img[lazyload]')
    lazyload_images.forEach((img) => {
      let retryCount = 0; // リトライ回数を初期化
      const maxRetry = 3; // 最大リトライ回数
      // console.log(img.getAttribute('data-src'))
      let temp_img = new Image()
      temp_img.src = img.getAttribute('data-src')
      temp_img.onload = () => {
        img.src = temp_img.src
        // console.log('loaded');
      }
      temp_img.onerror = () => {
        if (retryCount < maxRetry) {
          setTimeout(() => { // 少し遅延して再試行
            temp_img.src = img.getAttribute('data-src') + "&retry=" + retryCount; // キャッシュ問題を避けるためURLを変更
          }, 1000 * retryCount); // リトライ間隔を徐々に増加
          retryCount++;
          // console.log(`retrying... attempt ${retryCount}`);
        } else {
          // console.log('error loading image after retries');
        }
      };
    })
  }
}


// 图片预览
const imagePreview = (loading_img=false) => {
  // 文章
  const wp_block_images = document.querySelectorAll('main .post-content .content img')
  wp_block_images.forEach(function(image) {
    if (image.parentNode.tagName === 'A') {
      image.parentNode.setAttribute('data-fancybox', 'gallery')
      if (loading_img) {
        image.parentNode.href = image.getAttribute('data-src')
      } else {
        image.parentNode.href = image.src
      }
      let wp_block_image = image.parentNode.parentNode
      let figcaption = wp_block_image.querySelector('figcaption')
      if (figcaption) {
        image.parentNode.setAttribute('data-caption', figcaption.textContent)
      }
      
    } else {
      let wp_block_image = image.parentNode
      let a = document.createElement('a')
      a.setAttribute('data-fancybox', 'gallery')
      if (loading_img) {
        a.setAttribute('href', image.getAttribute('data-src'))
      } else {
        a.setAttribute('href', image.getAttribute('src'))
      }
      a.appendChild(image)
      // aタグをfigcaption要素の後に挿入
      let figcaption = wp_block_image.querySelector('figcaption')
      if (figcaption) {
        a.setAttribute('data-caption', figcaption.textContent)
        wp_block_image.appendChild(a)
        wp_block_image.appendChild(figcaption)
      } else {
        wp_block_image.appendChild(a)
      }
    }
  })

  // 画展图片预览
  const gallery = document.querySelector('.content-gallery')
  if (gallery) {
    let gallery_p = gallery.querySelectorAll('p')
    gallery_p.forEach((p) => {
      let gallery_img = p.querySelectorAll('img')
      gallery_img.forEach((image) => {
        if (image.parentNode.tagName === 'A') {
          image.parentNode.setAttribute('data-fancybox', 'gallery')
          if (loading_img) {
            image.parentNode.href = image.getAttribute('data-src')
          } else {
            image.parentNode.href = image.src
          }
        } else {
          let a = document.createElement('a')
          a.setAttribute('data-fancybox', 'gallery')
          if (loading_img) {
            a.setAttribute('href', image.getAttribute('data-src'))
          } else {
            a.setAttribute('href', image.getAttribute('src'))
          }
          a.appendChild(image)
          p.appendChild(a)
        }
      })
      if (gallery_img.length > 1) {
        let gallery_box = document.createElement('div')
        gallery_box.className = 'gallery-box'
        gallery_img.forEach((image) => {
          gallery_box.appendChild(image.parentNode)
        })
        p.appendChild(gallery_box)
      }
    })
  }

  $args = {
    hideScrollbar: true,
    Carousel: {
      infinite: true,
    },
    placeFocusBack: false,
    Hash: false,
    Toolbar: {
      display: {
        left: ["infobar"],
        middle: [
          "zoomIn",
          "zoomOut",
          "toggle1to1",
          "rotateCCW",
          "rotateCW",
          "flipX",
          "flipY",
        ],
        right: ["slideshow", "thumbs", "close"],
      },
    },
    l10n: {
      ZOOMIN: "拡大",
      ZOOMOUT: "縮小",
      TOGGLE1TO1:"ズームレベル切替",
      ROTATECCW: "反時計回りに回転",
      ROTATECW: "時計回りに回転",
      FLIPX: "左右反転",
      FLIPY: "上下反転",
      TOGGLE_THUMBS:"サムネイル切替",
      NEXT: "次へ",
      PREV: "前へ",
      IMAGE_ERROR: "画像が見つかりません",
      TOGGLE_SLIDESHOW: "スライドショー",
      TOGGLE_FULLSCREEN: "フルスクリーン切替",
      DOWNLOAD: "ダウンロード",
      CLOSE: "閉じる"
    },
  }

  // バインド
  Fancybox.bind('[data-fancybox="gallery"]', $args)


  // moments
  const moments_text = document.querySelectorAll('main .tpl-moment .content li.item .moment-text')
  moments_text.forEach(function(text) {
    let gallery = text.id
    let moments_img = text.querySelectorAll('img')
    moments_img.forEach(function(image) {
      if (image.parentNode.tagName === 'A') {
        image.parentNode.setAttribute('data-fancybox', gallery)
        if (loading_img) {
          image.parentNode.href = image.getAttribute('data-src')
        } else {
          image.parentNode.href = image.src
        }
        let wp_block_image = image.parentNode.parentNode
        let figcaption = wp_block_image.querySelector('figcaption')
        if (figcaption) {
          image.parentNode.setAttribute('data-caption', figcaption.textContent)
        }
        
      } else {
        let wp_block_image = image.parentNode
        let a = document.createElement('a')
        a.setAttribute('data-fancybox', gallery)
        if (loading_img) {
          a.setAttribute('href', image.getAttribute('data-src'))
        } else {
          a.setAttribute('href', image.getAttribute('src'))
        }
        a.appendChild(image)
        // aタグをfigcaption要素の後に挿入
        let figcaption = wp_block_image.querySelector('figcaption')
        if (figcaption) {
          a.setAttribute('data-caption', figcaption.textContent)
          wp_block_image.appendChild(a)
          wp_block_image.appendChild(figcaption)
        } else {
          wp_block_image.appendChild(a)
        }
      }
    })
    Fancybox.bind(`[data-fancybox="${gallery}"]`, $args)
  })
  

  // $args.groupAll = true
  // Fancybox.bind('#gallery_678 a', $args)
  // const moments_li = document.querySelectorAll('main .tpl-moment .content li .text')
  // if (moments_li.length > 0) {
  //   $args.groupAll = true
  //   moments_li.forEach(function(li) {
  //     Fancybox.bind('#'+li.id+' a', $args)
  //   })
  // }
  
}


// コードブロックハイライト
const highLight = () => {
  // console.log(Prism)
  
  const pres = document.querySelectorAll('main .post-content .content pre')
  pres.forEach((pre) => {
    const code = pre.querySelector('code')
    if (!code) return
    console.log()
    const lang_class = [...pre.classList].find(className => className.startsWith('lang-'))
    if (lang_class) {
      const prism_lang = lang_class.replace('lang-', 'language-')
      code.classList.add(prism_lang)
    } else {
      if (typeof hljs !== 'undefined') {
        const lang = hljs.highlightAuto(code.textContent).language
        code.classList.add(lang ? 'language-' + lang : 'language-text')
      }
    }
  })

  if (typeof Prism !== 'undefined') {
    Prism.highlightAll()
  }
}


// アーカイブページ
const archiveJs = () => {

  // カテゴリタイトル
  const cate_info = document.querySelectorAll('main .archive-category a .cate_info')
  const cate_info_p = document.querySelector('main .archive-category .cate_info p')
  const archive_cate = document.querySelector('main .archive-category')
  // console.log(cate_info_p)
  if (cate_info_p != null && window.getComputedStyle(cate_info_p).display != 'none' && !archive_cate.classList.contains('post-category-cover')) {
    cate_info.forEach(function(box) {
      let p_height = box.querySelector('p').getBoundingClientRect().height.toFixed(2)
      // console.log(p_height)
      let styles = window.getComputedStyle(box.querySelector('p'))
      let marginBottom = parseFloat(styles.marginBottom)
      // console.log(marginBottom)
      p_height = parseFloat(p_height) + marginBottom
      // console.log(p_height)
      box.style.transform = 'translateY(' + p_height + 'px)'
  
      box.parentNode.addEventListener('mouseenter', function() {
        this.classList.add('hover')
      })
      box.parentNode.addEventListener('mouseleave', function() {
        this.classList.remove('hover')
      })
    })
  }
  

  // すべての記事折りたたみパネル
  const post_h2 = document.querySelectorAll('main .post-all .post-year h2')
  post_h2.forEach(function(h2, index) {
    // console.log(h2.getBoundingClientRect().height.toFixed(2))
    let h2_height = h2.getBoundingClientRect().height.toFixed(2)
    let h2_parent = h2.parentNode
    let h2_parent_height = h2_parent.getBoundingClientRect().height.toFixed(2)
    // console.log(h2)
    h2_parent.style.height = h2_height + 'px'
    if(index === 0) {
      h2_parent.style.height = h2_parent_height + 'px'
      h2_parent.classList.add('active')
    }
    h2.addEventListener('click', function() {
      if(!h2_parent.classList.contains('active')) {
        h2_parent.classList.add('active')
        h2_parent.style.height = h2_parent_height + 'px'
      } else {
        h2_parent.classList.remove('active')
        h2_parent.style.height = h2_height + 'px'
      }
    })
  })

}

// スクロールイベント/トップに戻る
const scrollEvent = () => {
  let func_box = document.querySelector('main .post-tool .case .box')
  let nav = document.querySelector('header nav')
  let position = window.scrollY

  // 即座に判定
  if (position >= 200) {
    nav.classList.add('active')
  } else {
    nav.classList.remove('active')
  }
  
  // スクロール判定
  window.addEventListener('scroll', () => {
    let scroll = window.scrollY
    if(position >= 150) {
      if(scroll > position) {
        nav.classList.add('active')
        if (func_box) {
          func_box.classList.add('hidden')
        }
      } else {
        nav.classList.remove('active')
        if (func_box) {
          func_box.classList.remove('hidden')
        }
      }
    }
    position = scroll
  })

  // トップに戻る
  let nav_tool = document.querySelector('header .nav-title .backtop')
  // let nav_p = document.querySelector('header .nav-title p')

  nav_tool.addEventListener('click', () => {
    window.scrollTo({
      top: 0, 
      behavior: 'smooth'
    })
    // remove()
  })

  nav_tool.addEventListener('mouseenter', function() {
    // add()
  })

  nav_tool.addEventListener('mouseleave', function() {
    // remove()
  })

  // function add() {
  //   nav_tool.classList.add('active')
  //   nav_p.classList.add('active')
  // }

  // function remove() {
  //   nav_tool.classList.remove('active')
  //   nav_p.classList.remove('active')
  // }


  // home ナビゲーションバーの色変更
  const home_nav = document.querySelector('main')  
  if (home_nav) {
    function homeScroll() {
      let scroll = window.scrollY
      if (scroll >= 50) {
        if (home_nav.classList.contains('active')) {
          home_nav.classList.remove('active')
        }
      } else {
        home_nav.classList.add('active')
      }
    }
    homeScroll()
    window.addEventListener('scroll', () => {
      homeScroll()
    })
  }

}


// 検索
function searchFunc() {
  // pc
  const searchform = document.querySelector('header .search')
  const search_input = searchform.querySelector('input')
  const action = searchform.getAttribute('action')
  const nav = document.querySelector('header .nav-menu')
  const nav_menu_btn = nav.querySelector('.left .menu-btn')
  const nav_title = nav.querySelector('.left .title')
  const nav_user = nav.querySelector('.user')
  const nav_toc = nav.querySelector('.toc-btn')

  // pc
  searchform.addEventListener('click', function(e) {
    if(!this.classList.contains('active')) {
      e.preventDefault()
      this.setAttribute('action', action + search_input.value)
      this.classList.add('active')
      nav_menu_btn.classList.add('hidden')
      nav_title.classList.add('hidden')
      nav_user.classList.add('hidden')
      if (nav_toc) {
        nav_toc.classList.add('hidden')
      }
      setTimeout(()=>{
        search_input.focus()
      }, 100)
    }
  })
  search_input.addEventListener('input', function() {
    searchform.setAttribute('action', action + this.value)
  })

  document.addEventListener('click', (e) => {
    if(searchform.classList.contains('active') && !searchform.contains(e.target)) {
      searchform.classList.remove('active')
      setTimeout(()=>{
        nav_menu_btn.classList.remove('hidden')
        nav_title.classList.remove('hidden')
        nav_user.classList.remove('hidden')
        if (nav_toc) {
          nav_toc.classList.remove('hidden')
        }
      }, 300)
    }
  })
}


// ランダム画像処理
// const randomCover = (apiUrl) => {
//   let imgElement = document.querySelectorAll('img[data-cover="false"]')

//   imgElement.forEach(function(img) {
//     let timestamp = new Date().getTime()
//     let apiUrlWithTimestamp = apiUrl + '?timestamp=' + timestamp

//     fetch(apiUrlWithTimestamp)
//     .then(response => {
//       let imageUrl = response.url
//       img.src = imageUrl
//       img.style.display = 'block'
//     })
//     .catch(error => console.error('Error fetching random image:', error))
//   })
// }


// 記事タイトル目次Toc
const titleToc = () => {
  const toc = document.querySelector('.toc-container')
  
  const post_title = document.querySelectorAll(`
    main .single.post-content .content h1,
    main .single.post-content .content h2,
    main .single.post-content .content h3,
    main .single.post-content .content h4,
    main .single.post-content .content h5,
    main .single.post-content .content h6
  `) 
  
  if (post_title.length > 1) {
    
    const toc_btn = document.querySelector('header .nav-menu .toc-btn')
    if (toc_btn) {
      toc_btn.classList.add('active')
      toc_btn.innerHTML = '<svg class="icon" aria-hidden="true"><use xlink:href="#icon-list-checkbox"></use></svg>'
    }
    const box = document.createElement('div')
    const ul = document.createElement('ul')
    const slider = document.createElement('div')
    const overflow = document.createElement('div')
    box.classList.add('toc')
    slider.classList.add('slider')
    overflow.classList.add('overflow')

    let h2 = 1
    let h3 = 1
    let h4 = 1
    post_title.forEach((title, i) => {
      title.id = 'title-'+(i+1)
      let li = document.createElement('li')
      li.classList.add(title.tagName.toLowerCase())
      if (title.tagName == 'H1' ||title.tagName == 'H2') {
        li.innerHTML = `<a target href="#title-${i+1}">${h2}. ${title.textContent}</a>`
        h2++
        h3 = 1
      } else if (title.tagName == 'H3'){
        li.innerHTML = `<a target href="#title-${i+1}">${h2-1}.${h3}. ${title.textContent}</a>`
        h3++
        h4 = 1
      } else if (title.tagName == 'H4' || title.tagName == 'H5' || title.tagName == 'H6'){
        li.innerHTML = `<a target href="#title-${i+1}">${h2-1}.${h3-1}.${h4}. ${title.textContent}</a>`
        h4++
      }
      
      // li.innerHTML = `<a href="#title-${i+1}">${title.textContent}</a>`
      ul.appendChild(li)
    })
    ul.appendChild(slider)
    overflow.appendChild(ul)
    box.appendChild(overflow)
    toc.appendChild(box)

    const toc_li = document.querySelectorAll('main .post-content .toc-container .toc li')
    const last_content = document.querySelector('main .post-content .content')
    let latest = -1
    
    window.addEventListener('scroll', () => {
      let first_title_top = Math.round(post_title[0].getBoundingClientRect().top)
      let last_content_bottom = Math.round(last_content.getBoundingClientRect().bottom)
  
      post_title.forEach((title, i) => {
        let title_top = Math.round(title.getBoundingClientRect().top)
        if (title_top <= 120) {
          latest = i
        }
        if (first_title_top > 120) {
          latest = -1
        }
        if (last_content_bottom < 60) {
          latest = -1
        }
      })

      if (latest != -1) {
        slider.style.height = toc_li[latest].getBoundingClientRect().height.toFixed(2)+'px'
        slider.style.transform = 'translateY('+toc_li[latest].offsetTop+'px)'
        toc_li.forEach((li) => {
          li.classList.remove('active')
        })
        toc_li[latest].classList.add('active')
      } else {
        toc_li.forEach((li) => {
          li.classList.remove('active')
        })
        slider.style.height = '0px'
      }
    })

    toc_li.forEach((li, i) => {
      li.addEventListener('click', function() {
        slider.style.height = this.getBoundingClientRect().height.toFixed(2)+'px'
        slider.style.transform = 'translateY('+this.offsetTop+'px)'
        // console.log(Math.round(post_title[i].getBoundingClientRect().top) - 110)
        // let offset = post_title[i].getBoundingClientRect().top + window.scrollY
        // window.scrollTo({ top: offset - 110, behavior: 'smooth' })
        toc_li.forEach((li) => {
          li.classList.remove('active')
        })
        this.classList.add('active')
      })
    })

    toc_btn.addEventListener('click', () => {
      if(!toc.classList.contains('active')) {
        toc_btn.classList.add('on')
        toc.classList.add('active')
        let header = document.querySelector('header')
        let overlay = document.querySelector('.overlay')

        let comment_box = document.querySelector('main .post-comments .comment-container')
        let comment_btn = document.querySelector('main .post-tool .comments a')
        if (comment_box && comment_box.classList.contains('active')) {
          comment_btn.classList.remove('active') //コメントパネルを閉じる
          comment_box.classList.remove('active') //コメントパネルを閉じる
        }

        if(header.classList.contains('active')) {
          header.classList.remove('active')
          overlay.classList.remove('active')
          document.body.style.overflowY = 'auto'
        }
        // document.body.style.overflowY = 'hidden'
      } else {
        toc_btn.classList.remove('on')
        toc.classList.remove('active')
        // document.body.style.overflowY = 'auto'
      }
    })

    document.addEventListener('click', (e) => {
      if(!toc_btn.contains(e.target) && !toc.contains(e.target)) {
        toc_btn.classList.remove('on')
        toc.classList.remove('active')
      }
    })


    // スワイプで閉じる
    let startX = 0 // 指がタッチを開始した位置を記録

    // touchstart イベントを監聴
    toc.addEventListener('touchstart', function(e) {
      startX = e.touches[0].clientX; // 指がタッチを開始した位置を記録
    })

    // touchmove イベントを監聴
    toc.addEventListener('touchmove', function(e) {
      let moveX = e.touches[0].clientX // 指が移動した位置を記録
      let distance = moveX - startX // 指がスワイプした距離を計算
      if (distance > 100) {
        // classを削除
        toc_btn.classList.remove('on')
        toc.classList.remove('active')
      }
    });


  } else if (toc) {
    toc.style.display = 'none'
  }
}


// 友達リンク自己申請
const friendsSup = () => {
  const link_form = document.querySelector('.linkForm')
  if (link_form) {
    // パネルスイッチ
    const link_form_btn = document.querySelector('main .tpl-friend .link-sup .apply')
    const link_form = document.querySelector('main .tpl-friend .link-sup .link-form')
    link_form_btn.addEventListener('click', () => {
      link_form.classList.add('active')
    })
    link_form.addEventListener('click', (e) => {
      if (e.target == link_form && !e.target.classList.contains('linkForm')) {
        link_form.classList.remove('active')
      }
    })

    // カテゴリ選択
    let category
    const select = document.querySelector('main .tpl-friend .linkForm .select')
    const select_input = document.querySelector('main .tpl-friend .linkForm .select input')
    const select_ul = document.querySelector('main .tpl-friend .linkForm .select .ul-box')
    const select_li = document.querySelectorAll('main .tpl-friend .linkForm .select ul li')
    // console.log(select_ul)
    if (select_ul) {
      select.addEventListener('click', (e) => {
        e.preventDefault()
        if (e.target != select_ul && !select_ul.contains(e.target)) {
          select.classList.add('active')
          select_input.disabled = true
        }
      })
      document.addEventListener('click', (e) => {
        if (!select.contains(e.target)) {
          select.classList.remove('active')
          select_input.disabled = false
        }
      })
      select_li.forEach((li) => {
        li.addEventListener('click', () => {
          select_li.forEach((li) => {
            li.classList.remove('active')
          })
          li.classList.add('active')
          category = li.getAttribute('data-value')
          select_input.setAttribute('placeholder', li.textContent)
          select.classList.remove('active')
          select_input.disabled = false
        }) 
      })
    }

    // リンクフォームを送信
    let submit = link_form.querySelector('.submit')
    let submit_text = submit.querySelector('span')
    let submit_value = submit_text.textContent
    let submit_icon = link_form.querySelector('.submit svg use')
    let submit_icon_send = submit_icon.getAttribute('xlink:href')
    let num1 = document.querySelector('.linkForm .num1')
    let num2 = document.querySelector('.linkForm .num2')
    let num = 0
    let nowTime
    let endTime

    function random_verify() {
      let random_num1 = Math.floor(Math.random() * 20) + 1
      let random_num2 = Math.floor(Math.random() * 20) + 1
      num = random_num1 + random_num2
      num1.innerText = random_num1
      num2.innerText = random_num2
    }
    random_verify()

    function loading(nowS, loading=false) {
      if (loading) {
        submit.setAttribute('disabled', 'disabled')
        submit_text.innerText = 'クールダウン中... ' + nowS + '秒'
        submit_icon.setAttribute('xlink:href', '#icon-loading')
        submit.classList.add('loading')
      } else {
        submit.removeAttribute('disabled')
        submit_text.innerText = submit_value
        submit_icon.setAttribute('xlink:href', submit_icon_send)
        submit.classList.remove('loading')
      }
    }
    function submit_status(loading=false) {
      if (loading) {
        submit_icon.setAttribute('xlink:href', '#icon-loading')
        submit.classList.add('loading')
        submit.setAttribute('disabled', 'disabled')
      } else {
        submit_icon.setAttribute('xlink:href', submit_icon_send)
        submit.classList.remove('loading')
        submit.removeAttribute('disabled')
      }
    }

    link_form.addEventListener('submit', (e) => {
      e.preventDefault()
      
      if (select.querySelector('input').getAttribute('data-cate') == 'none') {
        toast('カテゴリがありません。管理者に連絡して追加してから送信してください！')
        return
      }
      
      // ロボット検証
      let result = link_form.querySelector('input[name="verify"]')
      if (result.value == num) {
        if (category == null && select_li) {
          select.classList.add('active')
          return
        }
        let formData = new FormData(e.target)
        formData.append('action', 'my_friends')
        formData.append('category', category)

        submit_status(true)

        fetch('/wp-admin/admin-ajax.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(response => {
          if (response == 'success') {
            toast('送信成功！審査をお待ちください！')
            result.value = ''
            random_verify()

            nowTime = new Date().getTime()
            endTime = nowTime + 1000 * 60
            localStorage.setItem('endTime', endTime)
            submit_countdown()
          } else if (response == 'exists') {
            toast('リンクは既に存在します。重複送信しないでください！')
            submit_status(false)
          } else if (response == 'closed') {
            toast('友達リンクの自己申請機能は閉じられています！')
            submit_status(false)
          }
        })
        .catch(() => {
          toast('送信に失敗しました。管理者に連絡してください！')
          submit_status(false)
        })
      } else {
        random_verify()
      }
    })

    // 送信頻度を制限
    submit_countdown()
    function submit_countdown() {
      let nowS = localStorage.getItem('endTime') - new Date().getTime()
      nowS = Math.ceil(nowS / 1000)
      // localStorage.setItem('submitValue', submit.value)

      let timer = setInterval(() => {
        let nowS = localStorage.getItem('endTime') - new Date().getTime()
        nowS = Math.ceil(nowS / 1000)
        // console.log(nowS)
        if (nowS > 0) {
          loading(nowS, true)
        } else {
          loading(nowS, false)
          clearInterval(timer)
        }
      }, 1000)
  
      if (nowS > 0) {
        loading(nowS, true)
      } else {
        loading(nowS, false)
        clearInterval(timer)
      }
    }
  }
}


// 通知系
const toast = (msg, stat = false) => {
  if (msg == null) {
    return
  }

  let toast = document.createElement('div')
  toast.classList.add('toast')
  toast.innerHTML = `
    <div class="mask"></div>
    <div class="msg">${msg}</div>
  `
  // console.log(toast)
  document.body.appendChild(toast)
  
  setTimeout(() => {
    toast.classList.add('active','runing')
  }, 10)

  setTimeout(() => {
    toast.classList.remove('runing')
  }, 4000)

  setTimeout(() => {
    toast.remove()
  }, 4500)

  // マウスダブルクリックまたは上スワイプで非表示
  toast.addEventListener('dblclick', function(e) {
    toast.classList.remove('runing')
  }, false)
  let isMouseDown = false
  toast.addEventListener('mousedown', function(e) {
    startY = e.clientY
    isMouseDown = true
  }, false)
  toast.addEventListener('mousemove', function(e) {
    if (!isMouseDown) return
    let endY = e.clientY
    let distance = startY - endY
    if (distance > 20) {
      toast.classList.remove('runing')
      isMouseDown = false // 複数回トリガーを防止
    }
  }, false)
  toast.addEventListener('mouseup', function(e) {
    isMouseDown = false
  }, false)

  // 指で上スワイプして非表示
  let startY
  toast.addEventListener('touchstart', function(e) {
    startY = e.touches[0].clientY
  }, false)
  toast.addEventListener('touchmove', function(e) {
    e.preventDefault()
    let endY = e.changedTouches[0].clientY
    let distance = startY - endY
    if (distance > 30) {
      // console.log('上スワイプが30ピクセルを超えました')
      toast.classList.remove('runing')
    }
  }, {passive: false})
}


// ローディングバー
function loadBar(loaded=false) {
  if (!loaded) {
    let load_bar = document.querySelectorAll('.load-bar')
    load_bar.forEach((bar) => {
      bar.classList.add('ready')
      setTimeout(() => {
        bar.remove()
      }, 500)
      
    })
    let bar = document.createElement('div')
    bar.classList.add('load-bar')
    document.body.appendChild(bar)
    setTimeout(() => {
      bar.classList.add('loading')
    }, 10)
  } else {
    let load_bar = document.querySelectorAll('.load-bar')
    load_bar.forEach((bar) => {
      bar.classList.remove('loading')
      bar.classList.add('loaded')
      setTimeout(() => {
        bar.classList.remove('loaded')
        bar.classList.add('ready')
      }, 500)
    })
  }
}


// テーマ情報を取得
function getThemeInfo() {
  fetch('/wp-admin/admin-ajax.php', {
    method: 'POST',
    body: new URLSearchParams({
      'action': 'get_theme_info'
    })
  })
  .then(response => response.json())
  .then(data => {
    let name = 'Oyiso\'s Theme'
    let version = ''
    if (data.success) {
      data = data.data
      name = data.name
      version = ` (v${data.version})`
    }
    console.log(
      `%c ${name}${version} %c https://oyiso.cn`, 
      'background:linear-gradient(120deg,#8183ff,#67c3d3); color:#fff; border-radius:2px;', 
      ''
    )
  })
}



// 初期化状態設定
document.addEventListener('DOMContentLoaded', function() {
  homeView(def_img) //ホームビジュアル
  home1_newest() //ホーム最新コンテンツ
  menuSwitch() //メニューボタン
  ArticleProcessing() //記事処理
  nodesEdit() //テキストエフェクト追加
  lazyLoad(loading_img) //画像遅延読み込み
  imagePreview(loading_img) //画像プレビュー
  highLight() //コードブロックハイライト
  lightSwitch() //テーマモード切替
  archiveJs() //アーカイブページ
  scrollEvent() //スクロールイベント/トップに戻る
  searchFunc() //検索
  // randomCover(apiUrl) //ランダム画像処理
  titleToc() //記事目次Toc
  friendsSup() //友達リンク自己申請
  scrollReveal() //ウェブエフェクト
  loadBar(loaded=true) // ローディングバー - 読み込み完了
})


// PJAX
try {
  const { Pjax } = window['pjax-api']

  new Pjax({
    areas: ['#pjax-box, #pjax-box-footer'],
    link: ':is(a)[href]:not([target],[data-fancybox])',
    form: ':is(form)[method="get"]',
  })
  
  window.addEventListener('pjax:fetch', () => {
    loadBar() // ローディングバー - 読み込み中
  })
  
  window.addEventListener('pjax:unload', () => {
  
  })
  
  document.addEventListener('pjax:content', () => {
  
  })
  
  document.addEventListener('pjax:ready', () => {
    homeView(def_img) //ホームビジュアル
    home1_newest() //ホーム最新コンテンツ
    menuSwitch() //メニューボタン
    ArticleProcessing() //記事処理
    nodesEdit() //テキストエフェクト追加
    lazyLoad(loading_img) //画像遅延読み込み
    imagePreview(loading_img) //画像プレビュー
    highLight() //コードブロックハイライト
    lightSwitch() //ライト切替
    archiveJs() //アーカイブページカテゴリタイトル
    scrollEvent() //スクロールイベント/トップに戻る
    searchFunc() //検索
    CodeDarkMode() //コードブロックダークモード
    // randomCover(apiUrl) //ランダム画像処理
    titleToc() //記事目次Toc
    friendsSup() //友達リンク自己申請
    // ウェブエフェクト
    setTimeout(() => {
      scrollReveal(true)
    }, 10)
    loadBar(true) // ローディングバー - 読み込み完了
  })
  
} catch (e) {
  console.error('Pjax is not defined: ', e)
}

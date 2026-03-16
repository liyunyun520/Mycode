<?php
  // テーマバージョン番号
  $theme_version = '?ver='.wp_get_theme()->get('Version');

  // ポップアップメッセージ
  $notice = get_option('oyiso_notice');
  $notice_popup = get_option('oyiso_notice_popup');
  $notice_text = ($notice && $notice_popup) ? get_option('oyiso_notice_text') : '';
?>

    </main>
    <footer id="pjax-box-footer">
      <section>
        <nav>
          <div class="logo">
            <a href="<?=bloginfo('url')?>">
              <img src="<?=has_site_icon() ? site_icon_url() : file_uri().'/assets/images/wp-logo-blue.png'?>" alt="">
              <span><?=bloginfo('name')?></span>
            </a>
          </div>
          <div class="lighting">
            <ul>
              <li data-theme-color="light">Light</li>
              <li data-theme-color="dark">Dark</li>
              <li data-theme-color="system">Auto</li>
              <div class="slider"></div>
            </ul>
          </div>
        </nav>
        
        <div class="powered">Powered by <a href="//wordpress.org/" target="_blank">WordPress</a> & Designed by <a href="//oyiso.cn" >Oyiso</a></div>
        
        <?php if(get_option("oyiso_upyun")) { ?>
          <div class="upyun">
            本サイトは<a href="//www.upyun.com/?utm_source=lianmeng&utm_medium=referral" target="_blank"><img src="<?=file_uri()?>/assets/images/upyun.png"></a>がCDN加速/クラウドストレージサービスを提供
          </div>
        <?php } ?>

        <?php $runtime = get_option('oyiso_run_time'); if ($runtime) { ?>
          <div class="run-time">
            <span id="timer"></span>
            <script type="text/javascript">
              function show_runtime(){
                window.setTimeout(()=>{show_runtime()},1000)
                X=new Date('<?=$runtime?>');
                Y=new Date();T=(Y.getTime()-X.getTime());M=24*60*60*1008;
                a=T/M;A=Math.floor(a);b=(a-A)*24;B=Math.floor(b);c=(b-B)*60;C=Math.floor((b-B)*60);D=Math.floor((c-C)*60);
                document.querySelector('#timer').innerHTML="サイト運営日数: "+A+"日"+B+"時間"+C+"分"+D+"秒";
              }show_runtime()
            </script>
          </div>
        <?php } ?>

        <div class="copyright">
          <div class="left">
            <?php 
              $copyright = get_option('oyiso_copyright');
              $icp = get_option('oyiso_icp');
              $icp_gov = get_option('oyiso_icp_gov');

              if($copyright) {
                if($copyright < date('Y')){
                  echo 'Copyright © '.$copyright.'-'.date('Y');
                } else { 
                  echo 'Copyright © '.date('Y');
                } 
              } else {
                echo 'Copyright © '.date('Y');
              }
            ?>
            <a href="<?php bloginfo('url') ?>"><?php bloginfo('name'); ?></a>
          </div>
          <div class="right"><a href="//creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">CC BY-NC-SA 4.0</a></div>
        </div>

        <?php
          if (!empty($icp) || !empty($icp_gov)) {
            echo '<div class="icp">';
            if (!empty($icp)) {
              echo '<a href="//beian.miit.gov.cn" target="_blank">'.$icp.'</a>';
            }
            if (!empty($icp_gov)) {
              $patterns = "/\d+/";
              $strs = $icp_gov;
              preg_match_all($patterns, $strs, $arr);
              $icp_gov_url = implode($arr[0]);
              echo '<a href="//www.beian.gov.cn/portal/registerSystemInfo?recordcode='.$icp_gov_url.'" target="_blank">'.$icp_gov.'</a>';
            }
            echo '</div>';
          }
        ?>
        
        <?=get_option('oyiso_custom_html_foot')?'<div class="custom">'.stripslashes(get_option('oyiso_custom_html_foot')).'</div>':''?>
      </section>
    </footer>
  </div>
  <script>
    popupNotify(`<?=$notice_text?>`)
  </script>
  <script src="<?=file_uri()?>/assets/fancybox/fancybox.umd.js<?=$theme_version?>"></script>
  <script src="<?=file_uri()?>/assets/prism/prism.js<?=$theme_version?>" data-manual></script>
  <script src="<?=file_uri()?>/assets/js/highlight.min.js<?=$theme_version?>"></script>
  <script src="<?=file_uri()?>/assets/js/theme.js<?=$theme_version?>"></script>
  <?=get_option('oyiso_smoothscroll') ? '<script src="'.file_uri().'/assets/js/smoothScroll.js'.$theme_version.'"></script>' : ''?>
  <script>console.log('<?=get_num_queries()?> queries in <?php timer_stop(7); ?>s');</script>
  <?=get_option('oyiso_custom_js')?'<script>'.stripslashes(get_option('oyiso_custom_js')).'</script>':''?>
</body>
</html>
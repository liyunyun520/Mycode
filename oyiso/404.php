<?=get_header()?>

<style>
  .container {
    background-color: var(--bgc-single);
  }
</style>

<section class="p404">
  <div class="screen">
    <div class="notfound">
      <div class="tip main-reveal">404 | Page not found!</div>
      <div class="back main-reveal">
        <a class="home" href="<?=bloginfo('url')?>">Home</a>
        <a style="cursor:pointer" onclick="window.history.back()">Go Back</a>
      </div>
    </div>
  </div>
</section>

<?=get_footer()?>
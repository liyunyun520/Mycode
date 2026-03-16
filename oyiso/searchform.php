<form class="search" method="get" action="<?=home_url('/?s=')?>">
  <input id="search" type="text" value="<?=get_search_query()?>" name="s" placeholder="検索..." required autocomplete="off">
  <button><svg class="icon" aria-hidden="true"><use xlink:href="#icon-search-circle"></use></svg></button>
</form>
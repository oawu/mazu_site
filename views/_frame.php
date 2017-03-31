<!DOCTYPE html>
<html lang="zh">
  <head>
    <?php echo isset ($meta) ? implode (MINIFY ? '' : "\n", $meta) : '';?>

    <title><?php echo isset ($title) && $title ? $title . ' - ' : '';?><?php echo TITLE;?></title>
    <?php echo isset ($link) ? implode (MINIFY ? '' : "\n", $link) : '';?>

    <?php echo implode (MINIFY ? '' : "\n", $css);?>
    <?php echo implode (MINIFY ? '' : "\n", $js);?>
    <?php if (isset ($jsonLd) && $jsonLd) { ?>
      <script type="application/ld+json"><?php echo json_encode ($jsonLd, DEV ? JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES : JSON_UNESCAPED_SLASHES);?></script>
    <?php } ?>

  </head>
  <body lang="zh-tw"<?php echo isset ($body_class) && $body_class ? ' class="' . $body_class . '"' : '';?>>
    <input type='hidden' id='_aa' value='<?php echo ADMIN_URL;?>' />
    <input type='checkbox' id='menu_ckb' class='ckb' />

    <header id='header'>
      <div id='header_content'>
  <?php if (!(isset ($last_url) && $last_url)) { ?>
          <label class='icon-menu' for='menu_ckb'></label>
  <?php } else { ?>
          <a href='<?php echo $last_url;?>' id='back' class='icon-arrow-left'></a>
  <?php } ?>

        <a href='<?php echo URL;?>' id='logo'>北港迎媽祖</a>
        <form action='<?php echo PAGE_URL_SEARCH;?>' method='get' class='content'>
          <input type='text' placeholder='搜尋..' id='search' name='search' value='<?php echo isset ($search) && $search ? $search : '';?>' />
          <button type='submit' class='icon-search'></button>
        </form>
        <a id='share' class='icon-share'></a>
      </div>
    </header>

    <div id='container'><?php echo $content;?></div>

    <div id='menu'>
      <header><div>北港</div><div><span>迎媽祖</span><span>Beigang Mazu</span></div></header>
      <div>
        <a href='<?php echo PAGE_URL_INDEX;?>' class='icon-home<?php echo isset ($now) && $now == 'index' ? ' active' : '';?>'>首頁</a>
        <a href='<?php echo URL_ARTICLES . 'index' . HTML;?>' class='icon-file-text2<?php echo isset ($now) && $now == 'articles' ? ' active' : '';?>'>所有文章</a>
        <a href='<?php echo PAGE_URL_GPS;?>' class='icon-op<?php echo isset ($now) && $now == 'paths' ? ' active' : '';?>'>繞境路關</a>
        <a href='<?php echo URL_ALBUMS . 'index' . HTML;?>' class='icon-images<?php echo isset ($now) && $now == 'albums' ? ' active' : '';?>'>活動相簿</a>
        <a href='<?php echo URL_VIDEOS . 'index' . HTML;?>' class='icon-film<?php echo isset ($now) && $now == 'videos' ? ' active' : '';?>'>影音紀錄</a>
        <i></i>
        <a href='<?php echo PAGE_URL_LICENSE;?>' class='icon-c<?php echo isset ($now) && $now == 'license' ? ' active' : '';?>'>授權聲明</a>
        <a href='<?php echo PAGE_URL_AUTHOR;?>' class='icon-user-secret<?php echo isset ($now) && $now == 'author' ? ' active' : '';?>'>關於作者</a>
        <footer><a href='<?php echo PAGE_URL_LICENSE;?>'>隱私權政策 - 服務條款</a><span>© 2014-2017 MAZU.IOA.TW</span></footer>
      </div>
    </div><label for='menu_ckb' class='ckb_cover'></label>
    
<?php if (isset ($scopes) && $scopes) {
        foreach ($scopes as $scope) { ?>
          <div class='_scope' itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href='<?php echo $scope['url'];?>'><span itemprop="title"><?php echo $scope['title'];?></span></a></div>
  <?php }
      } ?>

    <div id='fb-root'></div>

  </body>
</html>
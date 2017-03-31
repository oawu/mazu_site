<div id='content'>
  <?php
  if ($tag) { ?>
    <header class='article_title'>
      <h1 class='l'><?php echo $tag;?></h1>
      <div class='r'></div>
    </header>
  <?php
  }
  foreach ($articles as $article) { ?>
    <a href='<?php echo $article['url'];?>' class='article'>
      <figure class='_i'>
        <img src='<?php echo $article['cover']['c600x315'];?>'>
        <figcaption><?php echo $article['title'];?></figcaption>
      </figure>
      <header>
        <h3><?php echo $article['title'];?></h3>
        <span><?php echo $article['user']['name'];?><i class='icon-keyboard_arrow_right'></i><?php echo $article['tag'];?></span>
      </header>
      <section><?php echo mb_strimwidth (remove_ckedit_tag ($article['content']), 0, 200, '…','UTF-8');?></section>
      <time datetime='<?php echo $article['created_at'];?>'><?php echo date ('Y.m.d', strtotime ($article['created_at']));?></time>
    </a>
<?php
  } ?>
  
  <div class="pagination"><?php echo $pagination;?></div>

</div>
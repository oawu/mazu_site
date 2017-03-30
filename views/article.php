<article id='article'>
  <nav>
    <div class='user _i'>
      <img src='<?php echo avatar_url ($article['user']['fbid']);?>'>
    </div>
    <a href='<?php echo facebook_url ($article['user']['fbid']);?>' class='name' target='_blank'><?php echo $article['user']['name'];?></a>
    <i class='icon-keyboard_arrow_right'></i>
    <a href='<?php echo sprintf (URL_TAG_ARTICLES, oa_url_encode ($article['tag']));?>' class='tag'><?php echo $article['tag'];?></a>
    <i class='icon-keyboard_arrow_right'></i>
    <h1 class='title'><?php echo $article['title'];?></h1>
    <time><?php echo date ('Y.m.d', strtotime ($article['created_at']));?></time>
    <div class='fb-like' data-href='<?php echo $article['url'];?>' data-send='false' data-layout='button_count' data-action='like' data-show-faces='false' data-share='true'></div>
  </nav>

  <section class='section' data-pv='Article' data-id='<?php echo $article['id'];?>' data-url='<?php echo $article['url'];?>'><?php echo $article['content'];?></section>

<?php
  if ($article['sources']) { ?>
    <footer>
      <header>相關參考</header>
  <?php foreach ($article['sources'] as $source) { ?>
          <div><a href='<?php echo $source['href'];?>'><?php echo $source['text'];?></a><i>-</i><a href='<?php echo $source['href'];?>'><?php echo $source['href'];?></a></div>
  <?php } ?>
    </footer>
<?php
  } ?>

  <a class='icon-mail-forward share'></a>
</article>
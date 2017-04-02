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
    <div class='other'>
      <header>相關參考</header>
  <?php foreach ($article['sources'] as $source) { ?>
          <div><a href='<?php echo $source['href'];?>'><?php echo $source['text'];?></a><i>-</i><a href='<?php echo $source['href'];?>'><?php echo $source['href'];?></a></div>
  <?php } ?>
    </div>
<?php
  }
  if (isset ($mores) && $mores) { ?>
    <div class='other'>
      <header>推薦文章</header>
  <?php foreach ($mores as $more) { ?>
          <a href='<?php echo $more['url'];?>'>
            <figure class='_i'>
              <img src="<?php echo $more['cover']['c600x315'];?>">
            <figcaption><?php echo $more['title'];?></figcaption>
            </figure>
            <h3><?php echo $more['title'];?></h3>
            <span><?php echo mb_strimwidth (remove_ckedit_tag ($more['content']), 0, 100, '…','UTF-8');?></span>
          </a>
  <?php } ?>
    </div>
<?php
  } ?>

  <div class='other'>
    <header>留言討論區</header>
    <div class="fb-comments" data-order-by='reverse_time' width='100%' data-href="<?php echo $article['url'];?>" data-numposts="5"></div>
  </div>

  <a class='icon-mail-forward share'></a>
</article>
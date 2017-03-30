<article class='l'>
  <figure class='_i' data-count='<?php echo count ($album['images']);?>'>
    <img alt="<?php echo $album['title'];?> - <?php echo TITLE;?>" src="<?php echo $album['cover']['c600x315'];?>">
    <figcaption><?php echo $album['title'];?></figcaption>
  </figure>

  <div class='avatar'>
    <img src='<?php echo avatar_url ($album['user']['fbid']);?>'>
  </div>
  <div class='info'>
    <span><a href='<?php echo facebook_url ($album['user']['fbid']);?>'><?php echo $album['user']['name'];?></a></span>
    <span><div class='fb-like' data-href='<?php echo $album['url'];?>' data-send='false' data-layout='button_count' data-action='like' data-show-faces='false' data-share='true'></div></span>
  </div>

  <h1><?php echo $album['title'];?></h1>
  <section class='section' data-pv='Album' data-id='<?php echo $album['id'];?>' data-url='<?php echo $album['url'];?>'><?php echo $album['content'];?></section>

  <footer><?php echo date ('Y.m.d', strtotime ($album['created_at']));?></footer>
</article>

<div class='r'>
  <div id='imgs'>
<?php
    foreach ($album['images'] as $image) { ?>
      <figure class='_i' data-pvid='AlbumImage-<?php echo $image['id'];?>'>
        <img alt="<?php echo $title = $image['title'] ? $image['title'] : $album['title'];?> - <?php echo TITLE;?>" src="<?php echo $image['url']['w800'];?>">
        <figcaption><?php echo $title;?></figcaption>
      </figure>
<?php
    } ?>
  </div>
</div>
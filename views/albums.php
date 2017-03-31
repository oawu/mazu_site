<?php echo isset ($h1) && $h1 ? '<h1 class="h">' . $h1 . '</h1>' : '';?>
<div id='content'>
<?php
  foreach ($albums as $album) { ?>
    <a href='<?php echo $album['url'];?>'>
      <figure class='_i'>
        <img alt="<?php echo $album['title'] ? $album['title'] . ' - ' . TITLE : TITLE;?>" src="<?php echo $album['cover']['c600x315'];?>">
        <figcaption><?php echo $album['title'];?></figcaption>
        <div class="icon-eye"><?php echo $album['pv'];?></div>
      </figure>
      <div class='a _i'><img src='<?php echo avatar_url ($album['user']['fbid']);?>' /></div>
      <div class='t'><?php echo $album['title'];?></div>
      <div class='c'>共有 <?php echo number_format (count ($album['images']));?> 張照片</div>
    </a>  
<?php
  } ?>
</div>
<div class="pagination"><?php echo $pagination;?></div>
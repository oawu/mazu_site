<?php echo isset ($h1) && $h1 ? '<h1 class="h">' . $h1 . '</h1>' : '';?>
<div id='content'>
<?php
  foreach ($videos as $video) { ?>
    <a href='<?php echo $video['url'];?>'>
      <figure class='_i'>
        <img alt="<?php echo $video['title'];?> - <?php echo TITLE;?>" src="<?php echo youtube_cover_url ($video['vid']);?>">
        <figcaption><?php echo $video['title'];?></figcaption>
      </figure>
      <div class='user'><div class='_i'><img src='<?php echo avatar_url ($video['user']['fbid']);?>'></div><span><?php echo $video['user']['name'];?></span></div>
      <div class='info'>
        <h3><?php echo $video['title'];?></h3>
        <div><?php echo mb_strimwidth (remove_ckedit_tag ($video['content']), 0, 150, '…','UTF-8');?></div>
      </div>
    </a>
<?php
  } ?>
</div>
<div class="pagination"><?php echo $pagination;?></div>

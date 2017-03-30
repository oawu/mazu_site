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
      <div class='avatar'>
        <img src='<?php echo avatar_url ($article['user']['fbid']);?>'>
      </div>
      <div class='name'><span><?php echo $article['user']['name'];?></span></div>
      <div class='content'><span><?php echo $article['title'];?></span><span>-</span><span><?php echo mb_strimwidth (remove_ckedit_tag ($article['content']), 0, 150, '…','UTF-8');?></span></div>
      
<?php if ($article['pics']) { ?>
        <div class='attachments'>
    <?php foreach ($article['pics'] as $pic) { ?>
            <div class='img _i'><img src='<?php echo $pic;?>'></div>
  <?php } ?>
        </div>
<?php } ?>
      <span class='icon-more_horiz more'></span>
    </a>
<?php
  } ?>
  
  
  <div class="pagination"><?php echo $pagination;?></div>

</div>
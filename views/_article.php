<article id='article'>
  <nav>
    <div class='user _i'>
      <img src='<?php echo avatar_url ($obj['user']['fbid']);?>'>
    </div>
    <a href='<?php echo facebook_url ($obj['user']['fbid']);?>' class='name' target='_blank'><?php echo $obj['user']['name'];?></a>
    <i class='icon-keyboard_arrow_right'></i>
    <h1 class='title'><?php echo $obj['title'];?></h1>
    <time><?php echo date ('Y.m.d', strtotime ($obj['created_at']));?></time>
    <div class='fb-like' data-href='<?php echo URL;?>' data-send='false' data-layout='button_count' data-action='like' data-show-faces='false' data-share='true'></div>
  </nav>

  <section class='section'<?php echo isset ($obj['orm']) && $obj['orm'] ? " data-pv='" . $obj['orm'] ."'" : '';?> data-id='<?php echo $obj['id'];?>' data-url='<?php echo $obj['url'];?>'><?php echo $obj['content'];?></section>

  <a class='icon-mail-forward share'></a>
</article>
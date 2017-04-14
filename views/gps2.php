
<?php echo isset ($h1) && $h1 ? '<h1 class="h">' . $h1 . '</h1>' : '';?>
<div id='maps' data-infos='<?php echo json_encode ($infos);?>' data-paths='<?php echo json_encode ($paths);?>'></div>

<div id='select_panel'>
  <select id='select'>
    <?php
    foreach ($struct as $name => $items) { ?>
      <optgroup label='<?php echo $name;?>路關'>
  <?php foreach ($items as $key => $name) { ?>
          <option value='<?php echo $key;?>'><?php echo $name;?></option>
  <?php } ?>
      </optgroup>
  <?php
    } ?>
  </select>
  <label for='select' class='icon-keyboard_arrow_down'></label>
</div>
<div id='paths'></div>
<div id='f' class='fb-like' data-href='<?php echo PAGE_URL_GPS;?>' data-send='false' data-layout='button_count' data-action='like' data-show-faces='false' data-share='true'></div>
<div id='z'><a class='icon-zi'></a><a class='icon-zo'></a></div>
<div id='o' class='icon-my_location'></div>
<div id='y'><div><h1>2017年 北港三月十九<br/> GPS 即時定位</h1><p>衛星訊號接收中，請稍候..</p><div><i></i><i></i><i></i></div><span>版本：v3.0.0</span></div></div>
<div id='l'>
  <span>地圖資料 ©2017 Google</span>
  <a href='https://www.google.com/intl/zh-TW_US/help/terms_maps.html' target='_blank'>使用條款</a>
  <span>北港迎媽祖</span>
  <a href='<?php echo PAGE_URL_LICENSE;?>' target='_blank'>使用條款</a>
</div>
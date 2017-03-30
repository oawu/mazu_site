/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var $imgs = $('#imgs');
  
  var $el = $imgs.find ('> *');
  var figures = [];

  for (var i = 0; i < $el.length; i++)
    if ($el.eq (i).is ('figure')) figures.push ($el.eq (i).clone (true));
    else if (figures.length) oasort (figures.length).forEach (function (c) { $('<div />').addClass ('pics').addClass ('n' + c).append (figures.splice (0, c)).insertBefore ($el.eq (i)); });

  if (figures.length) oasort (figures.length).forEach (function (c) { $('<div />').addClass ('pics').addClass ('n' + c).append (figures.splice (0, c)).appendTo ($imgs); });
  $imgs.find ('> figure').remove ();
  
  $imgs.find ('figure').each (function (i) {
    $(this).OAIL ({verticalAlign: 'center'}).attr ('href', $imgs.data ('url') + '#&gid=1&pid=' + (i + 1) +'&id=0');
  });

  OAIPS ('#imgs', 'figure');
  
});
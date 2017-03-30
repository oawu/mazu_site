/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

function queryString () {
  var qs = {}, q = window.location.search.substring (1), v = q.split ('&');

  for (var i = 0; i < v.length; i++) {
    var pair = v[i].split ('=');
    if (typeof qs[pair[0]] === 'undefined') qs[pair[0]] = decodeURIComponent (pair[1]);
    else if (typeof qs[pair[0]] === 'string') qs[pair[0]] = [qs[pair[0]],decodeURIComponent (pair[1])];
    else qs[pair[0]].push (decodeURIComponent (pair[1]));
  }
  return qs;
}

$(function () {
  var $items = $('#items').attr ('data-tip', '請搜尋其他關鍵字吧，目前沒有相關資料喔!'), qs = queryString ();
  if (typeof qs['search'] === 'undefined' || qs['search'].length === 0) return;

  var $search = $('#search');
  $search.val (qs.search);
  var re = new RegExp ("(" + qs.search.split (/\s+/).join ('|') + ")+");

  window._mazu = window._mazu.map (function (t) {
    return {
      group: t.group,
      datas: t.datas.filter (function (t) { return re.test (t.title) || re.test (t.content) || re.test (t.user.name); })
    };
  });
  window._mazu = window._mazu.filter (function (t) { return t.datas.length; });

  $items.append (window._mazu.map (function (t) {
    return $('<div />').addClass ('group').text (t.group).add (
      $('<div />').append (
        t.datas.map (function (u) {
          return $('<a />').addClass ('item').attr ('href', u.url).append (
            $('<figure />').append (
              $('<img />').attr ('src', u.pic)).append (
              $('<figcaption />').text (u.title))).append (
            $('<div />').addClass ('info').append (
              $('<h3>').text (u.title)).append (
              $('<span>').text (u.user.name)).append (
              $('<div>').text (u.content)));
        })));
  })).find ('figure').OAIL ({verticalAlign: 'center'});
  
});
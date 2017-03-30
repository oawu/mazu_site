/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

window.gmc = function () { $(window).trigger ('gm'); };
var OAML = function () { };
$(function () {
  var _ks = [], _t = 1000 * 60, _vml = false, _lat = 23.569, _lng = 120.3038, _vm = null, _vp = null, _vz = null, _va = null, _tr = null, _im = false, _vy = null, _vs = null;
  var $maps = $('#maps');
  var $fz = $('#fz');
  var $fzl = $fz.parent ();
  var $select = $('#select').change (fsp);
  var $o = $('#o');
  var _tl = 10 * 60 * 1000; // location point cache

  var _ps = {}, _p = [], _vi = [], _vis = [];
  function fap (w, h, q) { return 'M -' + (w / 2) + ' ' + (h / 2) + 'l ' + (w / 2) + ' -' + h + ' l ' + (w / 2) + ' ' + h + ' q -' + (w / 2) + ' -' + q + ' -' + w + ' 0'; }
  
  function fmkm (m, a, n, cn, u, i, c) {
    if (u > cn) {
      m.setPosition (new google.maps.LatLng (m.getPosition ().lat () + a, m.getPosition ().lng () + n));
    clearTimeout (window._fmkmTimer);
    window._fmkmTimer = setTimeout (function () {
      var b = ((1 / _p.length * 100) / u) * cn; _vp.setOptions ({ icons: _vs.map (function (t) { return {icon: t, offset: ((i / _p.length * 100) + b) + '%'};}) });
      fmkm (m, a, n, cn + 1, u, i, c);
    }, 25); } else { if (c) c (m); }
  }
  function fgu (w, n) { var aa = w.lat () - n.lat (), al = w.lng () - n.lng (); var aad = ((Math.abs (aa) + Math.abs (al)) / 2); var u = aad < 10 ? aad < 1 ? aad < 0.1 ? aad < 0.01 ? aad < 0.001 ? aad < 0.0001 ? 3 : 6 : 9 : 12 : 15 : 24 : 21; var a = aa / u, n = al / u; if (!((Math.abs (a) > 0) || (Math.abs (n) > 0))) return null; return { u: u, a: a, n: n }; }
  function fmkg (m, w, c, i) { var n = m.getPosition (); var u = fgu (w, n); 
    if (!u) return false;
    fmkm (m, u.a, u.n, 0, u.u, i, c); }
  function fmm (m, a, n, cn, u, c) {
    if (u > cn) { m.setCenter (new google.maps.LatLng (m.getCenter ().lat () + a, m.getCenter ().lng () + n)); clearTimeout (window.mmtr); window.mmtr = setTimeout (function () { fmm (m, a, n, cn + 1, u, c); }, 25); } else { if (c) c (m); } }
  function fmg (m, w, c) { var now = m.center; var u = fgu (w, now); if (!u) return false; fmm (m, u.a, u.n, 0, u.u, c); }
  
  /* Location info */
  function _flo (isFirst) {
    if ($o.hasClass ('l')) return;
    $o.addClass ('l');

    navigator.geolocation.getCurrentPosition (function (position) {
      if (isFirst === true) {
        $o.addClass ('s').click (function () {
          _flo (false);
          if ($fz.prop ('checked')) $fz.prop ('checked', false);
        });
      } else {
        var t = new google.maps.LatLng (position.coords.latitude, position.coords.longitude);
        _vy.setOptions ({ map: _vm, zIndex: _va.zIndex + 1, position: t });
        _vm.setOptions ({ center: t, zoom: 16 });
      }

      _vl = {
        lat: position.coords.latitude,
        lng: position.coords.longitude,
        acc: position.coords.accuracy,
      };

      var ct = _fsg ('ct'); if (ct && (new Date ().getTime () - ct < _tl)) return $o.removeClass ('l'); _fss ('ct', new Date ().getTime ());

      $.ajax ({
        url: window.apis.addLoc,
        async: true, cache: false, dataType: 'json', type: 'get',
        data: _vl
      }).complete (function () { $o.removeClass ('l'); });

    }, 
    function (e) {
      $o.removeClass ('l');
      // $.ajax ({
      //   url: 'http://ip-api.com/json',
      //   dataType: 'jsonp',
      //   crossDomain: true,
      //   async: true, cache: false, type: 'get',
      // }).done (function (result) {
      //   console.error (result.lat, result.lon);
      // })
      // .fail (function (result) { })
      // .complete (function (result) { });

    }, { enableHighAccuracy: true });
  }

  function flp (i){
    i = (i !== undefined) && ((i + 1) < _p.length) ? i + 1 : 0;
    clearTimeout (_tr); _tr = setTimeout (function () { if (!_im && !(i % 10) && $fz.prop ('checked')) fmg (_vm, _p[i]);
      fmkg (_va, _p[i], flp (i), i);
    }, 150);
  }

  function fsp (){
    _vis.forEach (function (t) { t.setMap (null); });

    _p = _ps[$select.val ()].map (function (t) { return new google.maps.LatLng (t[0] / Math.pow (10, 6) + 23, t[1] / Math.pow (10, 6) + 120); });
    _i = [['起馬', _p[0]], ['下馬', _p[_p.length - 1]]].concat (_vi[$select.val ()].map (function (t) { return [t[0], new google.maps.LatLng (t[1][0] / Math.pow (10, 6) + 23, t[1][1] / Math.pow (10, 6) + 120)]; }));
    
    clearTimeout (_tr);
    clearTimeout (window._fmkmTimer);
    _vp.setPath (_p);
    _va.setPosition (_p[0]);
    // if (_vz) _vz.setPosition (_p[0]);
    setTimeout (flp, 1000);
    _vp.setOptions ({
      icons: _vs.map (function (t) { return {icon: t, offset: '0%'};})
    });
    _vis = _i.map (function (t, i) {
      return new OAML ({
        map: _vm,
        labelAnchor: new google.maps.Point (10 / 2, 10 / 2),
        icon: {path: 'M 0 0'},
        zIndex: (_vz ? _vz.zIndex : 0) - (i + 1),
        labelClass: 'if' + (_vz && _vz.position.lat () == t[1].lat () && _vz.position.lng () == t[1].lng () ? ' o' : ''),
        position: t[1], labelContent: '<div>' + t[0] + '</div>'
      });
    });
  }
  function fin (){
    if (_vml) return ; _vml = true;
    
    function OAIN(e,t){function i(){}i.prototype=t.prototype,e.superClass_=t.prototype,e.prototype=new i,e.prototype.constructor=e}
    function OAML_(e,t,i){this.marker_=e,this.handCursorURL_=e.handCursorURL,this.labelDiv_=document.createElement("div"),this.labelDiv_.style.cssText="position: absolute; overflow: hidden;",this.eventDiv_=document.createElement("div"),this.eventDiv_.style.cssText=this.labelDiv_.style.cssText,this.eventDiv_.setAttribute("onselectstart","return false;"),this.eventDiv_.setAttribute("ondragstart","return false;"),this.crossDiv_=OAML_.getSharedCross(t)}
    OAML = function (e) {e=e||{},e.labelContent=e.labelContent||"",e.initCallback=e.initCallback||function(){},e.labelAnchor=e.labelAnchor||new google.maps.Point(0,0),e.labelClass=e.labelClass||"markerLabels",e.labelStyle=e.labelStyle||{},e.labelInBackground=e.labelInBackground||!1,"undefined"==typeof e.labelVisible&&(e.labelVisible=!0),"undefined"==typeof e.raiseOnDrag&&(e.raiseOnDrag=!0),"undefined"==typeof e.clickable&&(e.clickable=!0),"undefined"==typeof e.draggable&&(e.draggable=!1),"undefined"==typeof e.optimized&&(e.optimized=!1),e.crossImage=e.crossImage||"http"+("https:"===document.location.protocol?"s":"")+"://maps.gstatic.com/intl/en_us/mapfiles/drag_cross_67_16.png",e.handCursor=e.handCursor||"http"+("https:"===document.location.protocol?"s":"")+"://maps.gstatic.com/intl/en_us/mapfiles/closedhand_8_8.cur",e.optimized=!1,this.label=new OAML_(this,e.crossImage,e.handCursor),google.maps.Marker.apply(this,arguments)}
    OAIN (OAML_,google.maps.OverlayView),OAML_.getSharedCross=function(e){var t;return"undefined"==typeof OAML_.getSharedCross.crossDiv&&(t=document.createElement("img"),t.style.cssText="position: absolute; z-index: 1000002; display: none;",t.style.marginLeft="-8px",t.style.marginTop="-9px",t.src=e,OAML_.getSharedCross.crossDiv=t),OAML_.getSharedCross.crossDiv},OAML_.prototype.onAdd=function(){var e,t,i,s,a,r,o,n=this,l=!1,g=!1,p=20,_="url("+this.handCursorURL_+")",v=function(e){e.preventDefault&&e.preventDefault(),e.cancelBubble=!0,e.stopPropagation&&e.stopPropagation()},h=function(){n.marker_.setAnimation(null)};this.getPanes().overlayImage.appendChild(this.labelDiv_),this.getPanes().overlayMouseTarget.appendChild(this.eventDiv_),"undefined"==typeof OAML_.getSharedCross.processed&&(this.getPanes().overlayImage.appendChild(this.crossDiv_),OAML_.getSharedCross.processed=!0),this.listeners_=[google.maps.event.addDomListener(this.eventDiv_,"mouseover",function(e){(n.marker_.getDraggable()||n.marker_.getClickable())&&(this.style.cursor="pointer",google.maps.event.trigger(n.marker_,"mouseover",e))}),google.maps.event.addDomListener(this.eventDiv_,"mouseout",function(e){!n.marker_.getDraggable()&&!n.marker_.getClickable()||g||(this.style.cursor=n.marker_.getCursor(),google.maps.event.trigger(n.marker_,"mouseout",e))}),google.maps.event.addDomListener(this.eventDiv_,"mousedown",function(e){g=!1,n.marker_.getDraggable()&&(l=!0,this.style.cursor=_),(n.marker_.getDraggable()||n.marker_.getClickable())&&(google.maps.event.trigger(n.marker_,"mousedown",e),v(e))}),google.maps.event.addDomListener(document,"mouseup",function(t){var i;if(l&&(l=!1,n.eventDiv_.style.cursor="pointer",google.maps.event.trigger(n.marker_,"mouseup",t)),g){if(a){i=n.getProjection().fromLatLngToDivPixel(n.marker_.getPosition()),i.y+=p,n.marker_.setPosition(n.getProjection().fromDivPixelToLatLng(i));try{n.marker_.setAnimation(google.maps.Animation.BOUNCE),setTimeout(h,1406)}catch(r){}}n.crossDiv_.style.display="none",n.marker_.setZIndex(e),s=!0,g=!1,t.latLng=n.marker_.getPosition(),google.maps.event.trigger(n.marker_,"dragend",t)}}),google.maps.event.addListener(n.marker_.getMap(),"mousemove",function(s){var _;l&&(g?(s.latLng=new google.maps.LatLng(s.latLng.lat()-t,s.latLng.lng()-i),_=n.getProjection().fromLatLngToDivPixel(s.latLng),a&&(n.crossDiv_.style.left=_.x+"px",n.crossDiv_.style.top=_.y+"px",n.crossDiv_.style.display="",_.y-=p),n.marker_.setPosition(n.getProjection().fromDivPixelToLatLng(_)),a&&(n.eventDiv_.style.top=_.y+p+"px"),google.maps.event.trigger(n.marker_,"drag",s)):(t=s.latLng.lat()-n.marker_.getPosition().lat(),i=s.latLng.lng()-n.marker_.getPosition().lng(),e=n.marker_.getZIndex(),r=n.marker_.getPosition(),o=n.marker_.getMap().getCenter(),a=n.marker_.get("raiseOnDrag"),g=!0,n.marker_.setZIndex(1e6),s.latLng=n.marker_.getPosition(),google.maps.event.trigger(n.marker_,"dragstart",s)))}),google.maps.event.addDomListener(document,"keydown",function(e){g&&27===e.keyCode&&(a=!1,n.marker_.setPosition(r),n.marker_.getMap().setCenter(o),google.maps.event.trigger(document,"mouseup",e))}),google.maps.event.addDomListener(this.eventDiv_,"click",function(e){(n.marker_.getDraggable()||n.marker_.getClickable())&&(s?s=!1:(google.maps.event.trigger(n.marker_,"click",e),v(e)))}),google.maps.event.addDomListener(this.eventDiv_,"dblclick",function(e){(n.marker_.getDraggable()||n.marker_.getClickable())&&(google.maps.event.trigger(n.marker_,"dblclick",e),v(e))}),google.maps.event.addListener(this.marker_,"dragstart",function(e){g||(a=this.get("raiseOnDrag"))}),google.maps.event.addListener(this.marker_,"drag",function(e){g||a&&(n.setPosition(p),n.labelDiv_.style.zIndex=1e6+(this.get("labelInBackground")?-1:1))}),google.maps.event.addListener(this.marker_,"dragend",function(e){g||a&&n.setPosition(0)}),google.maps.event.addListener(this.marker_,"position_changed",function(){n.setPosition()}),google.maps.event.addListener(this.marker_,"zindex_changed",function(){n.setZIndex()}),google.maps.event.addListener(this.marker_,"visible_changed",function(){n.setVisible()}),google.maps.event.addListener(this.marker_,"labelvisible_changed",function(){n.setVisible()}),google.maps.event.addListener(this.marker_,"title_changed",function(){n.setTitle()}),google.maps.event.addListener(this.marker_,"labelcontent_changed",function(){n.setContent()}),google.maps.event.addListener(this.marker_,"labelanchor_changed",function(){n.setAnchor()}),google.maps.event.addListener(this.marker_,"labelclass_changed",function(){n.setStyles()}),google.maps.event.addListener(this.marker_,"labelstyle_changed",function(){n.setStyles()})]},OAML_.prototype.onRemove=function(){var e;for(this.labelDiv_.parentNode.removeChild(this.labelDiv_),this.eventDiv_.parentNode.removeChild(this.eventDiv_),e=0;e<this.listeners_.length;e++)google.maps.event.removeListener(this.listeners_[e])},OAML_.prototype.draw=function(){this.setContent(),this.setTitle(),this.setStyles()},OAML_.prototype.setContent=function(){var e=this.marker_.get("labelContent");"undefined"==typeof e.nodeType?(this.labelDiv_.innerHTML=e,this.eventDiv_.innerHTML=this.labelDiv_.innerHTML):(this.labelDiv_.innerHTML="",this.labelDiv_.appendChild(e),e=e.cloneNode(!0),this.eventDiv_.innerHTML="",this.eventDiv_.appendChild(e))},OAML_.prototype.setTitle=function(){this.eventDiv_.title=this.marker_.getTitle()||""},OAML_.prototype.setStyles=function(){var e,t;this.labelDiv_.className=this.marker_.get("labelClass"),this.eventDiv_.className=this.labelDiv_.className,this.labelDiv_.style.cssText="",this.eventDiv_.style.cssText="",t=this.marker_.get("labelStyle");for(e in t)t.hasOwnProperty(e)&&(this.labelDiv_.style[e]=t[e],this.eventDiv_.style[e]=t[e]);this.setMandatoryStyles()},OAML_.prototype.setMandatoryStyles=function(){this.labelDiv_.style.position="absolute",this.labelDiv_.style.overflow="","undefined"!=typeof this.labelDiv_.style.opacity&&""!==this.labelDiv_.style.opacity&&(this.labelDiv_.style.MsFilter='"progid:DXImageTransform.Microsoft.Alpha(opacity='+100*this.labelDiv_.style.opacity+')"',this.labelDiv_.style.filter="alpha(opacity="+100*this.labelDiv_.style.opacity+")"),this.eventDiv_.style.position=this.labelDiv_.style.position,this.eventDiv_.style.overflow=this.labelDiv_.style.overflow,this.eventDiv_.style.opacity=.01,this.eventDiv_.style.MsFilter='"progid:DXImageTransform.Microsoft.Alpha(opacity=1)"',this.eventDiv_.style.filter="alpha(opacity=1)",this.setAnchor(),this.setPosition(),this.setVisible()},OAML_.prototype.setAnchor=function(){var e=this.marker_.get("labelAnchor");this.labelDiv_.style.marginLeft=-e.x+"px",this.labelDiv_.style.marginTop=-e.y+"px",this.eventDiv_.style.marginLeft=-e.x+"px",this.eventDiv_.style.marginTop=-e.y+"px"},OAML_.prototype.setPosition=function(e){var t=this.getProjection().fromLatLngToDivPixel(this.marker_.getPosition());"undefined"==typeof e&&(e=0),this.labelDiv_.style.left=Math.round(t.x)+"px",this.labelDiv_.style.top=Math.round(t.y-e)+"px",this.eventDiv_.style.left=this.labelDiv_.style.left,this.eventDiv_.style.top=this.labelDiv_.style.top,this.setZIndex()},OAML_.prototype.setZIndex=function(){var e=this.marker_.get("labelInBackground")?-1:1;"undefined"==typeof this.marker_.getZIndex()?(this.labelDiv_.style.zIndex=parseInt(this.labelDiv_.style.top,10)+e,this.eventDiv_.style.zIndex=this.labelDiv_.style.zIndex):(this.labelDiv_.style.zIndex=this.marker_.getZIndex()+e,this.eventDiv_.style.zIndex=this.labelDiv_.style.zIndex)},OAML_.prototype.setVisible=function(){this.marker_.get("labelVisible")?this.labelDiv_.style.display=this.marker_.getVisible()?"block":"none":this.labelDiv_.style.display="none",this.eventDiv_.style.display=this.labelDiv_.style.display;var e=this.marker_.get("initCallback");e(this.labelDiv_)},OAIN(OAML,google.maps.Marker),OAML.prototype.setMap=function(e){google.maps.Marker.prototype.setMap.apply(this,arguments),this.label.setMap(e)};

    var p = new google.maps.LatLng (_lat, _lng);
    _vm = new google.maps.Map ($maps.get (0), { zoom: 16, disableDefaultUI: true, center: p });
    _vm.mapTypes.set ('style1', new google.maps.StyledMapType ([{featureType: 'administrative.land_parcel', elementType: 'labels', stylers: [{visibility: 'on'}]}, {featureType: 'poi', elementType: 'labels.text', stylers: [{visibility: 'off'}]}, {featureType: 'poi.business', stylers: [{visibility: 'on'}]}, {featureType: 'poi.park', elementType: 'labels.text', stylers: [{visibility: 'on'}]}, {featureType: 'road.local', elementType: 'labels', stylers: [{visibility: 'on'}]}]));
    _vm.setMapTypeId ('style1');

    _vm.addListener ('zoom_changed', function () {
      if ($fz.get (0).x) return $fz.get (0).x = false;
      if ($fz.prop ('checked')) $fz.prop ('checked', false);
    });
    _vm.addListener ('dragend', function () {
      if ($fz.get (0).x) return $fz.get (0).x = false;
      if ($fz.prop ('checked')) $fz.prop ('checked', false);
    });

    $('#z').find ('a').click (function () { _vm.setZoom (_vm.zoom + ($(this).index () ? -1 : 1)); });

    $fz.click (function () {
      if ($fzl.hasClass ('l')) return false;
      $fzl.addClass ('l');

      if ($fz.prop ('checked')) {
        $fz.get (0).x = true;
        _vm.setOptions ({ zoom: 16, center: _va.position });
      }
      $fzl.removeClass ('l');
    });
    _vs = [
        { path: fap (9, 14, 6), strokeColor: 'rgba(50, 60, 140, 1)', strokeWeight: 1, fillColor: 'rgba(68, 77, 145, .85)', fillOpacity: 1 },
        { path: fap (4.2, 6.9, 2.5), strokeColor: 'rgba(255, 255, 255, 1)', strokeWeight: 1, fillColor: 'rgba(255, 255, 255, 1)', fillOpacity: 1 },
        { path: fap (4.2, 6.9, 2.5), strokeColor: 'rgba(255, 255, 255, 1)', strokeWeight: 1, fillColor: 'rgba(223, 93, 84, 1)', fillOpacity: 1 }
      ];
    _vz = new OAML ({ map: _vm, position: new google.maps.LatLng (23.56818134920916, 120.3047239780426), icon: {path: 'M 0 0'}, labelAnchor: new google.maps.Point (40 / 2, 70), zIndex: 99999999, labelClass: 'mz', labelContent: '<img src="img/m.png"/>' });
    _va = new google.maps.Marker ({
      map: _vm,
      position: p,
      icon: {path: 'M 0 0'}
    });
    _vy = new OAML ({ icon: {path: 'M 0 0'}, labelAnchor: new google.maps.Point (50 / 2, 50 / 2), zIndex: 0, labelClass: 'my', labelContent: '<img src="img/user.png"/>'});
    _vp = new google.maps.Polyline ({
      map: _vm, strokeColor: 'rgba(255, 3, 0, .5)', strokeWeight: 4,
      icons: _vs.map (function (t) { return {icon: t, offset: '0%'};})
    });

    _ps = $maps.data ('paths');
    _vi = $maps.data ('infos');
    
    fsp ();
    _flo (true);
  }
  function flgm (){
    var k = _ks[Math.floor ((Math.random() * _ks.length))], s = document.createElement ('script');
    s.setAttribute ('type', 'text/javascript');
    s.setAttribute ('src', 'https://maps.googleapis.com/maps/api/js?' + (k ? 'key=' + k + '&' : '') + 'language=zh-TW&libraries=visualization&callback=gmc');
    (document.getElementsByTagName ('head')[0] || document.documentElement).appendChild (s);
    s.onload = fin;
  }
  $(window).bind ('gm', fin);
  flgm ();
});
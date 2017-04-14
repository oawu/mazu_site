<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */


define ('URL', PROTOCOL . BUCKET . '/');

define ('PAGE_URL_INDEX',    URL . 'index' . HTML);
define ('PAGE_URL_GPS',   URL . 'gps' . HTML);
define ('PAGE_URL_AUTHOR',   URL . 'author' . HTML);
define ('PAGE_URL_LICENSE',   URL . 'license' . HTML);
define ('PAGE_URL_SEARCH',   URL . 'search' . HTML);

define ('URL_ARTICLES', URL . 'articles' . '/');
define ('URL_ALBUMS', URL . 'albums' . '/');
define ('URL_VIDEOS', URL . 'videos' . '/');

define ('URL_TAGS', URL . 'tags' . '/');

define ('URL_TAG_ARTICLES', URL_TAGS . '%s' . '/' . 'articles' . '/');

define ('URL_ARTICLE', URL . 'article' . '/');
define ('URL_ALBUM', URL . 'album' . '/');
define ('URL_VIDEO', URL . 'video' . '/');

define ('URL_IMG', URL . 'img/');
define ('URL_IMG_LOGO', URL_IMG . 'logo/');
define ('URL_IMG_OG', URL_IMG . 'og/');

define ('URL_IMG_OG_TMP', URL_IMG_OG . 'tmp/');
define ('URL_D4_OG_IMG', URL_IMG_OG . 'd4.jpg');
define ('URL_D4_OG_IMG_GPS', URL_IMG_OG . 'gps6.png');

<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */


define ('URL', PROTOCOL . BUCKET . '/');

define ('PAGE_URL_INDEX',    URL . 'index' . HTML);
define ('PAGE_URL_ABOUT',   URL . 'about' . HTML);
define ('PAGE_URL_CONTACT', URL . 'contact' . HTML);

define ('URL_WORKS',    URL . 'works' . '/');
define ('URL_ARTICLES', URL . 'articles' . '/');

define ('URL_TAGS', URL . 'tags' . '/');

define ('URL_TAG_ARTICLES', URL_TAGS . '%s' . '/' . 'articles' . '/');
define ('URL_TAG_WORKS', URL_TAGS . '%s' . '/' . 'works' . '/');

define ('URL_WORK',    URL . 'work' . '/');
define ('URL_ARTICLE', URL . 'article' . '/');


define ('URL_IMG', URL . 'img/');
define ('URL_IMG_LOGO', URL_IMG . 'logo/');

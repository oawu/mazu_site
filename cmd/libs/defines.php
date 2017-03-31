<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

mb_regex_encoding ("UTF-8");
mb_internal_encoding ('UTF-8');

date_default_timezone_set ('Asia/Taipei');

define ('JS', '.js');
define ('CSS', '.css');
define ('JSON', '.json');
define ('HTML', '.html');
define ('TXT', '.txt');
define ('XML', '.xml');

define ('NAME', ($temps = array_filter (explode (DIRECTORY_SEPARATOR, PATH))) ? end ($temps) : '');

define ('OA', '吳政賢');
define ('OA_URL', 'http://www.ioa.tw/');
define ('OA_FB_URL', 'https://www.facebook.com/comdan66/');
define ('OA_FB_MAIL', 'comdan66@gmail.com');
define ('OA_FB_UID', '100000100541088');
define ('FB_APP_ID', '436436213363323');
define ('FB_ADMIN_ID', OA_FB_UID);

define ('PATH_VIEWS', PATH . 'views' . DIRECTORY_SEPARATOR);
define ('PATH_ASSET', PATH . 'asset' . DIRECTORY_SEPARATOR);
define ('PATH_SITEMAP', PATH . 'sitemap' . DIRECTORY_SEPARATOR);
define ('PATH_IMG_OG_TMP', PATH . 'img' . DIRECTORY_SEPARATOR . 'og' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR);
define ('PATH_TMP', PATH . 'tmp' . DIRECTORY_SEPARATOR);

define ('PATH_ARTICLES', PATH . 'articles' . DIRECTORY_SEPARATOR);
define ('PATH_ALBUMS', PATH . 'albums' . DIRECTORY_SEPARATOR);
define ('PATH_VIDEOS', PATH . 'videos' . DIRECTORY_SEPARATOR);

define ('PATH_TAGS', PATH . 'tags' . DIRECTORY_SEPARATOR);
define ('PATH_TAG_ARTICLES', PATH_TAGS . '%s' . DIRECTORY_SEPARATOR . 'articles' . DIRECTORY_SEPARATOR);

define ('PATH_APIS', PATH . 'api' . DIRECTORY_SEPARATOR);

define ('PATH_ARTICLE', PATH . 'article' . DIRECTORY_SEPARATOR);
define ('PATH_ALBUM', PATH . 'album' . DIRECTORY_SEPARATOR);
define ('PATH_VIDEO', PATH . 'video' . DIRECTORY_SEPARATOR);

define ('TITLE', '北港迎媽祖');
define ('KEYWORDS', '北港迎媽祖,北港,媽祖,朝天宮,路關,北港廟會,廟會,廟會繞境,繞境');
define ('DESCRIPTION', '如候鳥一般，是一個返鄉的季節！「農曆三月十九」這個慶典對於北港人而言，就是一個小過年的概念，其實這一天對於北港人來說，不只是熱情也不僅僅是信仰，更是一種習慣、參與感、責任感！十幾年過去了，不曾改變的習慣還依然繼續！不曾冷卻的期待也依然澎湃！在外地的北港囝仔，還記得北港的鞭炮味嗎？這一天這是我們北港人最榮耀、最團結的過年，今年要記得回來，再忙都要回來幫媽祖婆逗熱鬧一下吧！');

define ('AMP_IMG_600_60', PROTOCOL . BUCKET . '/' . 'img/amp_logo_600x60.png');

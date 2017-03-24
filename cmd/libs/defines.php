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
define ('OA_FB_UID', '100000100541088');
define ('FB_APP_ID', '1121233787886675');
define ('FB_ADMIN_ID', OA_FB_UID);

define ('PATH_VIEWS', PATH . 'views' . DIRECTORY_SEPARATOR);
define ('PATH_ASSET', PATH . 'asset' . DIRECTORY_SEPARATOR);
define ('PATH_SITEMAP', PATH . 'sitemap' . DIRECTORY_SEPARATOR);

define ('PATH_ARTICLES', PATH . 'articles' . DIRECTORY_SEPARATOR);
define ('PATH_WORKS', PATH . 'works' . DIRECTORY_SEPARATOR);

define ('PATH_TAGS', PATH . 'tags' . DIRECTORY_SEPARATOR);
define ('PATH_TAG_ARTICLES', PATH_TAGS . '%s' . DIRECTORY_SEPARATOR . 'articles' . DIRECTORY_SEPARATOR);
define ('PATH_TAG_WORKS', PATH_TAGS . '%s' . DIRECTORY_SEPARATOR . 'works' . DIRECTORY_SEPARATOR);


define ('PATH_APIS', PATH . 'api' . DIRECTORY_SEPARATOR);

define ('PATH_ARTICLE', PATH . 'article' . DIRECTORY_SEPARATOR);
define ('PATH_WORK', PATH . 'work' . DIRECTORY_SEPARATOR);

define ('TITLE', 'ZEUS // Design Studio');
define ('KEYWORDS', '宙思設計,ZEUS,網頁設計,品牌設計,平面設計,包裝設計,RWD網頁設計,APP設計,網頁外包');
define ('DESCRIPTION', '宙思設計團隊擁有各領域的人才，我們服務廣泛，凡舉網頁、平面、包裝、印刷及攝影皆可製作，宙思希望能以最完整的服務與最精湛的設計達成您的需求！');
define ('OG_IMG', PROTOCOL . BUCKET . '/' . 'img/og/v1.jpg');
define ('OG_IMG_TYPE', 'image/' . (($pi = pathinfo (OG_IMG)) && $pi['extension'] ? $pi['extension'] : 'jpg'));

define ('FAVICON', PROTOCOL . BUCKET . '/' . 'img/favicon/v3/');
define ('AMP_IMG_600_60', PROTOCOL . BUCKET . '/' . 'img/logo/amp_logo_600x60.png');

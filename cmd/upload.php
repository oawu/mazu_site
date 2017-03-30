<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 *
 * Need to run init.php
 *
 */

define ('PHP', '.php');
define ('PATH', implode (DIRECTORY_SEPARATOR, explode (DIRECTORY_SEPARATOR, dirname (str_replace (pathinfo (__FILE__, PATHINFO_BASENAME), '', __FILE__)))) . '/');
define ('PATH_CMD', PATH . 'cmd' . DIRECTORY_SEPARATOR);
define ('PATH_CMD_LIBS', PATH_CMD . 'libs' . DIRECTORY_SEPARATOR);
include_once PATH_CMD . 'verify' . PHP;
include_once PATH_CMD . 'config_upload' . PHP;

include_once PATH_CMD_LIBS . 'defines' . PHP;
include_once PATH_CMD_LIBS . 'Step' . PHP;
include_once PATH_CMD_LIBS . 'Minify' . DIRECTORY_SEPARATOR . 'Min' . PHP;
include_once PATH_CMD_LIBS . 'Pagination' . PHP;
include_once PATH_CMD_LIBS . 'Sitemap' . PHP;
include_once PATH_CMD_LIBS . 'define_urls' . PHP;

Step::notCil ();
Step::init ();

Step::cleanBuild ();
Step::writeIndexHtml ();
Step::writeAboutHtml ();
Step::writeContactHtml ();
Step::writeArticlesHtml ();
Step::writeWorksHtml ();
Step::writeSitemap ();

// ---------------
// 裡面要判斷是否地回 ln 的狀態
Step::setUploadDirs (array (
    'js' => array ('js'),
    'css' => array ('css'),
    'font' => array ('eot', 'svg', 'ttf', 'woff'),
    'asset' => array ('js', 'css'),
    'img' => array ('png', 'jpg', 'jpeg', 'gif', 'svg', 'ico'),
    '' => array ('html', 'txt'),
    'articles' => array ('html'),
    'article' => array ('html'),
    'maps' => array ('html'),
    'videos' => array ('html'),
    'images' => array ('html'),
    'sitemap' => array ('xml'),
  ));

// ---------------
include_once PATH_CMD_LIBS . 'S3' . PHP;

Step::initS3 (ACCESS, SECRET);
Step::listLocalFiles ();
Step::listS3Files ();
// ---------------

$files = Step::filterLocalFiles ();
Step::uploadLocalFiles ($files);
$files = Step::filterS3Files ();
Step::deletwS3Files ($files);
// ---------------

Step::usage ();
Step::end ();
Step::showUrl ();

header ('Content-Type: application/json');
echo json_encode (array ('result' => 'success'));

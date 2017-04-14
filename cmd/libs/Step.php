<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

if (!function_exists ('web_file_exists')) {
  function web_file_exists ($url, $cainfo = null) {
    $options = array (CURLOPT_URL => $url, CURLOPT_NOBODY => 1, CURLOPT_FAILONERROR => 1, CURLOPT_RETURNTRANSFER => 1);

    if (is_readable ($cainfo))
      $options[CURLOPT_CAINFO] = $cainfo;

    $ch = curl_init ($url);
    curl_setopt_array ($ch, $options);
    return curl_exec ($ch) !== false;
  }
}

if (!function_exists ('download_web_file')) {
  function download_web_file ($url, $fileName = null, $is_use_reffer = false, $cainfo = null) {
    if (!web_file_exists ($url, $cainfo))
      return null;

    if (is_readable ($cainfo))
      $url = str_replace (' ', '%20', $url);

    $options = array (
      CURLOPT_URL => $url, CURLOPT_TIMEOUT => 120, CURLOPT_HEADER => false, CURLOPT_MAXREDIRS => 10,
      CURLOPT_AUTOREFERER => true, CURLOPT_CONNECTTIMEOUT => 30, CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.76 Safari/537.36",
    );

    if (is_readable ($cainfo))
      $options[CURLOPT_CAINFO] = $cainfo;

    if ($is_use_reffer)
      $options[CURLOPT_REFERER] = $url;

    $ch = curl_init ($url);
    curl_setopt_array ($ch, $options);
    $data = curl_exec ($ch);
    curl_close ($ch);

    if (!$fileName)
      return $data;

    $write = fopen ($fileName, 'w');
    fwrite ($write, $data);
    fclose ($write);

    $oldmask = umask (0);
    @chmod ($fileName, 0777);
    umask ($oldmask);

    return filesize ($fileName) ?  $fileName : null;
  }
}

if (!function_exists ('video_url')) {
  function video_url ($id, $title) {
    return URL_VIDEO . $id . '-' . oa_url_encode ($title . HTML);
  }
}
if (!function_exists ('album_url')) {
  function album_url ($id, $title) {
    return URL_ALBUM . $id . '-' . oa_url_encode ($title . HTML);
  }
}
if (!function_exists ('article_url')) {
  function article_url ($id, $title) {
    return URL_ARTICLE . $id . '-' . oa_url_encode ($title . HTML);
  }
}
if (!function_exists ('youtube_cover_url')) {
  function youtube_cover_url ($vid) {
    return 'https://img.youtube.com/vi/' . $vid . '/0.jpg';
  }
}
if (!function_exists ('facebook_url')) {
  function facebook_url ($uid) {
    return 'https://www.facebook.com/' . $uid;
  }
}
if (!function_exists ('avatar_url')) {
  function avatar_url ($uid, $w = 100, $h = 100) {
    $size = array ();
    array_push ($size, isset ($w) && $w ? 'width=' . $w : ''); array_push ($size, isset ($h) && $h ? 'height=' . $h : '');
    return 'https://graph.facebook.com/' . $uid . '/picture' . (($size = implode ('&', array_filter ($size))) ? '?' . $size : '');
  }
}
if (!function_exists ('css')) {
  function css () {
    return array_map (function ($path) {
      return "<link href='" . URL . $path . "' rel='stylesheet' type='text/css' />";
    }, Min::css (func_get_args ()));
  }
}
if (!function_exists ('js')) {
  function js () {
    return array_map (function ($path) {
      return "<script src='" . URL . $path . "' language='javascript' type='text/javascript' ></script>";  
    }, Min::js (func_get_args ()));
  }
}
if (!function_exists('meta')) {
  function meta () {
    return array_map (function ($attributes) {
      return '<meta ' . implode (' ', array_map (function ($attribute, $value) { return $attribute . '="' . $value . '"'; }, array_keys ($attributes), $attributes)) . ' />';
    }, array_merge (array (
        array ('charset' => 'utf-8'),
        array ('http-equiv' => 'Content-type', 'content' => 'text/html; charset=utf-8'),
        array ('http-equiv' => 'Content-Language', 'content' => 'zh-tw'),
        array ('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui'),
        array ('name' => 'robots', 'content' => DEV ? 'noindex,nofollow' : 'index,follow'),
        array ('property' => 'og:site_name', 'content' => TITLE),
        array ('property' => 'fb:admins', 'content' => FB_ADMIN_ID),
        array ('property' => 'fb:app_id', 'content' => FB_APP_ID),
        array ('property' => 'og:locale', 'content' => 'zh_TW'),
        array ('property' => 'og:locale:alternate', 'content' => 'en_US'),
        array ('property' => 'og:type', 'content' => 'article'),
        array ('property' => 'article:publisher', 'content' => OA_FB_URL),
      ), func_get_args ()));
  }
}
if (!function_exists('myLink')) {
  function myLink () {
    return array_map (function ($attributes) {
      return '<link ' . implode (' ', array_map (function ($attribute, $value) { return $attribute . '="' . $value . '"'; }, array_keys ($attributes), $attributes)) . ' />';
    }, func_get_args ());
  }
}
if (!function_exists ('oa_url')) {
  function oa_url ($str) {
    return preg_replace ('/[\/%]/u', ' ', $str);
  }
}
if (!function_exists ('oa_url_encode')) {
  function oa_url_encode ($str) {
    return rawurlencode (oa_url ($str));
  }
}
if (!function_exists ('remove_ckedit_tag')) {
  function remove_ckedit_tag ($text, $space = true) {
    return preg_replace ("/\s+/u", $space ? " " : "", preg_replace ("/&#?[a-z0-9]+;/iu", "", str_replace ('▼', '', str_replace ('▲', '', trim (strip_tags ($text))))));
  }
}
if (!function_exists ('typeOfImg')) {
  function typeOfImg ($img) {
    return 'image/' . (($img = pathinfo ($img)) && $img['extension'] ? $img['extension'] : 'jpg');
  }
}

class Step {
  public static $startTime;
  public static $nowSize;
  public static $size;
  public static $progress = array ();

  public static $uploadDirs = array ();
  public static $s3Files = array ();
  public static $localFiles = array ();
  public static $sitemapInfos = array ();
  public static $isCli = true;
  public static $apis = array ();
  
  public static function progress ($str, $c = 0) {
    $isStr = !is_numeric ($c);
    if (!isset (Step::$progress[$str])) Step::$progress[$str] = array ('c' => is_numeric ($c) && $c ? $c : 1, 'i' => 0);
    else Step::$progress[$str]['i'] += 1;

    if (is_numeric ($c) && $c) Step::$progress[$str]['c'] = $c;
    Step::$progress[$str]['i'] = Step::$progress[$str]['i'] >= Step::$progress[$str]['c'] || $isStr ? Step::$progress[$str]['c'] : Step::$progress[$str]['i'];
    
    preg_match_all('/(?P<c>[\x{4e00}-\x{9fa5}])/u', $str . ($isStr ? $c : ''), $matches);

    Step::$size = memory_get_usage () > Step::$size ? memory_get_usage () : Step::$size;
    $size = Step::memoryUnit (Step::$size - Step::$nowSize);
    $show = sprintf (' ' . self::color ('➜', 'W') . ' ' . self::color ($str . '(' . Step::$progress[$str]['i'] . '/' . Step::$progress[$str]['c'] . ')', 'g') . " - % 3d%% " . ($isStr ? '- ' . self::color ('完成！', 'C') : ''), Step::$progress[$str]['c'] ? ceil ((Step::$progress[$str]['i'] * 100) / Step::$progress[$str]['c']) : 100);
    if (Step::$isCli) echo sprintf ("\r% -" . (91 + count ($matches['c']) + ($isStr ? 12 : 0)) . "s" .  self::color (sprintf ('% 7s', $size[0]), 'W') . ' ' . $size[1] . " " . ($isStr ? "\n" : ''), $show, 10);
  }
  public static function start () {
    Step::$startTime = microtime (true);
    if (Step::$isCli) echo "\n" . str_repeat ('=', 80) . "\n";
    if (Step::$isCli) echo ' ' . self::color ('◎ 執行開始 ◎', 'P') . str_repeat (' ', 48) . '[' . self::color ('OA S3 Tools v1.0', 'y') . "]\n";
  }
  public static function end () {
    if (Step::$isCli) echo str_repeat ('=', 80) . "\n";
    if (Step::$isCli) echo ' ' . self::color ('◎ 執行結束 ◎', 'P') . "\n";
    if (Step::$isCli) echo str_repeat ('=', 80) . "\n";
  }
  public static function showUrl () {
    if (Step::$isCli) echo "\n";
    if (Step::$isCli) echo " " . self::color ('➜', 'R') . " " . self::color ('您的網址是', 'G') . "：" . self::color (PROTOCOL . BUCKET . '/', 'W') . "\n\n";
    if (Step::$isCli) echo str_repeat ('=', 80) . "\n";
  }
  public static function memoryUnit ($size) {
    $units = array ('B','KB','MB','GB','TB','PB');
    return array (@round ($size / pow (1024, ($i = floor (log ($size, 1024)))), 2), $units[$i]);
  }
  public static function usage () {
    if (Step::$isCli) echo str_repeat ('=', 80) . "\n";
    $size = Step::memoryUnit (memory_get_usage ());
    if (Step::$isCli) echo ' ' . self::color ('➜', 'W') . ' ' . self::color ('使用記憶體：', 'R') . '' . self::color ($size[0], 'W') . ' ' . $size[1] . "\n";
    if (Step::$isCli) echo str_repeat ('-', 80) . "\n";

    if (Step::$isCli) echo ' ' . self::color ('➜', 'W') . ' ' . self::color ('執行時間：', 'R') . '' . self::color (round (microtime (true) - Step::$startTime, 4), 'W') . ' 秒' . "\n";
  }
  public static function setUploadDirs ($args = array ()) {
    Step::$uploadDirs = $args;
  }

  public static function error ($errors = array ()) {
    if (Step::$isCli) echo "\n" . str_repeat ('=', 80) . "\n";
    if (Step::$isCli) echo " " . self::color ('➜', 'W') . ' ' . self::color ('有發生錯誤！', 'r') . "\n";
    if (Step::$isCli) echo $errors ? str_repeat ('-', 80) . "\n" . implode ("\n" . str_repeat ('-', 80) . "\n", $errors) . "\n" : "";
    if (Step::$isCli) echo str_repeat ('=', 80) . "\n";
    
    if (!Step::$isCli) {
      $message = 'Error';
      $code = 405;

      $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;
      if (substr (php_sapi_name (), 0, 3) == 'cgi') header ('Status: ' . $code . ' ' . $message, true);
      elseif (($server_protocol == 'HTTP/1.1') || ($server_protocol == 'HTTP/1.0')) header ($server_protocol . ' ' . $code . ' ' . $message, true, $code);
      else header ('HTTP/1.1 ' . $code . ' ' . $message, true, $code);
      header ('Content-Type: application/json');
      echo json_encode (array (
          'message' => $errors
        ));
    }
    exit ();
  }
  public static function newLine ($char, $str = '', $c = 0) {
    if (Step::$isCli) echo str_repeat ($char, 80) . "\n";
    Step::$nowSize = Step::$size = memory_get_usage ();
    if ($str) Step::progress ($str, $c);
  }
  public static function init () {
    $paths = array (PATH, PATH_ASSET, PATH_SITEMAP, PATH_ARTICLES, PATH_VIDEOS, PATH_ALBUMS, PATH_ALBUM, PATH_VIDEOS, PATH_VIDEO, PATH_TAGS, PATH_ARTICLE, PATH_TMP, PATH_IMG_OG_TMP);
    
    Step::newLine ('-', '初始化環境與變數', count ($paths));

    if ($errors = array_filter (array_map (function ($path) {
        if (!file_exists ($path)) Step::mkdir777 ($path);
        Step::progress ('初始化環境與變數');
        return !(is_dir ($path) && is_writable ($path)) ? ' 目錄：' . $path : '';
      }, $paths))) Step::error ($errors);

    Step::progress ('初始化環境與變數', '完成！');
  }
  public static function initS3 ($access, $secret) {
    Step::newLine ('-', '初始化 S3 工具');
    
    try {
      if (!S3::init ($access, $secret)) throw new Exception ('初始化失敗！');
    } catch (Exception $e) { Step::error (array (' ' . $e->getMessage ())); }
    
    Step::progress ('初始化 S3 工具', '完成！');
  }
  public static function listLocalFiles () {
    Step::newLine ('-', '列出即將上傳所有檔案');

    $uploadDirs = array (); foreach (Step::$uploadDirs as $key => $value) array_push ($uploadDirs, array ('path' => PATH . $key, 'formats' => $value));

    Step::$localFiles = self::array2dTo1d (array_map (function ($uploadDir) {
        $files = array ();
        Step::mergeArrayRecursive (Step::directoryMap ($uploadDir['path']), $files, $uploadDir['path']);
        $files = array_filter ($files, function ($file) use ($uploadDir) { return in_array (pathinfo ($file, PATHINFO_EXTENSION), $uploadDir['formats']); });
        Step::progress ('列出即將上傳所有檔案');
        return array_map (function ($file) {
          
          if (MINIFY) {
            $bom = pack ('H*','EFBBBF');
            switch (pathinfo ($file, PATHINFO_EXTENSION)) {
              case 'html': Step::writeFile ($file, preg_replace ("/^$bom/", '', HTMLMin::minify (Step::readFile ($file)))); break;
              case 'css': Step::writeFile ($file, preg_replace ("/^$bom/", '', CSSMin::minify (Step::readFile ($file)))); break;
              case 'js': Step::writeFile ($file, preg_replace ("/^$bom/", '', JSMin::minify (Step::readFile ($file)))); break;
            }
          }

          return array ('path' => $file, 'md5' => md5_file ($file), 'uri' => preg_replace ('/^(' . preg_replace ('/\//', '\/', PATH) . ')/', '', $file));
        }, $files);
      }, $uploadDirs));

    Step::progress ('列出即將上傳所有檔案', '完成！');
  }
  public static function listS3Files () {
    try {
      Step::newLine ('-', '列出 S3 上所有檔案', count ($list = S3::getBucket (BUCKET)));
      Step::$s3Files = array_filter ($list, function ($file) {
        Step::progress ('列出 S3 上所有檔案');
        return $file['name'];
      });
    } catch (Exception $e) { Step::error (array (' ' . $e->getMessage ())); }

    Step::progress ('列出 S3 上所有檔案', '完成！');
  }
  public static function filterLocalFiles () {
    Step::newLine ('-', '過濾需要上傳檔案');

    $files = array_filter (Step::$localFiles, function ($file) {
      foreach (Step::$s3Files as $s3File)
        if (($s3File['name'] == $file['uri']) && ($s3File['hash'] == $file['md5']))
          return false;
      Step::progress ('過濾需要上傳檔案');
      return $file;
    });
    Step::progress ('過濾需要上傳檔案', '完成！');

    return $files;
  }
  public static function uploadLocalFiles ($files) {
    Step::newLine ('-', '上傳檔案', count ($files));
    
    if ($errors = array_filter (array_map (function ($file) {
        try {
          Step::progress ('上傳檔案');
          return !S3::putFile ($file['path'], BUCKET, $file['uri']) ? ' 檔案：' . $file['path'] : '';
        } catch (Exception $e) {
          return ' 檔案：' . $file['path'];
        }
      }, $files))) Step::error ($errors);
    Step::progress ('上傳檔案', '完成！');
  }
  public static function filterS3Files () {
    Step::newLine ('-', '過濾需要刪除檔案');

    $files = array_filter (Step::$s3Files, function ($s3File) {
      foreach (Step::$localFiles as $localFile) if ($s3File['name'] == $localFile['uri']) return false;
      Step::progress ('過濾需要刪除檔案');
      return true;
    });

    Step::progress ('過濾需要刪除檔案', '完成！');

    return $files;
  }
  public static function deletwS3Files ($files) {
    Step::newLine ('-', '刪除 S3 上需要刪除的檔案', count ($files));

    if ($errors = array_filter (array_map (function ($file) {
        try {
          Step::progress ('刪除 S3 上需要刪除的檔案');
          return !S3::deleteObject (BUCKET, $file['name']) ? ' 檔案：' . $file['name'] : '';
        } catch (Exception $e) {
          return ' 檔案：' . $file['name'];
        }
      }, $files))) Step::error ($errors);
    Step::progress ('刪除 S3 上需要刪除的檔案', '完成！');
  }
  public static function params ($params, $keys) {
    $ks = $return = $result = array ();

    if (!$params) return $return;
    if (!$keys) return $return;

    foreach ($keys as $key)
      if (is_array ($key)) foreach ($key as $k) array_push ($ks, $k);
      else  array_push ($ks, $key);

    $key = null;

    foreach ($params as $param)
      if (in_array ($param, $ks)) if (!isset ($result[$key = $param])) $result[$key] = array (); else ;
      else if (isset ($result[$key])) array_push ($result[$key], $param); else ;

    foreach ($keys as $key)
      if (is_array ($key))  foreach ($key as $k) if (isset ($result[$k])) $return[$key[0]] = isset ($return[$key[0]]) ? array_merge ($return[$key[0]], $result[$k]) : $result[$k]; else;
      else if (isset ($result[$key])) $return[$key] = isset ($return[$key]) ? array_merge ($return[$key], $result[$key]) : $result[$key]; else;

    return $return;
  }
  public static function directoryList ($sourceDir, $hidden = false) {
    if ($fp = @opendir ($sourceDir = rtrim ($sourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR)) {
      $filedata = array ();

      while (false !== ($file = readdir ($fp)))
        if (!(!trim ($file, '.') || (($hidden == false) && ($file[0] == '.'))))
          array_push ($filedata, $file);

      closedir ($fp);
      return $filedata;
    }
    return array ();
  }
  public static function directoryMap ($sourceDir, $directoryDepth = 0, $hidden = false) {
    if ($fp = @opendir ($sourceDir)) {
      $filedata = array ();
      $new_depth  = $directoryDepth - 1;
      $sourceDir = rtrim ($sourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

      while (false !== ($file = readdir ($fp))) {
        if (trim ($file, '.') == '' || (($hidden === false) && ($file[0] === '.')) || is_link ($file) || ($file == 'cmd')) continue;

        if ((($directoryDepth < 1) || ($new_depth > 0)) && @is_dir ($sourceDir . $file)) $filedata[$file] = Step::directoryMap ($sourceDir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden);
        else array_push ($filedata, $file);
      }

      closedir ($fp);
      return $filedata;
    }

    return false;
  }
  public static function mergeArrayRecursive ($files, &$a, $k = null) {
    if (!($files && is_array ($files))) return false;
    foreach ($files as $key => $file)
      if (is_array ($file)) $key . Step::mergeArrayRecursive ($file, $a, ($k ? rtrim ($k, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '') . $key);
      else array_push ($a, ($k ? rtrim ($k, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '') . $file);
  }
  public static function color ($string, $fColor = null, $background_color = null, $is_print = false) {
    if (!strlen ($string)) return "";
    $sColor = "";
    $keys = array ('n' => '30', 'w' => '37', 'b' => '34', 'g' => '32', 'c' => '36', 'r' => '31', 'p' => '35', 'y' => '33');
    if ($fColor && in_array (strtolower ($fColor), array_map ('strtolower', array_keys ($keys)))) {
      $fColor = !in_array (ord ($fColor[0]), array_map ('ord', array_keys ($keys))) ? in_array (ord ($fColor[0]) | 0x20, array_map ('ord', array_keys ($keys))) ? '1;' . $keys[strtolower ($fColor[0])] : null : $keys[$fColor[0]];
      $sColor .= $fColor ? "\033[" . $fColor . "m" : "";
    }
    $sColor .= $background_color && in_array (strtolower ($background_color), array_map ('strtolower', array_keys ($keys))) ? "\033[" . ($keys[strtolower ($background_color[0])] + 10) . "m" : "";

    if (substr ($string, -1) == "\n") { $string = substr ($string, 0, -1); $has_new_line = true; } else { $has_new_line = false; }
    $sColor .=  $string . "\033[0m";
    $sColor = $sColor . ($has_new_line ? "\n" : "");
    if ($is_print) printf ($sColor);
    return $sColor;
  }
  public static function array2dTo1d ($array) {
    $messages = array ();
    foreach ($array as $key => $value)
      if (is_array ($value)) $messages = array_merge ($messages, $value);
      else array_push ($messages, $value);
    return $messages;
  }
  public static function readFile ($file) {
    if (!file_exists ($file)) return false;
    if (function_exists ('file_get_contents')) return file_get_contents ($file);
    if (!$fp = @fopen ($file, 'rb')) return false;

    $data = '';
    flock ($fp, LOCK_SH);
    if (filesize ($file) > 0) $data =& fread ($fp, filesize ($file));
    flock ($fp, LOCK_UN);
    fclose ($fp);

    return $data;
  }
  public static function writeFile ($path, $data, $mode = 'wb') {
    if (!$fp = @fopen ($path, $mode)) return false;

    flock($fp, LOCK_EX);
    fwrite($fp, $data);
    flock($fp, LOCK_UN);
    fclose($fp);

    chmod ($path, 0777);

    return true;
  }
  public static function loadView ($__o__p__ = '', $__o__d__ = array ()) {
    if (!$__o__p__) return '';

    extract ($__o__d__);
    ob_start ();
    if (((bool)@ini_get ('short_open_tag') === FALSE) && (false == TRUE)) echo eval ('?>' . preg_replace ("/;*\s*\?>/u", "; ?>", str_replace ('<?=', '<?php echo ', file_get_contents ($__o__p__))));
    else include $__o__p__;
    $buffer = ob_get_contents ();
    @ob_end_clean ();

    return $buffer;
  }
  

  public static function notCil () {
    Step::$isCli = false;
  }
  public static function mkdir777 ($path) {
    $oldmask = umask (0);
    @mkdir ($path, 0777, true);
    umask ($oldmask);
    return true;
  }

  public static function apis () {
    Step::newLine ('-', '取得 API');
    $articles = array_map (function ($article) { return array_merge ($article, array ( 'url' => article_url ($article['id'], $article['title']))); }, json_decode (Step::readFile (PATH_APIS . 'articles.json'), true));
    $tags     = array_values (array_map (function ($tag) use ($articles) { $articles = array_values (array_filter ($articles, function ($article) use ($tag) { return $article['tag'] == $tag; })); return $articles ? array ('name' => $tag, 'url' => sprintf (URL_TAG_ARTICLES, oa_url_encode ($tag)), 'articles' => $articles) : array (); }, array_unique (self::columnArray ($articles, 'tag'))));
    $paths    = json_decode (Step::readFile (PATH_APIS . 'paths.json'), true);
    $albums   = array_map (function ($album) { return array_merge ($album, array ( 'url' => album_url ($album['id'], $album['title']))); }, json_decode (Step::readFile (PATH_APIS . 'albums.json'), true));
    $videos   = array_map (function ($video) { return array_merge ($video, array ( 'url' => video_url ($video['id'], $video['title']))); }, json_decode (Step::readFile (PATH_APIS . 'videos.json'), true));
    
    $home   = array_merge (array ('user' => array ('fbid' => OA_FB_UID, 'name' => OA), 'orm' => 'Home', 'title' => '首頁', 'url' => PAGE_URL_INDEX), json_decode (Step::readFile (PATH_APIS . 'home.json'), true));
    $author   = array_merge (array ('user' => array ('fbid' => OA_FB_UID, 'name' => OA), 'orm' => 'Author', 'title' => '關於作者', 'url' => PAGE_URL_AUTHOR), json_decode (Step::readFile (PATH_APIS . 'author.json'), true));
    $license   = array_merge (array ('user' => array ('fbid' => OA_FB_UID, 'name' => OA), 'orm' => 'License', 'title' => '授權聲明', 'url' => PAGE_URL_LICENSE), json_decode (Step::readFile (PATH_APIS . 'license.json'), true));
    
    Step::$apis = array (
      'articles' => $articles,
      'tags' => $tags,
      'paths' => $paths,
      'albums' => $albums,
      'videos' => $videos,
      'home' => $home,
      'author' => $author,
      'license' => $license,
    );
    Step::progress ('取得 API', '完成！');
  }
  public static function writeIndexHtml () {
    Step::newLine ('-', '更新 Index HTML');

    $obj = Step::$apis['home'];
    if (!Step::writeFile (PATH . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($obj['content']), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => $obj['url']),
              array ('property' => 'og:title', 'content' => $obj['title'] . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($obj['content'], false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c', strtotime ($obj['updated_at']))),
              array ('property' => 'article:published_time', 'content' => date ('c', strtotime ($obj['created_at']))),
              array ('property' => 'og:image', 'content' => $ogImgUrl = $obj['cover']['c1200x630'], 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => $obj['url']),
              array ('rel' => 'alternate', 'href' => $obj['url'], 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
              '@context' => 'http://schema.org', '@type' => 'Article',
              'mainEntityOfPage' => array (
                '@type' => 'WebPage',
                '@id' => $obj['url']),
              'headline' => $obj['title'],
              'image' => array ('@type' => 'ImageObject', 'url' => $ogImgUrl, 'height' => 630, 'width' => 1200),
              'datePublished' => date ('c', strtotime ($obj['created_at'])),
              'dateModified' => date ('c', strtotime ($obj['updated_at'])),
              'author' => array (
                  '@type' => 'Person', 'name' => $obj['user']['name'], 'url' => facebook_url ($obj['user']['fbid']),
                  'image' => array ('@type' => 'ImageObject', 'url' => avatar_url ($obj['user']['fbid'], 300, 300), 'height' => 300, 'width' => 300)
                ),
              'publisher' => array (
                  '@type' => 'Organization', 'name' => TITLE,
                  'logo' => array ('@type' => 'ImageObject', 'url' => AMP_IMG_600_60, 'width' => 600, 'height' => 60)
                ),
              'description' => mb_strimwidth (remove_ckedit_tag ($obj['content']), 0, 150, '…','UTF-8')
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => $obj['url'], 'title' => $obj['title'])),
          'css' => css ('css/public' . CSS, 'css/article' . CSS),
          'js' => js ('js/public' . JS, 'js/article' . JS),
          'now' => 'index',
          'content' => Step::loadView (PATH_VIEWS . '_article' . PHP, array (
              'obj' => $obj
            )),
        ))))) Step::error ();
  
    array_push (Step::$sitemapInfos, array (
      'uri' => '/' . 'index' . HTML,
      'priority' => '0.5',
      'changefreq' => 'daily',
      'lastmod' => date ('c'),
    ));

    Step::progress ('更新 Index HTML', '完成！');
  }
  public static function writeAuthorHtml () {
    Step::newLine ('-', '更新 Author HTML');
    $obj = Step::$apis['author'];

    if (!Step::writeFile (PATH . 'author' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($obj['content']), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => $obj['url']),
              array ('property' => 'og:title', 'content' => $obj['title'] . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($obj['content'], false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c', strtotime ($obj['updated_at']))),
              array ('property' => 'article:published_time', 'content' => date ('c', strtotime ($obj['created_at']))),
              array ('property' => 'og:image', 'content' => $ogImgUrl = $obj['cover']['c1200x630'], 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => $obj['url']),
              array ('rel' => 'alternate', 'href' => $obj['url'], 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
              '@context' => 'http://schema.org', '@type' => 'Article',
              'mainEntityOfPage' => array (
                '@type' => 'WebPage',
                '@id' => $obj['url']),
              'headline' => $obj['title'],
              'image' => array ('@type' => 'ImageObject', 'url' => $ogImgUrl, 'height' => 630, 'width' => 1200),
              'datePublished' => date ('c', strtotime ($obj['created_at'])),
              'dateModified' => date ('c', strtotime ($obj['updated_at'])),
              'author' => array (
                  '@type' => 'Person', 'name' => $obj['user']['name'], 'url' => facebook_url ($obj['user']['fbid']),
                  'image' => array ('@type' => 'ImageObject', 'url' => avatar_url ($obj['user']['fbid'], 300, 300), 'height' => 300, 'width' => 300)
                ),
              'publisher' => array (
                  '@type' => 'Organization', 'name' => TITLE,
                  'logo' => array ('@type' => 'ImageObject', 'url' => AMP_IMG_600_60, 'width' => 600, 'height' => 60)
                ),
              'description' => mb_strimwidth (remove_ckedit_tag ($obj['content']), 0, 150, '…','UTF-8')
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => $obj['url'], 'title' => $obj['title'])),
          'css' => css ('css/public' . CSS, 'css/article' . CSS),
          'js' => js ('js/public' . JS, 'js/article' . JS),
          'now' => 'author',
          'content' => Step::loadView (PATH_VIEWS . '_article' . PHP, array (
              'obj' => $obj
            )),
        ))))) Step::error ();

    array_push (Step::$sitemapInfos, array (
      'uri' => '/' . 'author' . HTML,
      'priority' => '0.3',
      'changefreq' => 'weekly',
      'lastmod' => date ('c'),
    ));

    Step::progress ('更新 Author HTML', '完成！');
  }
  public static function writeLicenseHtml () {
    Step::newLine ('-', '更新 License HTML');
    $obj = Step::$apis['license'];

    if (!Step::writeFile (PATH . 'license' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($obj['content']), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => $obj['url']),
              array ('property' => 'og:title', 'content' => $obj['title'] . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($obj['content'], false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c', strtotime ($obj['updated_at']))),
              array ('property' => 'article:published_time', 'content' => date ('c', strtotime ($obj['created_at']))),
              array ('property' => 'og:image', 'content' => $ogImgUrl = $obj['cover']['c1200x630'], 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => $obj['url']),
              array ('rel' => 'alternate', 'href' => $obj['url'], 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
              '@context' => 'http://schema.org', '@type' => 'Article',
              'mainEntityOfPage' => array (
                '@type' => 'WebPage',
                '@id' => $obj['url']),
              'headline' => $obj['title'],
              'image' => array ('@type' => 'ImageObject', 'url' => $ogImgUrl, 'height' => 630, 'width' => 1200),
              'datePublished' => date ('c', strtotime ($obj['created_at'])),
              'dateModified' => date ('c', strtotime ($obj['updated_at'])),
              'author' => array (
                  '@type' => 'Person', 'name' => $obj['user']['name'], 'url' => facebook_url ($obj['user']['fbid']),
                  'image' => array ('@type' => 'ImageObject', 'url' => avatar_url ($obj['user']['fbid'], 300, 300), 'height' => 300, 'width' => 300)
                ),
              'publisher' => array (
                  '@type' => 'Organization', 'name' => TITLE,
                  'logo' => array ('@type' => 'ImageObject', 'url' => AMP_IMG_600_60, 'width' => 600, 'height' => 60)
                ),
              'description' => mb_strimwidth (remove_ckedit_tag ($obj['content']), 0, 150, '…','UTF-8')
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => $obj['url'], 'title' => $obj['title'])),
          'css' => css ('css/public' . CSS, 'css/article' . CSS),
          'js' => js ('js/public' . JS, 'js/article' . JS),
          'now' => 'license',
          'content' => Step::loadView (PATH_VIEWS . '_article' . PHP, array (
              'obj' => $obj
            )),
        ))))) Step::error ();

    array_push (Step::$sitemapInfos, array (
      'uri' => '/' . 'license' . HTML,
      'priority' => '0.3',
      'changefreq' => 'weekly',
      'lastmod' => date ('c'),
    ));

    Step::progress ('更新 License HTML', '完成！');
  }

  public static function writeArticlesHtml () {
    Step::newLine ('-', '更新 Articles HTML');

    $limit = 10;
    // include_once PATH_CMD_LIBS . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . 'ImageUtility.php';

    if ($total = count (Step::$apis['articles'])) {
      for ($offset = 0; $offset < $total; $offset += $limit) {
        $i = 0;
        $articles = array_slice (Step::$apis['articles'], $offset, $limit);
        // $ogimage_path = PATH_IMG_OG_TMP . ($tmpName = uniqid (rand () . '_') . '.jpg');
        // try { ImageUtility::photos (array_values (array_filter (array_map (function ($article) { return download_web_file ($article['cover']['c600x315'], PATH_TMP . pathinfo ($article['cover']['c600x315'], PATHINFO_BASENAME)); }, $articles))), $ogimage_path); } catch (Exception $e) { }

        if (!Step::writeFile (PATH_ARTICLES . (!$offset ? 'index' : $offset) . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($description = TITLE . '有著豐富的相關文章，你知道 ' . implode (',', Step::columnArray ($articles, 'title')) . ' 嗎？不知道的朋友沒關係，趕緊來看看吧，說不定會讓你對北港更加了解喔！'), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => URL_ARTICLES . (!$offset ? 'index' : $offset) . HTML),
              array ('property' => 'og:title', 'content' => '所有文章' . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($description, false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c')),
              array ('property' => 'article:published_time', 'content' => date ('c')),
              array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG, 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => URL_ARTICLES . (!$offset ? 'index' : $offset) . HTML),
              array ('rel' => 'alternate', 'href' => URL_ARTICLES . (!$offset ? 'index' : $offset) . HTML, 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
            '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
            "itemListElement" => array_map (function ($article) use ($offset, $i) {
              return array (
                  "@type" => "ListItem",
                  "position" => $offset + $i,
                  "item" => array (
                      "@id" => $article['url'],
                      "name" => $article['title'],
                      "description" => mb_strimwidth (remove_ckedit_tag ($article['content']), 0, 150, '…','UTF-8'),
                      "image" => array ('@type' => 'ImageObject', 'url' => $article['cover']['c600x315'], 'height' => 630, 'width' => 1200),
                      "url" => $article['url'],
                    )
                  );
              }, $articles)
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => URL_ARTICLES . (!$offset ? 'index' : $offset) . HTML, 'title' => '所有文章')),
          'css' => css ('css/public' . CSS, 'css/articles' . CSS),
          'js' => js ('js/public' . JS),
          'now' => 'articles',
          'content' => Step::loadView (PATH_VIEWS . 'articles' . PHP, array (
              'tag' => '所有文章',
              'articles' => $articles,
              'offset' => $offset,
              'pagination' => Pagination::initialize (array (
                  'total_rows' => $total, 'per_page' => $limit, 
                  'base_url' => URL_ARTICLES,
                  'offset' => $offset,
                ))->create_links (),
            )),
          ))))) Step::error ();

        array_push (Step::$sitemapInfos, array (
          'uri' => '/articles/' . (!$offset ? 'index' : $offset) . HTML,
          'priority' => '0.5',
          'changefreq' => 'daily',
          'lastmod' => date ('c'),
        ));
      }
    } else {
      if (!Step::writeFile (PATH_ARTICLES . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => URL_ARTICLES . 'index' . HTML),
              array ('property' => 'og:title', 'content' => '所有文章' . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION, false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c')),
              array ('property' => 'article:published_time', 'content' => date ('c')),
              array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG, 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => URL_ARTICLES . 'index' . HTML),
              array ('rel' => 'alternate', 'href' => URL_ARTICLES . 'index' . HTML, 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
            '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
            "itemListElement" => array_map (function ($article) {
              return array (
                  "@type" => "ListItem",
                  "position" => 0,
                  "item" => array (
                      "@id" => $article['url'],
                      "name" => $article['title'],
                      "description" => mb_strimwidth (remove_ckedit_tag ($article['content']), 0, 150, '…','UTF-8'),
                      "image" => array ('@type' => 'ImageObject', 'url' => $article['cover']['c600x315'], 'height' => 630, 'width' => 1200),
                      "url" => $article['url'],
                    )
                  );
              }, array ())
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => URL_ARTICLES . 'index' . HTML, 'title' => '所有文章')),
          'css' => css ('css/public' . CSS, 'css/articles' . CSS),
          'js' => js ('js/public' . JS),
          'now' => 'articles',
          'content' => Step::loadView (PATH_VIEWS . 'articles' . PHP, array (
              'tag' => '所有文章',
              'articles' => array (),
              'offset' => 0,
              'pagination' => '',
            )),
        ))))) Step::error ();

      array_push (Step::$sitemapInfos, array (
        'uri' => '/articles/index' . HTML,
        'priority' => '0.5',
        'changefreq' => 'daily',
        'lastmod' => date ('c'),
      ));
    }

    foreach (Step::$apis['tags'] as $tag) {
      $total = count ($tag['articles']);
      $tag_path = sprintf (PATH_TAG_ARTICLES, oa_url ($tag['name']));

      if (!file_exists ($tag_path)) Step::mkdir777 ($tag_path);

      if ($total) {
        for ($offset = 0; $offset < $total; $offset += $limit) {
          $i = 0;
          $articles = array_slice ($tag['articles'], $offset, $limit);
          $ogimage_path = PATH_IMG_OG_TMP . ($tmpName = uniqid (rand () . '_') . '.jpg');
          // try { ImageUtility::photos (array_values (array_filter (array_map (function ($article) { return download_web_file (str_replace('https', 'http', $article['cover']['c600x315']), PATH_TMP . pathinfo ($article['cover']['c600x315'], PATHINFO_BASENAME)); }, $articles))), $ogimage_path); } catch (Exception $e) { }

          if (!Step::writeFile ($tag_path . (!$offset ? 'index' : $offset) . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
              'meta' => meta (
                array ('name' => 'keywords', 'content' => KEYWORDS),
                array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($description = TITLE . '有著豐富的 「' . $tag['name'] . '」 相關文章，你知道 ' . implode (',', Step::columnArray ($articles, 'title')) . ' 嗎？不知道的朋友沒關係，趕緊來看看吧，說不定會讓你對北港更加了解喔！'), 0, 150, '…','UTF-8')),
                array ('property' => 'og:url', 'content' => $tag['url'] . (!$offset ? 'index' : $offset) . HTML),
                array ('property' => 'og:title', 'content' => $tag['name'] . ' - ' . TITLE),
                array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($description, false), 0, 300, '…','UTF-8')),
                array ('property' => 'article:author', 'content' => OA_FB_URL),
                array ('property' => 'article:modified_time', 'content' => date ('c')),
                array ('property' => 'article:published_time', 'content' => date ('c')),
                array ('property' => 'og:image', 'content' => $ogImgUrl = $articles[0]['cover']['c1200x630'], 'alt' => TITLE),
                array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
                array ('property' => 'og:image:width', 'content' => 1200),
                array ('property' => 'og:image:height', 'content' => 630)
              ),
              'link' => myLink (
                  array ('rel' => 'canonical', 'href' => $tag['url'] . (!$offset ? 'index' : $offset) . HTML),
                  array ('rel' => 'alternate', 'href' => $tag['url'] . (!$offset ? 'index' : $offset) . HTML, 'hreflang' => 'zh-Hant')
                ),
              'jsonLd' => array (
                '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
                "itemListElement" => array_map (function ($article) use ($offset, $i) {
                  return array (
                      "@type" => "ListItem",
                      "position" => $offset + $i,
                      "item" => array (
                          "@id" => $article['url'],
                          "name" => $article['title'],
                          "description" => mb_strimwidth (remove_ckedit_tag ($article['content']), 0, 150, '…','UTF-8'),
                          "image" => array ('@type' => 'ImageObject', 'url' => $article['cover']['c600x315'], 'height' => 630, 'width' => 1200),
                          "url" => $article['url'],
                        )
                      );
                  }, $articles)
                ),
              'scopes' => array (
                array ('url' => URL, 'title' => TITLE),
                array ('url' => $tag['url'] . (!$offset ? 'index' : $offset) . HTML, 'title' => $tag['name'])),
              'css' => css ('css/public' . CSS, 'css/articles' . CSS),
              'js' => js ('js/public' . JS),
              'now' => 'articles',
              'content' => Step::loadView (PATH_VIEWS . 'articles' . PHP, array (
                  'tag' => $tag['name'],
                  'articles' => $articles,
                  'offset' => $offset,
                  'pagination' => Pagination::initialize (array (
                      'total_rows' => $total, 'per_page' => $limit, 
                      'base_url' => $tag['url'],
                      'offset' => $offset,
                    ))->create_links (),
                )),
            ))))) Step::error ();

          array_push (Step::$sitemapInfos, array (
            'uri' => '/tags/' . oa_url_encode ($tag['name']) . '/articles/' . (!$offset ? 'index' : $offset) . HTML,
            'priority' => '0.5',
            'changefreq' => 'daily',
            'lastmod' => date ('c'),
          ));
        }
      } else {
        if (!Step::writeFile ($tag_path . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
              'meta' => meta (
                  array ('name' => 'keywords', 'content' => KEYWORDS),
                  array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION), 0, 150, '…','UTF-8')),
                  array ('property' => 'og:url', 'content' => $tag['url'] . 'index' . HTML),
                  array ('property' => 'og:title', 'content' => $tag['name'] . ' - ' . TITLE),
                  array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION, false), 0, 300, '…','UTF-8')),
                  array ('property' => 'article:author', 'content' => OA_FB_URL),
                  array ('property' => 'article:modified_time', 'content' => date ('c')),
                  array ('property' => 'article:published_time', 'content' => date ('c')),
                  array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG, 'alt' => TITLE),
                  array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
                  array ('property' => 'og:image:width', 'content' => 1200),
                  array ('property' => 'og:image:height', 'content' => 630)
                ),
              'link' => myLink (
                  array ('rel' => 'canonical', 'href' => $tag['url'] . 'index' . HTML),
                  array ('rel' => 'alternate', 'href' => $tag['url'] . 'index' . HTML, 'hreflang' => 'zh-Hant')
                ),
              'jsonLd' => array (
                '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
                "itemListElement" => array_map (function ($article) {
                  return array (
                      "@type" => "ListItem",
                      "position" => 0,
                      "item" => array (
                          "@id" => $article['url'],
                          "name" => $article['title'],
                          "description" => mb_strimwidth (remove_ckedit_tag ($article['content']), 0, 150, '…','UTF-8'),
                          "image" => array ('@type' => 'ImageObject', 'url' => $article['cover']['c600x315'], 'height' => 630, 'width' => 1200),
                          "url" => $article['url'],
                        )
                      );
                  }, array ())
                ),
              'scopes' => array (
                array ('url' => URL, 'title' => TITLE),
                array ('url' => $tag['url'] . 'index' . HTML, 'title' => $tag['name'])),
              'css' => css ('css/public' . CSS, 'css/articles' . CSS),
              'js' => js ('js/public' . JS),
              'now' => 'articles',
              'content' => Step::loadView (PATH_VIEWS . 'articles' . PHP, array (
                  'tag' => $tag['name'],
                  'articles' => array (),
                  'offset' => 0,
                  'pagination' => '',
                )),
          ))))) Step::error ();

        array_push (Step::$sitemapInfos, array (
          'uri' => '/tags/' . oa_url_encode ($tag['name']) . '/articles/index' . HTML,
          'priority' => '0.5',
          'changefreq' => 'daily',
          'lastmod' => date ('c'),
        ));
      }
    }

    foreach (Step::$apis['articles'] as $article) {
      $path = PATH_ARTICLE . $article['id'] . '-' . oa_url ($article['title'] . HTML);
      $mores = array_filter (Step::$apis['articles'], function ($a) use ($article) {
          return $a['id'] != $article['id'];
        });
      shuffle ($mores);

      if (!Step::writeFile ($path, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($article['content']), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => $article['url']),
              array ('property' => 'og:title', 'content' => $article['title'] . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($article['content'], false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c', strtotime ($article['updated_at']))),
              array ('property' => 'article:published_time', 'content' => date ('c', strtotime ($article['created_at']))),
              array ('property' => 'og:image', 'content' => $ogImgUrl = $article['cover']['c1200x630'], 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => $article['url']),
              array ('rel' => 'alternate', 'href' => $article['url'], 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
              '@context' => 'http://schema.org', '@type' => 'Article',
              'mainEntityOfPage' => array (
                '@type' => 'WebPage',
                '@id' => $article['url']),
              'headline' => $article['title'],
              'image' => array ('@type' => 'ImageObject', 'url' => $ogImgUrl, 'height' => 630, 'width' => 1200),
              'datePublished' => date ('c', strtotime ($article['created_at'])),
              'dateModified' => date ('c', strtotime ($article['updated_at'])),
              'author' => array (
                  '@type' => 'Person', 'name' => $article['user']['name'], 'url' => facebook_url ($article['user']['fbid']),
                  'image' => array ('@type' => 'ImageObject', 'url' => avatar_url ($article['user']['fbid'], 300, 300), 'height' => 300, 'width' => 300)
                ),
              'publisher' => array (
                  '@type' => 'Organization', 'name' => TITLE,
                  'logo' => array ('@type' => 'ImageObject', 'url' => AMP_IMG_600_60, 'width' => 600, 'height' => 60)
                ),
              'description' => mb_strimwidth (remove_ckedit_tag ($article['content']), 0, 150, '…','UTF-8')
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => URL_ARTICLES . 'index' . HTML, 'title' => '所有文章'),
            array ('url' => $article['url'], 'title' => $article['title'])),
          
          'css' => css ('css/public' . CSS, 'css/article' . CSS),
          'js' => js ('js/public' . JS, 'js/article' . JS),
          'now' => 'articles',
          'search' => $article['title'],
          'last_url' => URL_ARTICLES . 'index' . HTML,
          'content' => Step::loadView (PATH_VIEWS . 'article' . PHP, array (
              'article' => $article,
              'mores' => array_slice ($mores, 0, 3)
            )),
        ))))) Step::error ();

      array_push (Step::$sitemapInfos, array (
        'uri' => '/article/' . $article['id'] . '-' . oa_url_encode ($article['title']) . HTML,
        'priority' => '0.7',
        'changefreq' => 'daily',
        'lastmod' => date ('c'),
      ));
    }
    Step::progress ('更新 Articles HTML', '完成！');
  }
  public static function writeAlbumsHtml () {
    Step::newLine ('-', '更新 Albums HTML');

    $limit = 12;
    // include_once PATH_CMD_LIBS . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . 'ImageUtility.php';

    if ($total = count (Step::$apis['albums'])) {
      for ($offset = 0; $offset < $total; $offset += $limit) {
        $i = 0;
        $albums = array_slice (Step::$apis['albums'], $offset, $limit);
        // $ogimage_path = PATH_IMG_OG_TMP . ($tmpName = uniqid (rand () . '_') . '.jpg');
        // try { ImageUtility::photos (array_values (array_filter (array_map (function ($album) { return download_web_file ($album['cover']['c600x315'], PATH_TMP . pathinfo ($album['cover']['c600x315'], PATHINFO_BASENAME)); }, $albums))), $ogimage_path); } catch (Exception $e) { }

        if (!Step::writeFile (PATH_ALBUMS . (!$offset ? 'index' : $offset) . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($description = TITLE . '有著豐富的活動相簿，你看過 ' . implode (',', Step::columnArray ($albums, 'title')) . ' 了嗎？不知道的朋友沒關係，趕緊來看看吧，說不定會讓你對北港更加了解喔！'), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => URL_ALBUMS . (!$offset ? 'index' : $offset) . HTML),
              array ('property' => 'og:title', 'content' => '活動相簿' . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($description, false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c')),
              array ('property' => 'article:published_time', 'content' => date ('c')),
              array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG, 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => URL_ALBUMS . (!$offset ? 'index' : $offset) . HTML),
              array ('rel' => 'alternate', 'href' => URL_ALBUMS . (!$offset ? 'index' : $offset) . HTML, 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
            '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
            "itemListElement" => array_map (function ($album) use ($offset, $i) {
              return array (
                  "@type" => "ListItem",
                  "position" => $offset + $i,
                  "item" => array (
                      "@id" => $album['url'],
                      "name" => $album['title'],
                      "description" => mb_strimwidth (remove_ckedit_tag ($album['content']), 0, 150, '…','UTF-8'),
                      "image" => array ('@type' => 'ImageObject', 'url' => $album['cover']['c600x315'], 'height' => 630, 'width' => 1200),
                      "url" => $album['url'],
                    )
                  );
              }, $albums)
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => URL_ALBUMS . (!$offset ? 'index' : $offset) . HTML, 'title' => '活動相簿')),
          'css' => css ('css/public' . CSS, 'css/albums' . CSS),
          'js' => js ('js/public' . JS),
          'now' => 'albums',
          'content' => Step::loadView (PATH_VIEWS . 'albums' . PHP, array (
              'h1' => '活動相簿',
              'albums' => $albums,
              'offset' => $offset,
              'pagination' => Pagination::initialize (array (
                  'total_rows' => $total, 'per_page' => $limit, 
                  'base_url' => URL_ALBUMS,
                  'offset' => $offset,
                ))->create_links (),
            )),
          ))))) Step::error ();

        array_push (Step::$sitemapInfos, array (
          'uri' => '/albums/' . (!$offset ? 'index' : $offset) . HTML,
          'priority' => '0.5',
          'changefreq' => 'daily',
          'lastmod' => date ('c'),
        ));
      }
    } else {
      if (!Step::writeFile (PATH_ALBUMS . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
                array ('name' => 'keywords', 'content' => KEYWORDS),
                array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION), 0, 150, '…','UTF-8')),
                array ('property' => 'og:url', 'content' => URL_ALBUMS . 'index' . HTML),
                array ('property' => 'og:title', 'content' => '活動相簿' . ' - ' . TITLE),
                array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION, false), 0, 300, '…','UTF-8')),
                array ('property' => 'article:author', 'content' => OA_FB_URL),
                array ('property' => 'article:modified_time', 'content' => date ('c')),
                array ('property' => 'article:published_time', 'content' => date ('c')),
                array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG, 'alt' => TITLE),
                array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
                array ('property' => 'og:image:width', 'content' => 1200),
                array ('property' => 'og:image:height', 'content' => 630)
              ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => URL_ALBUMS . 'index' . HTML),
              array ('rel' => 'alternate', 'href' => URL_ALBUMS . 'index' . HTML, 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
            '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
            "itemListElement" => array_map (function ($album) {
              return array (
                  "@type" => "ListItem",
                  "position" => 0,
                  "item" => array (
                      "@id" => $album['url'],
                      "name" => $album['title'],
                      "description" => mb_strimwidth (remove_ckedit_tag ($album['content']), 0, 150, '…','UTF-8'),
                      "image" => array ('@type' => 'ImageObject', 'url' => $album['cover']['c600x315'], 'height' => 630, 'width' => 1200),
                      "url" => $album['url'],
                    )
                  );
              }, array ())
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => URL_ALBUMS . 'index' . HTML, 'title' => '活動相簿')),
          'css' => css ('css/public' . CSS, 'css/albums' . CSS),
          'js' => js ('js/public' . JS),
          'now' => 'albums',
          'content' => Step::loadView (PATH_VIEWS . 'albums' . PHP, array (
              'h1' => '活動相簿',
              'albums' => array (),
              'offset' => 0,
              'pagination' => '',
            )),
        ))))) Step::error ();

      array_push (Step::$sitemapInfos, array (
        'uri' => '/albums/index' . HTML,
        'priority' => '0.5',
        'changefreq' => 'daily',
        'lastmod' => date ('c'),
      ));
    }

    foreach (Step::$apis['albums'] as $album) {
      $path = PATH_ALBUM . $album['id'] . '-' . oa_url ($album['title'] . HTML);

      if (!Step::writeFile ($path, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($album['content'] ? $album['content'] : DESCRIPTION), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => $album['url']),
              array ('property' => 'og:title', 'content' => $album['title'] . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($album['content'] ? $album['content'] : DESCRIPTION, false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c', strtotime ($album['updated_at']))),
              array ('property' => 'article:published_time', 'content' => date ('c', strtotime ($album['created_at']))),
              array ('property' => 'og:image', 'content' => $ogImgUrl = $album['cover']['c1200x630'], 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => $album['url']),
              array ('rel' => 'alternate', 'href' => $album['url'], 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
              '@context' => 'http://schema.org', '@type' => 'Article',
              'mainEntityOfPage' => array (
                '@type' => 'WebPage',
                '@id' => $album['url']),
              'headline' => $album['title'],
              'image' => array ('@type' => 'ImageObject', 'url' => $ogImgUrl, 'height' => 630, 'width' => 1200),
              'datePublished' => date ('c', strtotime ($album['created_at'])),
              'dateModified' => date ('c', strtotime ($album['updated_at'])),
              'author' => array (
                  '@type' => 'Person', 'name' => $album['user']['name'], 'url' => facebook_url ($album['user']['fbid']),
                  'image' => array ('@type' => 'ImageObject', 'url' => avatar_url ($album['user']['fbid'], 300, 300), 'height' => 300, 'width' => 300)
                ),
              'publisher' => array (
                  '@type' => 'Organization', 'name' => TITLE,
                  'logo' => array ('@type' => 'ImageObject', 'url' => AMP_IMG_600_60, 'width' => 600, 'height' => 60)
                ),
              'description' => mb_strimwidth (remove_ckedit_tag ($album['content']), 0, 150, '…','UTF-8')
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => URL_ALBUMS . 'index' . HTML, 'title' => '活動相簿'),
            array ('url' => $album['url'], 'title' => $album['title'])),
          
          'css' => css ('css/public' . CSS, 'css/album' . CSS),
          'js' => js ('js/public' . JS, 'js/album' . JS),
          'now' => 'albums',
          'body_class' => 'album',
          'last_url' => URL_ALBUMS . 'index' . HTML,
          'search' => $album['title'],
          'content' => Step::loadView (PATH_VIEWS . 'album' . PHP, array (
              'album' => $album,
            )),
        ))))) Step::error ();

      array_push (Step::$sitemapInfos, array (
        'uri' => '/album/' . $album['id'] . '-' . oa_url_encode ($album['title']) . HTML,
        'priority' => '0.7',
        'changefreq' => 'daily',
        'lastmod' => date ('c'),
      ));
    }
    Step::progress ('更新 Albums HTML', '完成！');
  }
  public static function writeVideosHtml () {
    Step::newLine ('-', '更新 Videos HTML');

    $limit = 10;
    // include_once PATH_CMD_LIBS . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . 'ImageUtility.php';

    if ($total = count (Step::$apis['videos'])) {
      for ($offset = 0; $offset < $total; $offset += $limit) {
        $i = 0;
        $videos = array_slice (Step::$apis['videos'], $offset, $limit);
        // $ogimage_path = PATH_IMG_OG_TMP . ($tmpName = uniqid (rand () . '_') . '.jpg');
        // try { ImageUtility::photos (array_values (array_filter (array_map (function ($video) { return download_web_file (youtube_cover_url ($video['vid']), PATH_TMP . pathinfo (youtube_cover_url ($video['vid']), PATHINFO_BASENAME)); }, $videos))), $ogimage_path); } catch (Exception $e) { }

        if (!Step::writeFile (PATH_VIDEOS . (!$offset ? 'index' : $offset) . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($description = TITLE . '有著豐富的影音紀錄，你看過 ' . implode (',', Step::columnArray ($videos, 'title')) . ' 了嗎？不知道的朋友沒關係，趕緊來看看吧，說不定會讓你對北港更加了解喔！'), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => URL_VIDEOS . (!$offset ? 'index' : $offset) . HTML),
              array ('property' => 'og:title', 'content' => '影音紀錄' . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($description, false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c')),
              array ('property' => 'article:published_time', 'content' => date ('c')),
              array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG, 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => URL_VIDEOS . (!$offset ? 'index' : $offset) . HTML),
              array ('rel' => 'alternate', 'href' => URL_VIDEOS . (!$offset ? 'index' : $offset) . HTML, 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
            '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
            "itemListElement" => array_map (function ($video) use ($offset, $i) {
              return array (
                  "@type" => "ListItem",
                  "position" => $offset + $i,
                  "item" => array (
                      "@id" => $video['url'],
                      "name" => $video['title'],
                      "description" => mb_strimwidth (remove_ckedit_tag ($video['content']), 0, 150, '…','UTF-8'),
                      "image" => array ('@type' => 'ImageObject', 'url' => youtube_cover_url ($video['vid']), 'height' => 360, 'width' => 480),
                      "url" => $video['url'],
                    )
                  );
              }, $videos)
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => URL_VIDEOS . (!$offset ? 'index' : $offset) . HTML, 'title' => '影音紀錄')),
          'css' => css ('css/public' . CSS, 'css/videos' . CSS),
          'js' => js ('js/public' . JS),
          'now' => 'videos',
          'content' => Step::loadView (PATH_VIEWS . 'videos' . PHP, array (
              'h1' => '影音紀錄',
              'videos' => $videos,
              'offset' => $offset,
              'pagination' => Pagination::initialize (array (
                  'total_rows' => $total, 'per_page' => $limit, 
                  'base_url' => URL_VIDEOS,
                  'offset' => $offset,
                ))->create_links (),
            )),
          ))))) Step::error ();

        array_push (Step::$sitemapInfos, array (
          'uri' => '/videos/' . (!$offset ? 'index' : $offset) . HTML,
          'priority' => '0.5',
          'changefreq' => 'daily',
          'lastmod' => date ('c'),
        ));
      }
    } else {
      if (!Step::writeFile (PATH_VIDEOS . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
                array ('name' => 'keywords', 'content' => KEYWORDS),
                array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION), 0, 150, '…','UTF-8')),
                array ('property' => 'og:url', 'content' => URL_VIDEOS . 'index' . HTML),
                array ('property' => 'og:title', 'content' => '影音紀錄' . ' - ' . TITLE),
                array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION, false), 0, 300, '…','UTF-8')),
                array ('property' => 'article:author', 'content' => OA_FB_URL),
                array ('property' => 'article:modified_time', 'content' => date ('c')),
                array ('property' => 'article:published_time', 'content' => date ('c')),
                array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG, 'alt' => TITLE),
                array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
                array ('property' => 'og:image:width', 'content' => 1200),
                array ('property' => 'og:image:height', 'content' => 630)
              ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => URL_VIDEOS . 'index' . HTML),
              array ('rel' => 'alternate', 'href' => URL_VIDEOS . 'index' . HTML, 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
            '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
            "itemListElement" => array_map (function ($video) {
              return array (
                  "@type" => "ListItem",
                  "position" => 0,
                  "item" => array (
                      "@id" => $video['url'],
                      "name" => $video['title'],
                      "description" => mb_strimwidth (remove_ckedit_tag ($video['content']), 0, 150, '…','UTF-8'),
                      "image" => array ('@type' => 'ImageObject', 'url' => $video['cover']['c600x315'], 'height' => 630, 'width' => 1200),
                      "url" => $video['url'],
                    )
                  );
              }, array ())
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => URL_VIDEOS . 'index' . HTML, 'title' => '影音紀錄')),
          'css' => css ('css/public' . CSS, 'css/videos' . CSS),
          'js' => js ('js/public' . JS),
          'now' => 'videos',
          'content' => Step::loadView (PATH_VIEWS . 'videos' . PHP, array (
              'h1' => '影音紀錄',
              'videos' => array (),
              'offset' => 0,
              'pagination' => '',
            )),
        ))))) Step::error ();

      array_push (Step::$sitemapInfos, array (
        'uri' => '/videos/index' . HTML,
        'priority' => '0.5',
        'changefreq' => 'daily',
        'lastmod' => date ('c'),
      ));
    }

    foreach (Step::$apis['videos'] as $video) {
      $path = PATH_VIDEO . $video['id'] . '-' . oa_url ($video['title'] . HTML);
      $mores = array_filter (Step::$apis['videos'], function ($a) use ($video) {
          return $a['id'] != $video['id'];
        });
      shuffle ($mores);
      

      if (!Step::writeFile ($path, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($video['content'] ? $video['content'] : DESCRIPTION), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => $video['url']),
              array ('property' => 'og:title', 'content' => $video['title'] . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($video['content'] ? $video['content'] : DESCRIPTION, false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c', strtotime ($video['updated_at']))),
              array ('property' => 'article:published_time', 'content' => date ('c', strtotime ($video['created_at']))),
              array ('property' => 'og:image', 'content' => $ogImgUrl = youtube_cover_url ($video['vid']), 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 480),
              array ('property' => 'og:image:height', 'content' => 360)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => $video['url']),
              array ('rel' => 'alternate', 'href' => $video['url'], 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
              '@context' => 'http://schema.org', '@type' => 'Article',
              'mainEntityOfPage' => array (
                '@type' => 'WebPage',
                '@id' => $video['url']),
              'headline' => $video['title'],
              'image' => array ('@type' => 'ImageObject', 'url' => $ogImgUrl, 'height' => 480, 'width' => 360),
              'datePublished' => date ('c', strtotime ($video['created_at'])),
              'dateModified' => date ('c', strtotime ($video['updated_at'])),
              'author' => array (
                  '@type' => 'Person', 'name' => $video['user']['name'], 'url' => facebook_url ($video['user']['fbid']),
                  'image' => array ('@type' => 'ImageObject', 'url' => avatar_url ($video['user']['fbid'], 300, 300), 'height' => 300, 'width' => 300)
                ),
              'publisher' => array (
                  '@type' => 'Organization', 'name' => TITLE,
                  'logo' => array ('@type' => 'ImageObject', 'url' => AMP_IMG_600_60, 'width' => 600, 'height' => 60)
                ),
              'description' => mb_strimwidth (remove_ckedit_tag ($video['content']), 0, 150, '…','UTF-8')
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => URL_VIDEOS . 'index' . HTML, 'title' => '影音紀錄'),
            array ('url' => $video['url'], 'title' => $video['title'])),
          'css' => css ('css/public' . CSS, 'css/article' . CSS),
          'js' => js ('js/public' . JS, 'js/article' . JS),
          'now' => 'videos',
          'last_url' => URL_VIDEOS . 'index' . HTML,
          'search' => $video['title'],
          'content' => Step::loadView (PATH_VIEWS . 'video' . PHP, array (
              'video' => $video,
              'mores' => array_map (function ($more) {
                return array (
                    'url' => $more['url'],
                    'cover' => array ('c600x315' => youtube_cover_url ($more['vid'])),
                    'title' => $more['title'],
                    'content' => $more['content'],
                  );
              }, array_slice ($mores, 0, 3))
            )),
        ))))) Step::error ();

      array_push (Step::$sitemapInfos, array (
        'uri' => '/video/' . $video['id'] . '-' . oa_url_encode ($video['title']) . HTML,
        'priority' => '0.7',
        'changefreq' => 'daily',
        'lastmod' => date ('c'),
      ));
    }
    Step::progress ('更新 Videos HTML', '完成！');
  }

  public static function writeGPSHtml () {
    Step::newLine ('-', '更新 GPS HTML');

    if (!Step::writeFile (PATH . 'gps' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS . ',GoogleMaps'),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($des = '2017北港迎媽祖，農曆三月十九日(4/15、16)開始囉，大家快點分享吧！活動期間會啟動 GPS 讓大家知道目前遶境到哪囉！如候鳥歸巢般的時刻，各位在外地的北港囝仔你準備好了嗎？一年一度的北港三月十九要開始了！外地的朋友快一起跟我們線上迓媽祖！'), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => PAGE_URL_GPS),
              array ('property' => 'og:title', 'content' => '三月十九 GPS 即時定位' . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($des, false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c')),
              array ('property' => 'article:published_time', 'content' => date ('c')),
              array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG_GPS, 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => PAGE_URL_GPS),
              array ('rel' => 'alternate', 'href' => PAGE_URL_GPS, 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
              '@context' => 'http://schema.org', '@type' => 'Article',
              'mainEntityOfPage' => array (
                '@type' => 'WebPage',
                '@id' => PAGE_URL_GPS),
              'headline' => '遶境路關',
              'image' => array ('@type' => 'ImageObject', 'url' => $ogImgUrl, 'height' => 630, 'width' => 1200),
              'datePublished' => date ('c'),
              'dateModified' => date ('c'),
              'author' => array (
                  '@type' => 'Person', 'name' => OA, 'url' => facebook_url (OA_FB_UID),
                  'image' => array ('@type' => 'ImageObject', 'url' => avatar_url (OA_FB_UID, 300, 300), 'height' => 300, 'width' => 300)
                ),
              'publisher' => array (
                  '@type' => 'Organization', 'name' => TITLE,
                  'logo' => array ('@type' => 'ImageObject', 'url' => AMP_IMG_600_60, 'width' => 600, 'height' => 60)
                ),
              'description' => mb_strimwidth (remove_ckedit_tag ($des), 0, 150, '…','UTF-8')
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => PAGE_URL_GPS, 'title' => '遶境路關')),
          'css' => css ('css/public' . CSS, 'css/gps' . CSS),
          'js' => js ('js/public' . JS, 'js/gps' . JS),
          'body_class' => 'maps',
          'now' => 'gps',
          'content' => Step::loadView (PATH_VIEWS . 'gps' . PHP, array (
              'h1' => '遶境路關',
              'struct' => Step::$apis['paths']['struct'],
              'paths' => Step::$apis['paths']['paths'],
              'infos' => Step::$apis['paths']['infos'],
            )),
        ))))) Step::error ();

    array_push (Step::$sitemapInfos, array (
      'uri' => '/' . 'gps' . HTML,
      'priority' => '0.5',
      'changefreq' => 'weekly',
      'lastmod' => date ('c'),
    ));

    Step::progress ('更新 GPS HTML', '完成！');
  }
  public static function writeGPS2Html () {
    Step::newLine ('-', '更新 GPS2 HTML');

    if (!Step::writeFile (PATH . 'gps' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS . ',GoogleMaps'),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag ($des = '2017北港迎媽祖，農曆三月十九日(4/15、16)開始囉，大家快點分享吧！活動期間會啟動 GPS 讓大家知道目前遶境到哪囉！如候鳥歸巢般的時刻，各位在外地的北港囝仔你準備好了嗎？一年一度的北港三月十九要開始了！外地的朋友快一起跟我們線上迓媽祖！'), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => PAGE_URL_GPS),
              array ('property' => 'og:title', 'content' => '三月十九 GPS 即時定位' . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag ($des, false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c')),
              array ('property' => 'article:published_time', 'content' => date ('c')),
              array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG_GPS, 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => PAGE_URL_GPS),
              array ('rel' => 'alternate', 'href' => PAGE_URL_GPS, 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
              '@context' => 'http://schema.org', '@type' => 'Article',
              'mainEntityOfPage' => array (
                '@type' => 'WebPage',
                '@id' => PAGE_URL_GPS),
              'headline' => '遶境路關',
              'image' => array ('@type' => 'ImageObject', 'url' => $ogImgUrl, 'height' => 630, 'width' => 1200),
              'datePublished' => date ('c'),
              'dateModified' => date ('c'),
              'author' => array (
                  '@type' => 'Person', 'name' => OA, 'url' => facebook_url (OA_FB_UID),
                  'image' => array ('@type' => 'ImageObject', 'url' => avatar_url (OA_FB_UID, 300, 300), 'height' => 300, 'width' => 300)
                ),
              'publisher' => array (
                  '@type' => 'Organization', 'name' => TITLE,
                  'logo' => array ('@type' => 'ImageObject', 'url' => AMP_IMG_600_60, 'width' => 600, 'height' => 60)
                ),
              'description' => mb_strimwidth (remove_ckedit_tag ($des), 0, 150, '…','UTF-8')
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => PAGE_URL_GPS, 'title' => '遶境路關')),
          'css' => css ('css/public' . CSS, 'css/gps2' . CSS),
          'js' => js ('js/public' . JS, 'js/gps2' . JS),
          'body_class' => 'maps',
          'now' => 'gps',
          'content' => Step::loadView (PATH_VIEWS . 'gps2' . PHP, array (
              'h1' => '遶境路關',
              'struct' => Step::$apis['paths']['struct'],
              'paths' => Step::$apis['paths']['paths'],
              'infos' => Step::$apis['paths']['infos'],
            )),
        ))))) Step::error ();

    array_push (Step::$sitemapInfos, array (
      'uri' => '/' . 'gps' . HTML,
      'priority' => '0.5',
      'changefreq' => 'weekly',
      'lastmod' => date ('c'),
    ));

    Step::progress ('更新 GPS2 HTML', '完成！');
  }
  public static function writeSearchHtml () {
    Step::newLine ('-', '更新 Search HTML');

    if (!Step::writeFile (PATH . 'js' . DIRECTORY_SEPARATOR . 'data' . JS, 'window._mazu=' . json_encode (array (
        array ('group' => '文章',
          'datas' => array_map (function ($article) {
            return array (
                'user' => $article['user'],
                'url' => $article['url'],
                'pic' => $article['cover']['c600x315'],
                'title' => $article['title'],
                'content' => remove_ckedit_tag ($article['content']),
              );
          }, Step::$apis['articles'])),
        array ('group' => '相簿',
        'datas' => array_map (function ($album) {
            return array (
                'user' => $album['user'],
                'url' => $album['url'],
                'pic' => $album['cover']['c600x315'],
                'title' => $album['title'],
                'content' => remove_ckedit_tag ($album['content']) . implode (' ', Step::columnArray ($album['images'], 'title')),
              );
          }, Step::$apis['albums'])),
        array ('group' => '影音',
          'datas' => array_map (function ($album) {
            return array (
                'user' => $album['user'],
                'url' => $album['url'],
                'pic' => youtube_cover_url ($album['vid']),
                'title' => $album['title'],
                'content' => remove_ckedit_tag ($album['content']),
              );
          }, Step::$apis['videos'])),
        array ('group' => '其他',
          'datas' => array (
              array (
                  'user' => Step::$apis['author']['user'],
                  'url' => PAGE_URL_AUTHOR,
                  'pic' => Step::$apis['author']['cover']['c600x315'],
                  'title' => Step::$apis['author']['title'],
                  'content' => remove_ckedit_tag (Step::$apis['author']['content']),
                ),
              array (
                  'user' => Step::$apis['license']['user'],
                  'url' => PAGE_URL_LICENSE,
                  'pic' => Step::$apis['license']['cover']['c600x315'],
                  'title' => Step::$apis['license']['title'],
                  'content' => remove_ckedit_tag (Step::$apis['license']['content']),
                ),
            ))
      )))) Step::error ();

    if (!Step::writeFile (PATH . 'search' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . '_frame' . PHP, array (
          'meta' => meta (
              array ('name' => 'keywords', 'content' => KEYWORDS),
              array ('name' => 'description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION), 0, 150, '…','UTF-8')),
              array ('property' => 'og:url', 'content' => PAGE_URL_SEARCH),
              array ('property' => 'og:title', 'content' => '快速搜尋' . ' - ' . TITLE),
              array ('property' => 'og:description', 'content' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION, false), 0, 300, '…','UTF-8')),
              array ('property' => 'article:author', 'content' => OA_FB_URL),
              array ('property' => 'article:modified_time', 'content' => date ('c')),
              array ('property' => 'article:published_time', 'content' => date ('c')),
              array ('property' => 'og:image', 'content' => $ogImgUrl = URL_D4_OG_IMG, 'alt' => TITLE),
              array ('property' => 'og:image:type', 'content' => typeOfImg ($ogImgUrl), 'tag' => 'larger'),
              array ('property' => 'og:image:width', 'content' => 1200),
              array ('property' => 'og:image:height', 'content' => 630)
            ),
          'link' => myLink (
              array ('rel' => 'canonical', 'href' => PAGE_URL_SEARCH),
              array ('rel' => 'alternate', 'href' => PAGE_URL_SEARCH, 'hreflang' => 'zh-Hant')
            ),
          'jsonLd' => array (
              '@context' => 'http://schema.org', '@type' => 'Article',
              'mainEntityOfPage' => array (
                '@type' => 'WebPage',
                '@id' => PAGE_URL_SEARCH),
              'headline' => '快速搜尋',
              'image' => array ('@type' => 'ImageObject', 'url' => $ogImgUrl, 'height' => 630, 'width' => 1200),
              'datePublished' => date ('c'),
              'dateModified' => date ('c'),
              'author' => array (
                  '@type' => 'Person', 'name' => OA, 'url' => facebook_url (OA_FB_UID),
                  'image' => array ('@type' => 'ImageObject', 'url' => avatar_url (OA_FB_UID, 300, 300), 'height' => 300, 'width' => 300)
                ),
              'publisher' => array (
                  '@type' => 'Organization', 'name' => TITLE,
                  'logo' => array ('@type' => 'ImageObject', 'url' => AMP_IMG_600_60, 'width' => 600, 'height' => 60)
                ),
              'description' => mb_strimwidth (remove_ckedit_tag (DESCRIPTION), 0, 150, '…','UTF-8')
            ),
          'scopes' => array (
            array ('url' => URL, 'title' => TITLE),
            array ('url' => PAGE_URL_SEARCH, 'title' => '快速搜尋')),

          'css' => css ('css/public' . CSS, 'css/search' . CSS),
          'js' => js ('js/public' . JS, 'js/data' . JS, 'js/search' . JS),
          'now' => 'search',
          'content' => Step::loadView (PATH_VIEWS . 'search' . PHP, array (
              'h1' => '快速搜尋',
            )),
        ))))) Step::error ();

    Step::progress ('更新 Search HTML', '完成！');
  }













  public static function columnArray ($objects, $key) {
    return array_map (function ($object) use ($key) {
      return !is_array ($object) ? is_object ($object) ? $object->$key : $object : $object[$key];
    }, $objects);
  }

  public static function writeSitemap () {

    Step::newLine ('-', '更新 Sitemap', count (Step::$sitemapInfos));

    $sitmap = new Sitemap ($domain = rtrim (URL, '/'));
    $sitmap->setPath (PATH_SITEMAP);
    $sitmap->setDomain ($domain);

    foreach (Step::$sitemapInfos as $sitemapInfo) {
      $sitmap->addItem ($sitemapInfo['uri'], $sitemapInfo['priority'], $sitemapInfo['changefreq'], $sitemapInfo['lastmod']);
      Step::progress ('更新 Sitemap');
    }

    $sitmap->createSitemapIndex ($domain . '/sitemap/', date ('c'));
    Step::progress ('更新 Sitemap', '完成！');
  }
  public static function cleanBuild () {
    Step::newLine ('-', '清除 上一次 檔案', count ($files = array ('js' . DIRECTORY_SEPARATOR . 'data' . JS, 'index' . HTML, 'gps' . HTML, 'license' . HTML, 'search' . HTML, 'author' . HTML)) + count ($paths = array (PATH_ASSET, PATH_SITEMAP, PATH_ARTICLES, PATH_VIDEOS, PATH_ALBUMS, PATH_ALBUM, PATH_VIDEOS, PATH_VIDEO, PATH_TAGS, PATH_ARTICLE, PATH_TMP, PATH_IMG_OG_TMP)));

    foreach ($paths as $path) {
      Step::directoryDelete ($path, false);
      Step::progress ('清除 上一次 檔案');
    }
    foreach ($files as $file) {
      @unlink (PATH . $file);
      Step::progress ('清除 上一次 檔案');
    }

    Step::progress ('清除 上一次 檔案', '完成！');
  }
  public static function directoryDelete ($dir, $is_root = true) {
    if (!file_exists ($dir)) return true;
    
    $dir = rtrim ($dir, DIRECTORY_SEPARATOR);
    if (!$currentDir = @opendir ($dir))
      return false;

    while (false !== ($filename = @readdir ($currentDir)))
      if (($filename != '.') && ($filename != '..'))
        if (is_dir ($dir . DIRECTORY_SEPARATOR . $filename)) if (substr ($filename, 0, 1) != '.') Step::directoryDelete ($dir . DIRECTORY_SEPARATOR . $filename); else;
        else unlink ($dir . DIRECTORY_SEPARATOR . $filename);

    @closedir ($currentDir);

    return $is_root ? @rmdir ($dir) : true;
  }
}

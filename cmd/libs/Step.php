<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

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
    $paths = array (PATH, PATH_ASSET, PATH_SITEMAP, PATH_ARTICLES, PATH_WORKS, PATH_TAGS, PATH_ARTICLE, PATH_WORK);
    
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
        if (!trim ($file, '.') || (($hidden == false) && ($file[0] == '.')) || is_link ($file) || ($file == 'cmd')) continue;

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
  public static function writeIndexHtml () {
    
    Step::newLine ('-', '更新 Index HTML');
    $banners = json_decode (Step::readFile (PATH_APIS . 'banners.json'), true);
    $promos = json_decode (Step::readFile (PATH_APIS . 'promos.json'), true);

    if (!Step::writeFile (PATH . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'index' . PHP, array (
          '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => PAGE_URL_INDEX)),
          '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
          'banners' => $banners,
          'promos' => $promos,
        ))))) Step::error ();
  
    array_push (Step::$sitemapInfos, array (
      'uri' => '/' . 'index' . HTML,
      'priority' => '0.5',
      'changefreq' => 'daily',
      'lastmod' => date ('c'),
    ));

    Step::progress ('更新 Index HTML', '完成！');
  }
  public static function writeAboutHtml () {
    Step::newLine ('-', '更新 About HTML');

    if (!Step::writeFile (PATH . 'about' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'about' . PHP, array (
          '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => PAGE_URL_ABOUT)),
          '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
        ))))) Step::error ();

    array_push (Step::$sitemapInfos, array (
      'uri' => '/' . 'about' . HTML,
      'priority' => '0.4',
      'changefreq' => 'weekly',
      'lastmod' => date ('c'),
    ));

    Step::progress ('更新 About HTML', '完成！');
  }
  public static function writeContactHtml () {
    Step::newLine ('-', '更新 Contact HTML');

    if (!Step::writeFile (PATH . 'contact' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'contact' . PHP, array (
          '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => PAGE_URL_CONTACT)),
          '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
        ))))) Step::error ();

    array_push (Step::$sitemapInfos, array (
      'uri' => '/' . 'contact' . HTML,
      'priority' => '0.3',
      'changefreq' => 'weekly',
      'lastmod' => date ('c'),
    ));

    Step::progress ('更新 Contact HTML', '完成！');
  }
  public static function columnArray ($objects, $key) {
    return array_map (function ($object) use ($key) {
      return !is_array ($object) ? is_object ($object) ? $object->$key : $object : $object[$key];
    }, $objects);
  }

  public static function writeArticlesHtml () {
    Step::newLine ('-', '更新 Articles HTML');

    $articles = array_map (function ($article) {
      $article['user']['url'] = 'https://www.facebook.com/' . $article['user']['uid'];

      return array_merge ($article, array (
        'path' => PATH_ARTICLE . $article['id'] . '-' . oa_url ($article['title'] . HTML),
        'url' => URL_ARTICLE . $article['id'] . '-' . oa_url_encode ($article['title'] . HTML),
        ));
    }, json_decode (Step::readFile (PATH_APIS . 'articles.json'), true));

    $tags = array ();
    foreach (self::columnArray ($articles, 'tags') as $ts) foreach ($ts as $t) if (!in_array ($t['id'], self::columnArray ($tags, 'id'))) array_push ($tags, $t);
    $tags = array_map (function ($tag) use ($articles) {
      $as = array (); foreach ($articles as $article) if (($ids = self::columnArray ($article['tags'], 'id')) && in_array ($tag['id'], $ids)) array_push ($as, $article);
      return array_merge ($tag, array (
        'articles' => $as,
        'path' => sprintf (PATH_TAG_ARTICLES, oa_url ($tag['name'])),
        'url' => sprintf (URL_TAG_ARTICLES, oa_url_encode ($tag['name'])),
        ));
    }, $tags);

    $articles = array_map (function ($article) use ($tags) {
      $article['tags'] = array_filter (array_map (function ($tag) use ($tags) { foreach ($tags as $t) if ($t['id'] == $tag['id']) return $t; return array (); }, $article['tags']));
      return $article;
    }, $articles);

    $news = array_values ($articles);
    $hots = array_values ($articles);
    usort ($hots, function ($a, $b) { return $a['pv'] < $b['pv']; });

    $limit = 10;
    $total = count ($articles);
    if ($total) {
      for ($offset = 0; $offset < $total; $offset += $limit) {
        if (!Step::writeFile (PATH_ARTICLES . (!$offset ? 'index' : $offset) . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'articles' . PHP, array (
            '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_ARTICLES)),
            '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
            'tags' => $tags,
            'articles' => array_slice ($articles, $offset, $limit),
            'hots' => $hots,
            'news' => $news,
            'offset' => $offset,
            'pagination' => Pagination::initialize (array (
                'total_rows' => $total, 'per_page' => $limit, 
                'base_url' => URL_ARTICLES,
                'offset' => $offset,
              ))->create_links (),
          ))))) Step::error ();

        array_push (Step::$sitemapInfos, array (
          'uri' => '/articles/' . (!$offset ? 'index' : $offset) . HTML,
          'priority' => '0.5',
          'changefreq' => 'daily',
          'lastmod' => date ('c'),
        ));
      }
    } else {
      if (!Step::writeFile (PATH_ARTICLES . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'articles' . PHP, array (
          '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_ARTICLES)),
          '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
          'tags' => $tags,
          'articles' => [],
          'offset' => 0,
          'hots' => $hots,
          'news' => $news,
          'pagination' => '',
        ))))) Step::error ();

      array_push (Step::$sitemapInfos, array (
        'uri' => '/articles/index' . HTML,
        'priority' => '0.5',
        'changefreq' => 'daily',
        'lastmod' => date ('c'),
      ));
    }
    foreach ($tags as $tag) {
      $total = count ($tag['articles']);
      if (!file_exists ($tag['path'])) Step::mkdir777 ($tag['path']);

      if ($total) {
        for ($offset = 0; $offset < $total; $offset += $limit) {
          if (!Step::writeFile ($tag['path'] . (!$offset ? 'index' : $offset) . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'tag-articles' . PHP, array (
              '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_ARTICLES)),
              '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
              'tag' => $tag,
              'tags' => $tags,
              'articles' => array_slice ($tag['articles'], $offset, $limit),
              'hots' => $hots,
              'news' => $news,
              'offset' => $offset,
              'pagination' => Pagination::initialize (array (
                  'total_rows' => $total, 'per_page' => $limit, 
                  'base_url' => $tag['url'],
                  'offset' => $offset,
                ))->create_links (),
            ))))) Step::error ();

          array_push (Step::$sitemapInfos, array (
            'uri' => '/tags/' . oa_url_encode ($tag['name']) . '/articles/' . (!$offset ? 'index' : $offset) . HTML,
            'priority' => '0.5',
            'changefreq' => 'daily',
            'lastmod' => date ('c'),
          ));
        }
      } else {
        if (!Step::writeFile ($tag['path'] . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'tag-articles' . PHP, array (
            '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_ARTICLES)),
            '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
            'tag' => $tag,
            'tags' => $tags,
            'articles' => [],
            'hots' => $hots,
            'news' => $news,
            'offset' => 0,
            'pagination' => '',
          ))))) Step::error ();

        array_push (Step::$sitemapInfos, array (
          'uri' => '/tags/' . oa_url_encode ($tag['name']) . '/articles/index' . HTML,
          'priority' => '0.5',
          'changefreq' => 'daily',
          'lastmod' => date ('c'),
        ));
      }
    }

    foreach ($articles as $article) {
      if (!Step::writeFile ($article['path'], HTMLMin::minify (Step::loadView (PATH_VIEWS . 'article' . PHP, array (
          '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_ARTICLES)),
          '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
          'tags' => $tags,
          'article' => $article,
          'hots' => $hots,
          'news' => $news,
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
  public static function writeWorksHtml () {
    Step::newLine ('-', '更新 Works HTML');

    $works = array_map (function ($work) {
      $work['user']['url'] = 'https://www.facebook.com/' . $work['user']['uid'];

      return array_merge ($work, array (
        'path' => PATH_WORK . $work['id'] . '-' . oa_url ($work['title'] . HTML),
        'url' => URL_WORK . $work['id'] . '-' . oa_url_encode ($work['title'] . HTML),
        ));
    }, json_decode (Step::readFile (PATH_APIS . 'works.json'), true));

    $tags = array ();
    foreach (self::columnArray ($works, 'tags') as $ts) foreach ($ts as $t) if (!in_array ($t['id'], self::columnArray ($tags, 'id'))) array_push ($tags, $t);
    $tags = array_map (function ($tag) use ($works) {
      $ws = array (); foreach ($works as $work) if (($ids = self::columnArray ($work['tags'], 'id')) && in_array ($tag['id'], $ids)) array_push ($ws, $work);
      return array_merge ($tag, array (
        'works' => $ws,
        'path' => sprintf (PATH_TAG_WORKS, oa_url ($tag['name'])),
        'url' => sprintf (URL_TAG_WORKS, oa_url_encode ($tag['name'])),
        ));
    }, $tags);
    $ntags = array ();
    foreach ($tags as $tag) if (!$tag['par_id']) array_push ($ntags, array_merge ($tag, array ('subs' => array ())));
    usort ($ntags, function ($a, $b) { return $a['sort'] > $b['sort']; });
    $ntags = array_map (function ($ntag) use ($tags) { foreach ($tags as $tag) if ($tag['par_id'] == $ntag['id']) array_push ($ntag['subs'], $tag); usort ($ntag['subs'], function ($a, $b) { return $a['sort'] > $b['sort']; }); return $ntag; }, $ntags);

    $works = array_map (function ($work) use ($tags) {
      $work['tags'] = array_filter (array_map (function ($tag) use ($tags) { foreach ($tags as $t) if ($t['id'] == $tag['id']) return $t; return array (); }, $work['tags']));
      return $work;
    }, $works);

    $limit = 9;
    $total = count ($works);

    if ($total) {
      for ($offset = 0; $offset < $total; $offset += $limit) {
        if (!Step::writeFile (PATH_WORKS . (!$offset ? 'index' : $offset) . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'works' . PHP, array (
            '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_WORKS)),
            '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
            'tags' => $ntags,
            'works' => array_slice ($works, $offset, $limit),
            'offset' => $offset,
            'pagination' => Pagination::initialize (array (
              'total_rows' => $total, 'per_page' => $limit, 
              'base_url' => URL_WORKS,
              'offset' => $offset,
            ))->create_links (),
          ))))) Step::error ();

        array_push (Step::$sitemapInfos, array (
          'uri' => '/works/' . (!$offset ? 'index' : $offset) . HTML,
          'priority' => '0.5',
          'changefreq' => 'daily',
          'lastmod' => date ('c'),
        ));
      }
    } else {
      if (!Step::writeFile (PATH_WORKS . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'works' . PHP, array (
          '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_WORKS)),
          '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
          'tags' => $ntags,
          'works' => [],
          'offset' => 0,
          'pagination' => '',
        ))))) Step::error ();

      array_push (Step::$sitemapInfos, array (
        'uri' => '/works/index' . HTML,
        'priority' => '0.5',
        'changefreq' => 'daily',
        'lastmod' => date ('c'),
      ));
    }

    foreach ($tags as $tag) {
      $total = count ($tag['works']);
      if (!file_exists ($tag['path'])) Step::mkdir777 ($tag['path']);

      if ($total) {
        for ($offset = 0; $offset < $total; $offset += $limit) {
          if (!Step::writeFile ($tag['path'] . (!$offset ? 'index' : $offset) . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'tag-works' . PHP, array (
              '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_WORKS)),
              '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
              'tag' => $tag,
              'tags' => $ntags,
              'works' => array_slice ($tag['works'], $offset, $limit),
              'offset' => $offset,
              'pagination' => Pagination::initialize (array (
                  'total_rows' => $total, 'per_page' => $limit, 
                  'base_url' => $tag['url'],
                  'offset' => $offset,
                ))->create_links (),
            ))))) Step::error ();

          array_push (Step::$sitemapInfos, array (
            'uri' => '/tags/' . oa_url_encode ($tag['name']) . '/works/' . (!$offset ? 'index' : $offset) . HTML,
            'priority' => '0.5',
            'changefreq' => 'daily',
            'lastmod' => date ('c'),
          ));
        }
      } else {
        if (!Step::writeFile ($tag['path'] . 'index' . HTML, HTMLMin::minify (Step::loadView (PATH_VIEWS . 'tag-works' . PHP, array (
            '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_WORKS)),
            '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
            'tag' => $tag,
            'tags' => $ntags,
            'works' => [],
            'offset' => 0,
            'pagination' => '',
          ))))) Step::error ();

        array_push (Step::$sitemapInfos, array (
          'uri' => '/tags/' . oa_url_encode ($tag['name']) . '/works/index' . HTML,
          'priority' => '0.5',
          'changefreq' => 'daily',
          'lastmod' => date ('c'),
        ));
      }
    }

    foreach ($works as $work) {
      if (!Step::writeFile ($work['path'], HTMLMin::minify (Step::loadView (PATH_VIEWS . 'work' . PHP, array (
          '_header' => Step::loadView (PATH_VIEWS . '_header' . PHP, array ('active' => URL_WORKS)),
          '_footer' => Step::loadView (PATH_VIEWS . '_footer' . PHP),
          'work' => $work,
        ))))) Step::error ();

      array_push (Step::$sitemapInfos, array (
        'uri' => '/work/' . $work['id'] . '-' . oa_url_encode ($work['title']) . HTML,
        'priority' => '0.7',
        'changefreq' => 'daily',
        'lastmod' => date ('c'),
      ));
    }
    Step::progress ('更新 Works HTML', '完成！');
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
    Step::newLine ('-', '清除 上一次 檔案', count ($paths = array (PATH_ASSET, PATH_SITEMAP, PATH_ARTICLES, PATH_WORKS, PATH_TAGS, PATH_ARTICLE, PATH_WORK)));
    foreach ($paths as $path) {
      Step::directoryDelete ($path, false);
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

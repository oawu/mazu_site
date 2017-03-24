<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Pagination {

  private $first_link = '第一頁', $last_link = '最後頁', $prev_link = '上一頁', $next_link = '下一頁',
          $full_tag_open = '<ul class="pagination">', $full_tag_close = '</ul>',
          $first_tag_open = '<li class="f">', $first_tag_close = '</li>',
          $prev_tag_open = '<li class="p">', $prev_tag_close = '</li>',
          $num_tag_open = '<li>', $num_tag_close = '</li>',
          $cur_tag_open = '<li class="active"><a href="#">', $cur_tag_close = '</a></li>',
          $next_tag_open = '<li class="n">', $next_tag_close = '</li>',
          $last_tag_open = '<li class="l">', $last_tag_close = '</li>';

  private $total_rows         = 0,
          $num_links          = 3,
          $per_page           = 10,
          $offset             = 2,
          $base_url           = '',
          $page_query_string  = false,
          $cur_page           =  0,
          $first_url          = '';
  private $suffix             = '.html';

  public function __construct ($params = array ()) {
    foreach ($params as $key => $val) if (isset ($this->$key)) $this->$key = $val;
  }
  public static function initialize ($params = array ()) {
    return new Pagination ($params);
  }

  public function create_links() {
    if ($this->total_rows == 0 || $this->per_page == 0) return '';
    if (($num_pages = ceil ($this->total_rows / $this->per_page)) == 1) return '';

      $base_page = 0;

      if ($this->offset != $base_page)
      {
        $this->cur_page = $this->offset;
        $this->cur_page = (int)$this->cur_page;
      }

      $this->num_links = (int)$this->num_links;
      if ($this->num_links < 1) return '';

      if (!is_numeric ($this->cur_page)) $this->cur_page = $base_page;

      if ($this->cur_page > $this->total_rows) $this->cur_page = ($num_pages - 1) * $this->per_page;

      $uri_page_number = $this->cur_page;

      $this->cur_page = floor (($this->cur_page / $this->per_page) + 1);

      $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
      $end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

      $this->base_url = rtrim ($this->base_url, '/') .'/';

      $output = '';

      if  ($this->first_link !== false && $this->cur_page > ($this->num_links + 1)) {
        $first_url = ($this->first_url == '') ? $this->base_url : $this->first_url;
        $output .= $this->first_tag_open . '<a href="' . rtrim ($first_url, '/') . '/' . 'index' . $this->suffix . '">' . $this->first_link . '</a>' . $this->first_tag_close;
      }

      if  ($this->prev_link !== false && $this->cur_page != 1) {
          $i = (($i = $uri_page_number - $this->per_page) == 0) ? '' : $i;
          $output .= $this->prev_tag_open.'<a href="' . rtrim ($this->base_url, '/') . '/' . $i . $this->suffix . '">' . $this->prev_link . '</a>' . $this->prev_tag_close;
      }

      for ($loop = $start -1; $loop <= $end; $loop++) {
        $i = ($loop * $this->per_page) - $this->per_page;

        if ($i >= $base_page)
          if ($this->cur_page == $loop) {
            $output .= $this->cur_tag_open . $loop . $this->cur_tag_close;
          } else {
            $n = ($i == $base_page) ? 'index' : $i;
            if ($n == 'index' && $this->first_url != '')
              $output .= $this->num_tag_open.'<a href="' . $this->first_url . '">' . $loop . '</a>' . $this->num_tag_close;
            else
              $output .= $this->num_tag_open.'<a href="' . rtrim ($this->base_url, '/') . '/' . (($n == '') ? '' : $n) . $this->suffix . '">' . $loop . '</a>' . $this->num_tag_close;
          }
      }

    if ($this->next_link !== false && $this->cur_page < $num_pages)
      $output .= $this->next_tag_open . '<a href="' . rtrim ($this->base_url, '/') . '/' . ($this->cur_page * $this->per_page) . $this->suffix . '">' . $this->next_link . '</a>' . $this->next_tag_close;

    if ($this->last_link !== false && ($this->cur_page + $this->num_links) < $num_pages)
      $output .= $this->last_tag_open . '<a href="' . rtrim ($this->base_url, '/') . '/' . (($num_pages * $this->per_page) - $this->per_page) . $this->suffix . '">' . $this->last_link . '</a>' . $this->last_tag_close;

    $output = preg_replace ("#([^:])//+#", "\\1/", $output);
    $output = $this->full_tag_open . $output . $this->full_tag_close;

    return $output;
  }
}
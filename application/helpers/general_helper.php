<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

function h_console_log($nameFile, $content)
{
  $CI = get_instance();
  $CI->load->helper('file');
  write_file($nameFile . '.log', $content);
}


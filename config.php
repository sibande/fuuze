<?php

if ( ! defined('CONFIG_LOADED'))
{
  define('CONFIG_LOADED', TRUE);
  define('APPLICATION_DIR', 'app');
  define('DEBUG', TRUE);
}

$Fuuze_config = array(
  // URL routes 'url regex' => array('class name', 'action', 'class sub directory separated by _')
  'routes' => array(
    '/^\/$/'=>array('Site_Main', 'index', ''),
    ),
  'db_connect' => array('mysql:host=localhost;dbname=devdb', 'root', ''),
  );

return $Fuuze_config;
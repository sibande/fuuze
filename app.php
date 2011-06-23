<?php


require('fuuze/fuuze.php');

//__autoloads
require_once '../plugins/Twig/Autoloader.php';

Twig_Autoloader::register();
spl_autoload_register('autoload');

define('PROJECT_ROOT_DIR', dirname(dirname(__FILE__)));


new Run;

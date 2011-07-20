<?php


require('fuuze/fuuze.php');

//__autoloads
require_once '../plugins/Twig/Autoloader.php';

Twig_Autoloader::register();
spl_autoload_register('Fuuze::autoload');

define('PROJECT_ROOT_DIR', dirname(dirname(__FILE__)));
define('APPLICATION_DIR', 'app');
define('FRAMEWORK_DIR', 'web');
define('FSTATIC_DIR', dirname(dirname(__FILE__)).'/'.APPLICATION_DIR.'/static');
define('FCONFIG_FILE_PATH', dirname(dirname(__FILE__)).'/config.php');



new Run;

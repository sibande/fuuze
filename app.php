<?php


require('fuuze/Fuuze.php');

spl_autoload_register('autoload');

define('PROJECT_ROOT_DIR', dirname(dirname(__FILE__)));

new Run;

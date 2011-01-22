<?php
/*
 * Fuuze PHP framework Controller and core logic.
 */

function __autoload($class) {

  include('config.php'); #??? not dry enough

  if (file_exists($class.'.php')){
    include($class . '.php');
  } else {
    foreach ($Fuuze_config['apps'] as $app){
      if (file_exists($app . $class . '.php')){
	include($app . $class . '.php');
      }
    }
  }
}

class Fuuze {
  
  function __construct(){
    include('config.php'); #??? not dry enough
    $this->Fuuze_config = $Fuuze_config;
    $this->errors = array();

    $this->connect_db();
  }
  final public function render($view){
    $view_file = 'views/'.$view;
    if (file_exists($view_file)){
      include($view_file);
    } else {
      echo 'View '.$view.' was not found under views/ folder.';
    }
  }
  protected function connect_db(){
    try {
      list($db_driver, $db_user, $db_pass) = $this->Fuuze_config['db_connect'];
      $this->dbh = new PDO($db_driver, $db_user, $db_pass);
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }
}

class Run {

  function __construct(){
    include_once('config.php');

    $path = $_SERVER['SCRIPT_URL'];
    
    foreach ($Fuuze_config['routes'] as $route=>$options){
      if (preg_match($route, $path)){
	list($controller, $action) = $options;
	$page = new $controller;
	$page->$action();
	$http_404_error = Null;
	break;
      }
      else {
	$http_404_error = True;
      }
    }
    #sends the 404 header if page was not matched
    if (isset($http_404_error)){
      header("Status: 404 Not Found");
      echo 'Page you were looking for was not found';
    }
  }
}
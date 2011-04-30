<?php
/*
 * Fuuze PHP framework Controller and core logic.
 * include("Fuuze.php"); then create and instance of class Run
 */

define('CONFIG_FILE', 'config.php');

function __autoload($class) {
  /*
   * Autoloads controllers and in predefined controller paths
   */
  
  include(CONFIG_FILE);

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
  /*
   * fuuze's core controller
   */
  public function __construct(){
    include(CONFIG_FILE);
    $this->Fuuze_config = $Fuuze_config;
    $this->errors = array();

    $this->connect_db();
  }
  public function render($view){
    /*
     * Renders controller template/view
     * $this->render("index.php"); // loads views/index.php
     */
    $view_file = 'views/'.$view;
    if (file_exists($view_file)){
      include($view_file);
    } else {
      echo 'View '.$view.' was not found under views/ folder.';
    }
  }
  public function connect_db(){
    /*
     * Initializes PDO and sets $this->dbh for further db operations
     */
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
  /*
   * Processes request and runs matched controller and action
   */
  public function __construct(){
    include_once(CONFIG_FILE);

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
    //sends the 404 header if page was not matched
    if (isset($http_404_error)){
      header("Status: 404 Not Found");
      echo 'Page you were looking for was not found';
    }
  }
}
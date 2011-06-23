<?php
/**
 * Fuuze PHP framework Controller and core logic.
 * 
 * @package   fuuze
 * @author    JB Sibande
 * @license   (c) 2010 JB Sibande GNU GPL 3.  
 */


/**
 * Autoloads a class defined under app directory
 *
 * @param   string  a class name
 * @return  void
 */
function autoload($class)
{
  $file = PROJECT_ROOT_DIR.'/'.APPLICATION_DIR.'/'.str_replace('_', '/', strtolower($class)).'.php';
  if (file_exists($file))
  {
    require $file;
  }
  else
  {
    echo $class.' not found.';
    die();
  }
}


/**
 * Parent controller
 */
class Fuuze
{
  public function __construct()
  {
    $this->fconfig = require(dirname(__FILE__).'/../config.php');
    
    $this->errors = array();
    
    $this->connect_db();
  }
  /**
   * Renders controller template/view
   * $this->render("index.php"); // loads views/index.php
   *
   * @param   string view name
   * @return  void
   */
  public function render($view)
  {
    $view_file = 'views/'.$view;
    if (file_exists($view_file))
    {
      include($view_file);
    }
    else
    {
      echo 'View '.$view.' was not found under views/ folder.';
    }
  }
  /**
   * Initializes PDO and sets $this->dbh
   *
   * @return void
   */
  public function connect_db()
  {
    try
    {
      list($db_driver, $db_user, $db_pass) = $this->fconfig['db_connect'];
      $this->dbh = new PDO($db_driver, $db_user, $db_pass);
      $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e)
    {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }
}

class Run
{
  /**
   * Processes request and runs matched controller and action
   */
  public function __construct()
  {
    $fconfig = require(dirname(__FILE__).'/../config.php');
 
    $path = $_SERVER['SCRIPT_URL'];
    
    foreach ($fconfig['routes'] as $route=>$options)
    {
      if (preg_match($route, $path))
      {
	list($controller, $action, $sub_dir) = $options;
	$class = $sub_dir.$controller;
	$page = new $class;
	$page->$action();
	break;
      }
      else
      {
	$http_404_error = True;
      }
    }
    //sends the 404 header if page was not matched
    if (isset($http_404_error))
    {
      header("Status: 404 Not Found");
      echo 'Page you were looking for was not found';
    }
  }
}

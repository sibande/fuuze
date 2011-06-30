<?php
/**
 * Fuuze PHP framework Controller and core logic.
 * 
 * @package   fuuze
 * @author    Jose Sibande <jbsibande@gmail.com>
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
    $this->fconfig = require(PROJECT_ROOT_DIR.'/'.FRAMEWORK_DIR.'/config.php');
    
    $this->app_path = PROJECT_ROOT_DIR.'/'.APPLICATION_DIR;

    $this->errors = array();
    
    $this->connect_db();
  }

  /**
   * Renders template
   * 
   * @param   string  template name
   * @param   array   variables to be display in template
   * @return  void
   */
  public function render($template, $variables = array())
  {
    $loader = new Twig_Loader_Filesystem(array($this->app_path.'/templates'));
    $twig = new Twig_Environment($loader, array(
				   'debug' => DEBUG,
				   ));
    $template = $twig->loadTemplate($template);

    echo $template->render( (array) $variables);
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
      $this->db = new PDO($db_driver, $db_user, $db_pass);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    $fconfig = require(PROJECT_ROOT_DIR.'/'.FRAMEWORK_DIR.'/config.php');
 
    $path = $_SERVER['SCRIPT_URL'];
    
    foreach ($fconfig['routes'] as $route=>$options)
    {
      if (preg_match($route, $path, $matches))
      {
	$requested_url_valid = TRUE;;
	list($controller, $action, $sub_dir) = $options;
	$class = $sub_dir.$controller;
	$page = new $class;
	$page->$action($request=array('route'=>$matches));
	break;
      }
    }
    if ( ! isset($requested_url_valid))
    {
      header("Status: 404 Not Found");
      echo 'Page you were looking for was not found';
    }
  }
}

<?php
/**
 * Fuuze PHP framework Controller and core logic.
 * 
 * @package   fuuze
 * @author    Jose Sibande <jbsibande@gmail.com>
 * @license   (c) 2010 JB Sibande GNU GPL 3.  
 */

/**
 * Parent controller
 */
class Fuuze
{

  public function __construct()
  {
    $this->fconfig = require(FCONFIG_FILE_PATH);
    
    $this->app_path = PROJECT_ROOT_DIR.'/'.APPLICATION_DIR;

    $this->errors = array();
    // prepare render function
    $this->render();
    
    $this->db = self::connect_db();
  }

  /**
   * Autoloads a class defined under app directory
   *
   * @param   string  a class name
   * @return  void
   */
  public static function autoload($class)
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
   * Renders template
   * 
   * @param   string  template name
   * @param   array   variables to be display in template
   * @return  void
   */
  public function render($template=NULL, $variables = array())
  {
    // check if render was called to make $_twig_env available 
    if (( ! (bool) $template) and ( ! isset($this->_twig_env)))
    {
      $loader = new Twig_Loader_Filesystem(array($this->app_path.'/templates'));
      $this->_twig_env = new Twig_Environment($loader, array(
						'debug' => DEBUG,
						));
    }
    else
    {
      $template = $this->_twig_env->loadTemplate($template);
      echo $template->render( (array) $variables);
    }
  }

  /**
   * Initializes PDO and sets $this->dbh
   *
   * @return void
   */
  static public function connect_db()
  {
    $fconfig = require(FCONFIG_FILE_PATH);
    try
    {
      list($db_driver, $db_user, $db_pass) = $fconfig['db_connect'];
      $db = new PDO($db_driver, $db_user, $db_pass);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e)
    {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
    return $db;
  }
}

class Run
{

  /**
   * Processes request and runs matched controller and action
   */
  public function __construct()
  {
    $fconfig = require(FCONFIG_FILE_PATH);
    
    $url = parse_url('http://devnull'.$_SERVER['REQUEST_URI']);

    foreach ($fconfig['routes'] as $route=>$options)
    {
      if (preg_match($route, $url['path'], $matches))
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

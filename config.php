<?php


$Fuuze_config = array(
		      'routes' => array(
				       '/^\/$/'=>array('Index', 'home'),
					),
		      'apps' => array(''),
		      'db_connect' => array('mysql:host=localhost;dbname=devdb', 'root', ''),
		      );
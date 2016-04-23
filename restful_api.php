<?php

// get correct id for plugin
$thisfile = basename(__FILE__, '.php');

// register plugin
register_plugin(
	$thisfile, //Plugin id
	'RESTful API', 	//Plugin name
	'0.1', 		//Plugin version
	'Lawrence Okoth-Odida',  //Plugin author
	'https://github.com/lokothodida/', //author website
	'Lets plugin developers build RESTful APIs in GetSimple', //Plugin description
	'plugins', //page type - on which admin tab to display
	'restful_api_admin'  //main function (administration)
);

// Actions/Filters
// Execution of REST API
add_action('common', 'exec_rest_apis');

// FUNCTIONS
// Admin Panel
function resftul_api_admin() {
  // ...
}

// Execute the REST apis
function exec_rest_apis() {
	if (isset($_GET['restapi'])) {
	  // Registered the APIs
	  exec_action('register-rest-api');

	  // Get the correct API
	  $api = RestAPI::execute();

	  if ($api['success']) {
      header('Content-Type: application/json');
	    exit(json_encode($api['result']));
	  }
	}
}

// Register a REST api
function register_rest_api($id, $params) {
  return RestAPI::register($id, $params);
}

class RestAPI {
  private static $apis = array();

  public static function register($id, $params) {
    self::$apis[$id] = $params;
  }

  public static function execute() {
    $status = array('success' => false);

    foreach (self::$apis as $id => $api) {
      $valid = isset($_GET['restapi']) && $_GET['restapi'] == $id && isset($api['action']) && is_callable($api['action']);
      $where = isset($api['where']) ? $api['where'] : GSBOTH;
      $frontend = is_frontend();
      $loggedin = cookie_check();

      if (($where == GSBOTH) || $where == GSBACK && $loggedin && !$frontend || ($where == GSFRONT && $frontend)) {
        $status['success'] = true;
        $status['result'] = $api['action']();
        return $status;
      }
    }

    return $status;
  }
}
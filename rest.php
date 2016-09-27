<?php
namespace KuntaAPI\Menus\Rest;

defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

require_once ('constants.php');

class Menus {
	
  private $namespace;
  
  function __construct() {
  	$this->namespace = "wp/v2";
  }
  
  public function registerRoutes() {
  	register_rest_route($this->namespace, '/menus', array(
  	  array(
  		'methods'  => \WP_REST_Server::READABLE,
  		'callback' => array($this, 'listMenus')
  	  ),
  	  'schema' => array($this, 'listMenusSchema')
  	));
  	
  	register_rest_route($this->namespace, '/menus/(?P<id>\d+)', array(
  	  array(
  	   'methods'  => \WP_REST_Server::READABLE,
  	   'callback' => array($this, 'findMenu')
      ),
  	  'schema' => array($this, 'findMenuSchema')
  	));
  }
	
  public function listMenus() {
	$response = [];
	$wp_menus = wp_get_nav_menus();
	foreach ($wp_menus as $wp_menu) {
	  $menu = (array) $wp_menu;
	  $response[] = array(
	    id => $menu['term_id'],
     	slug => $menu['slug'],
	  	name => $menu['name'],
	  	description => $menu['description']
	  );
	}
	 
    return $response;		
  }
  
  public function listMenusSchema() {
  	return array(
  	  "title" => "menu",
  	  "properties" => array(
  	  	"id" => array (
  	  	  "type" => "number"
  	  	),
  	  	"slug" => array (
  	  	  "type" => "string"
  	  	),
  	  	"name" => array (
  	  	  "type" => "string"
  	  	),
  	  	"description" => array (
  	  	  "type" => "string"
  	  	)
  	  )
  	);
  }
  
  public function findMenu($id) {
  	$wp_menu = wp_get_nav_menu_object($id);
  	if (empty($wp_menu)) {
  	  return new \WP_Error(404, 'Not found', array( 'status' => 404 ) );
  	} else {
	  $menu = (array) $wp_menu;
	  return array(
	  	id => $menu['term_id'],
	  	slug => $menu['slug'],
	  	name => $menu['name'],
	  	description => $menu['description']
	  );
  	}
  }
  
  public function findMenuSchema() {
  	return array(
  	  "title" => "menu",
  	  "properties" => array(
  	     "id" => array (
  			"type" => "number"
  		 ),
  		 "slug" => array (
  		   "type" => "string"
  		 ),
  		 "name" => array (
  		    "type" => "string"
  		  ),
  		  "description" => array (
  		    "type" => "string"
  		  )
  	  )
  	);
  }
  
}
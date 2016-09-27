<?php
namespace KuntaAPI\Menus\Rest;

defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

require_once ('constants.php');

class Menus {
	
  private $namespace;
  
  function __construct() {
  	$this->namespace = "kunta-api";
  }
  
  public function registerRoutes() {
  	register_rest_route($this->namespace, '/menus', array(
  	  array(
  		'methods'  => \WP_REST_Server::READABLE,
  		'callback' => array($this, 'listMenus')
  	  ),
  	  'schema' => array($this, 'getMenuSchema')
  	));
  	
  	register_rest_route($this->namespace, '/menus/(?P<id>\d+)', array(
  	  array(
  	   'methods'  => \WP_REST_Server::READABLE,
  	   'callback' => array($this, 'findMenu')
      ),
  	  'schema' => array($this, 'getMenuSchema')
  	));
  	
  	register_rest_route($this->namespace, '/menus/(?P<id>\d+)/items', array(
  	  array(
  	   'methods'  => \WP_REST_Server::READABLE,
  	   'callback' => array($this, 'listMenuItems')
      ),
  	  'schema' => array($this, 'getMenuItemSchema')
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
  
  public function findMenu($data) {
  	$wp_menu = wp_get_nav_menu_object($data['id']);
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
  
  public function listMenuItems($data) {
    // TODO: Implement
  	return [];
  }
  
  public function findMenuItem($id) {
  	// TODO: Implement
  	return null;
  }
  
  public function getMenuSchema() {
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
  
  public function getMenuItemSchema() {
  	return array(
  	  "title" => "menuitem",
  	  "properties" => array(
  	  	"id" => array (
  	  	  "type" => "number"
  	  	),
  	  	"slug" => array (
  	  	  "type" => "string"
  	  	),
  	  	"postId" => array (
  	  	  "type" => "number"
  	  	),
  	  	"postParentId" => array (
  	  	  "type" => "number"
  	  	),
  	  	"url" => array (
  	  	  "type" => "string"
  	  	)
  	  )
  	);
  }
  
}
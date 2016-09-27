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
        'callback' => array($this, 'listMenus'),
        'args' => array(
          'slug' => array(
            'default' => null
          ),
        ),
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
  	
  	register_rest_route($this->namespace, '/menus/(?P<menuId>\d+)/items', array(
  	  array(
  	   'methods'  => \WP_REST_Server::READABLE,
  	   'callback' => array($this, 'listMenuItems')
      ),
  	  'schema' => array($this, 'getMenuItemSchema')
  	));
  }
	
  public function listMenus($data) {
    $response = [];
    $filter = array();
    if($data['slug'] != null) {
      $filter['slug'] = $data['slug'];
    }
    $wpMenus = wp_get_nav_menus($filter);
    foreach ($wpMenus as $wpMenu) {
      $menu = (array) $wpMenu;
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
  	$wpMenu = wp_get_nav_menu_object($data['id']);
  	if (empty($wpMenu)) {
  	  return new \WP_Error(404, 'Not found', array( 'status' => 404 ) );
  	} else {
	  $menu = (array) $wpMenu;
	  return array(
	  	id => $menu['term_id'],
	  	slug => $menu['slug'],
	  	name => $menu['name'],
	  	description => $menu['description']
	  );
  	}
  }
  
  public function listMenuItems($data) {
    $response = [];
    $wpMenuItems = wp_get_nav_menu_items($data['menuId']);
    foreach ($wpMenuItems as $wpMenuItem) {
      $item = (array) $wpMenuItem;
      $responseItem = array(
        id => $item['ID'],
        parentItemId => $item['menu_item_parent'] > 0 ? $item['menu_item_parent'] : null,
        title => $item['title'],
        url => $item['url'],
        type => $item['object'],
        order => $item['menu_order'],
        postId => null,
        pageId => null
      );
      if($item['object'] == 'post') {
        $responseItem['postId'] = $item['ID'];
      } else if($item['object'] == 'page') {
        $responseItem['pageId'] = $item['ID'];
      }
      $response[] = $responseItem;
    }
    return $response;
  }
  
  public function getMenuSchema() {
  	return array(
  	  "title" => "menu",
  	  "properties" => array(
  	  	"id" => array (
  	  	  "type" => "integer",
          "format" => "int64"
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
  	  	  "type" => "integer",
          "format" => "int64"
  	  	),
        "parentItemId" => array (
  	  	  "type" => "integer",
          "format" => "int64"
  	  	),
        "type" => array (
  	  	  "type" => "string"
  	  	),
        "title" => array (
  	  	  "type" => "string"
  	  	),
        "order" => array (
  	  	  "type" => "integer",
          "format" => "int64"
  	  	),
  	  	"pageId" => array (
  	  	  "type" => "integer",
          "format" => "int64"
  	  	),
  	  	"postId" => array (
  	  	  "type" => "integer",
          "format" => "int64"
  	  	),
  	  	"url" => array (
  	  	  "type" => "string"
  	  	)
  	  )
  	);
  }
  
}
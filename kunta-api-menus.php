<?php
/*
 * Created on Sep 27, 2016
 * Plugin Name: Kunta API Menus
 * Description: Kunta API Menus plugin for Wordpress
 * Version: 0.1
 * Author: Antti Leppä / Otavan Opisto
 */
defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

require_once ('constants.php');
require_once ('rest.php');

add_action( 'rest_api_init', function () {
  $rest = new KuntaAPI\Menus\Rest\Menus();
  $rest->registerRoutes();
});

?>
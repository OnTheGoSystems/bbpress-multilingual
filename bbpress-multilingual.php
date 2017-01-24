<?php
/*
Plugin Name: bbPress Multilingual
Plugin URI: https://github.com/OnTheGoSystems/bbpress-multilingual
Description: An add-on to enable compatibility between bbPress and WPML
Version: 0.1.0
Author: OnTheGoSystems
Author URI: http://www.onthegosystems.com/
Text Domain: bbpress_multilingual
*/

if ( defined( 'BBPRESS_MULTILINGUAL_VERSION' ) ) {
	return;
}

define( 'BBPRESS_MULTILINGUAL_VERSION', '0.1.0' );
define( 'BBPRESS_MULTILINGUAL_PATH', dirname( __FILE__ ) );

/**
 * Load main plugin class.
 */
require BBPRESS_MULTILINGUAL_PATH . '/classes/class-bbpml.php';

$bbp_multilingual = new BBPML();
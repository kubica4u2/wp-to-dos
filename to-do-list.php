<?php
/**
 *
 * @link              https://www.elegantseagulls.com/
 * @since             1.0.0
 * @package           Elegant Seagulls To Dos
 *
 * @wordpress-plugin
 * Plugin Name:       Elegant Seagulls To Dos
 * Plugin URI:        https://www.elegantseagulls.com/
 * Version:           1.0.0
 * Author:            Elegant Seagulls
 * Author URI:        https://www.elegantseagulls.com/
 * Text Domain:       elegantseagulls
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'ESROOT', plugin_dir_path( __FILE__ ) );
define( 'ESINC', ESROOT . 'inc/' );

require_once( ESROOT . 'inc/class-plugin.php' );

$plugin = new \ES\ToDos\Plugin();

register_activation_hook( __FILE__, function () use( $plugin ){
    $plugin->activate();
} );
register_deactivation_hook( __FILE__, function() use( $plugin ){
    $plugin->deactivate();
} );

$plugin->run();

<?php
/**
 * Plugin Name: Watchful App: Changed Files
 * Plugin URI: https://app.watchful.net
 * Description: An app for Watchful.net to monitor a custom list of files for changes.
 * Version: 1.0.1
 * Author: watchful
 * Author URI: https://watchful.net/apps/
 * License: GPL
 * @package watchful
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once 'autoloader.php';

spl_autoload_register( 'watchful_app_changed_files_class_loader' );

register_activation_hook( __FILE__, array( 'Watchful\App\ChangedFiles\Init', 'activation' ) );
register_uninstall_hook( __FILE__, array( 'Watchful\App\ChangedFiles\Init', 'uninstall' ) );

$detector = new Watchful\App\ChangedFiles\Detector();
$detector->run_detector();

add_action( 'admin_init', array( 'Watchful\App\ChangedFiles\Init', 'admin_init' ));

if ( is_admin() ) {
	$my_settings_page = new Watchful\App\ChangedFiles\Settings();
	$my_settings_page->init();
}

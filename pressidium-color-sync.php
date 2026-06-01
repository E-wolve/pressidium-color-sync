<?php
/**
 * Plugin Name: Pressidium Color Sync
 * Plugin URI: https://ewolve.nl/pressidium-color-sync
 * Description: Synchroniseer Core Framework kleuren automatisch naar Pressidium Cookie Consent.
 * Version: 1.0.0
 * Author: Ewolve
 * Author URI: https://ewolve.nl
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pressidium-color-sync
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package PressidiumColorSync
 */

namespace PressidiumColorSync;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} elseif ( file_exists( __DIR__ . '/plugin-update-checker/plugin-update-checker.php' ) ) {
	require_once __DIR__ . '/plugin-update-checker/plugin-update-checker.php';
}

if ( class_exists( '\YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
	\YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/E-wolve/pressidium-color-sync',
		__FILE__,
		'pressidium-color-sync'
	);
}

define( 'PCS_VERSION', '1.0.0' );
define( 'PCS_PLUGIN_FILE', __FILE__ );
define( 'PCS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PCS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PCS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

require_once PCS_PLUGIN_DIR . 'includes/class-plugin.php';

register_deactivation_hook( __FILE__, function () {
	flush_rewrite_rules();
} );

function pressidium_color_sync_init() {
	return Plugin::instance();
}

pressidium_color_sync_init();

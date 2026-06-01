<?php
/**
 * Main Plugin Class
 *
 * @package PressidiumColorSync
 */

namespace PressidiumColorSync;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	private static $instance = null;

	public $admin;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->init_hooks();
		$this->load_dependencies();
	}

	private function load_dependencies() {
		require_once PCS_PLUGIN_DIR . 'includes/class-core-framework.php';
		require_once PCS_PLUGIN_DIR . 'includes/class-admin.php';
	}

	private function init_hooks() {
		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
		add_action( 'init', [ $this, 'load_textdomain' ] );
	}

	public function on_plugins_loaded() {
		if ( ! function_exists( 'CoreFramework' ) ) {
			add_action( 'admin_notices', function () {
				echo '<div class="notice notice-warning"><p>';
				echo '<strong>Pressidium Color Sync:</strong> ';
				esc_html_e( 'Core Framework plugin is vereist.', 'pressidium-color-sync' );
				echo '</p></div>';
			} );
			return;
		}

		$this->admin = new Admin( new Core_Framework() );
	}

	public function load_textdomain() {
		load_plugin_textdomain(
			'pressidium-color-sync',
			false,
			dirname( PCS_PLUGIN_BASENAME ) . '/languages'
		);
	}

	private function __clone() {}

	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}
}

<?php
/**
 * Plugin Name: BuddyUp
 * Description: Custom addon of BuddyBoss with group check-in functionality
 * Version:     1.0.0
 * Text Domain: bp-group-check-in
 * Domain Path: /languages/
 */

/**
 * This file should always remain compatible with the minimum version of
 * PHP supported by WordPress.
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Define WCE Constants
 */

global $wpdb;

define_constants( 'BPGCI_ADDON_PLUGIN_FILE', __FILE__ );
define_constants( 'BPGCI_ADDON_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define_constants( 'BPGCI_ADDON_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define_constants( 'BPGCI_ADDON_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define_constants( 'BPGCI_ADDON_PLUGIN', array(
			'name' => 'buddyboss_group_check_in',
			'version' => '1.0.0',
			'table_name' => $wpdb->prefix . 'bp_group_check_in',
			'time_zone' => 'America/Los_Angeles'
		) );
define_constants( 'PAGE_TYPE_SINGLE_GROUP', 'single_group' );
define_constants( 'PAGE_TYPE_ALL_GROUPS', 'all_groups' );

date_default_timezone_set( BPGCI_ADDON_PLUGIN['time_zone'] );

/**
 * Define constant if not already set
 * @param  string $name
 * @param  string|bool $value
 */
function define_constants( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

include_once( 'activation.php' );


if ( ! class_exists( 'BPGCI_Addon' ) ) {

	/**
	 * Main MYPlugin Custom Emails Class
	 *
	 * @class BPGCI_Addon
	 * @version	1.0.0
	 */
	final class BPGCI_Addon {

		/**
		 * @var BPGCI_Addon The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main BPGCI_Addon Instance
		 *
		 * Ensures only one instance of BPGCI_Addon is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see BPGCI_Addon()
		 * @return BPGCI_Addon - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Cloning is forbidden.
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', '' ), '1.0.0' );
		}
		/**
		 * Unserializing instances of this class is forbidden.
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', '' ), '1.0.0' );
		}

		/**
		 * BPGCI_Addon Constructor.
		 */
		public function __construct() {
			$this->requires();

			// Add link to settings page.
			add_filter( 'plugin_action_links',               array( $this, 'action_links' ), 10, 2 );
			add_filter( 'network_admin_plugin_action_links', array( $this, 'action_links' ), 10, 2 );

			// Set up localisation.
			$this->load_plugin_textdomain();
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 */
		public function requires() {
			require_once( 'admin/enqueue-scripts.php' );
			require_once( 'admin/settings.php' );
			require_once( 'admin/report/index.php' );

			require_once( 'view/shortcode.php' );
			require_once( 'view/single_group.php' );
			require_once( 'view/single_member.php' );
			//require_once( 'admin-option.php' );
		}

		/**
		 * Get the plugin url.
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugins_url( '/', __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Load Localisation files.
		 *
		 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
		 */
		public function load_plugin_textdomain() {
			$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
			$locale = apply_filters( 'plugin_locale', $locale, '' );

			unload_textdomain( '' );
			load_textdomain( '', WP_LANG_DIR . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' . plugin_basename( dirname( __FILE__ ) ) . '-' . $locale . '.mo' );
			load_plugin_textdomain( '', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}


		public function action_links( $links, $file ) {

			// Return normal links if not BuddyPress.
			if ( BPGCI_ADDON_PLUGIN_BASENAME != $file ) {
				return $links;
			}

			// Add a few links to the existing links array.
			return array_merge(
				$links,
				array(
					'settings' => '<a href="' . esc_url( bp_get_admin_url( 'admin.php?page=bp-settings&tab=bp-groups' ) ) . '">' . __( 'Settings', 'bp-group-check-in' ) . '</a>',
				)
			);
		}

	}

	/**
	 * Returns the main instance of BPGCI_Addon to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return BPGCI_Addon
	 */
	function BPGCI_Addon() {
		return BPGCI_Addon::instance();
	}

	function BPGCI_BB_Platform_install_bb_platform_notice() {
		echo '<div class="error fade"><p>';
		_e('<strong>BuddyBoss Group Check-In</strong></a> requires the BuddyBoss Platform plugin to work. Please <a href="https://buddyboss.com/platform/" target="_blank">install BuddyBoss Platform</a> first.', '');
		echo '</p></div>';
	}

	function BPGCI_BB_Platform_update_bb_platform_notice() {
		echo '<div class="error fade"><p>';
		_e('<strong>BuddyBoss Group Check-In</strong></a> requires BuddyBoss Platform plugin version 1.2.6 or higher to work. Please update BuddyBoss Platform.', '');
		echo '</p></div>';
	}

	function BPGCI_BB_Platform_is_active() {
		if ( defined( 'BP_PLATFORM_VERSION' ) && version_compare( BP_PLATFORM_VERSION,'1.2.6', '>=' ) ) {
			return true;
		}
		return false;
	}

	function BPGCI_BB_Platform_init() {
		if ( ! defined( 'BP_PLATFORM_VERSION' ) ) {
			add_action( 'admin_notices', 'BPGCI_BB_Platform_install_bb_platform_notice' );
			add_action( 'network_admin_notices', 'BPGCI_BB_Platform_install_bb_platform_notice' );
			return;
		}

		if ( version_compare( BP_PLATFORM_VERSION,'1.2.6', '<' ) ) {
			add_action( 'admin_notices', 'BPGCI_BB_Platform_update_bb_platform_notice' );
			add_action( 'network_admin_notices', 'BPGCI_BB_Platform_update_bb_platform_notice' );
			return;
		}

		BPGCI_Addon();
	}

	add_action( 'plugins_loaded', 'BPGCI_BB_Platform_init', 9 );

}

<?php
/**
 * Plugin Name: Buddy Up
 * Description: Custom addon of BuddyBoss with group check-in functionality
 * Version:     1.3.0
 * Text Domain: bp-group-check-in
 * Domain Path: /languages/
 */

/*
  Option name from divi-up - du_option_buddy_up_check_in
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

// get group slug
$page_slug = '';
$group_slug = '';
$url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');

if($url_path) {

	$url_path_arr = explode('/', $url_path);
	$page_slug_index = count($url_path_arr) - 1 - 1;
	$group_slug_index = count($url_path_arr) - 1;

	if( count($url_path_arr) > 1 ) {
		$page_slug = $url_path_arr[ $page_slug_index ];
		$group_slug = $url_path_arr[ $group_slug_index ];
	}

}

$bpgci_is_enabled = absint(get_option( 'bpg-enable-check-in', 0 )) && get_option( 'du_option_buddy_up_check_in', 'off' );

define_constants( 'BPGCI_ADDON_PLUGIN_FILE', __FILE__ );
define_constants( 'BPGCI_ADDON_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define_constants( 'BPGCI_PATH', plugin_dir_path( __FILE__ ) );
define_constants( 'BPGCI_URL', plugin_dir_url( __FILE__ ) );
define_constants( 'BPGCI_ADDON_PLUGIN', array(
			'name' => 'buddyboss_group_check_in',
			'version' => '1.2.1',
			'table_name' => $wpdb->prefix . 'bpgci_bp_habit_check_in',
			'time_zone' => 'America/Los_Angeles'
		) );
define_constants( 'BPGCI_PAGE_TYPE_SINGLE_GROUP', 'single_group' );
define_constants( 'BPGCI_PAGE_TYPE_ALL_GROUPS', 'all_groups' );
define_constants( 'BPGCI_MENU_SLUG', 'buddy-up' );
define_constants( 'BPGCI_PPAGE_SLUG', $page_slug );
define_constants( 'BPGCI_GROUP_SLUG', $group_slug );
define_constants( 'BPGCI_IS_ENABLED', $bpgci_is_enabled );

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

			if (
				is_plugin_active( 'wordpress-seo/wp-seo.php' ) ||
			  is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ) {
			       add_filter( 'wpseo_title', array($this, 'yoast_change_group_page_title') );
			}else {
			   add_filter( 'document_title_parts', array($this, 'change_group_page_title') );
			}

			add_filter('template_include', array( $this, 'load_group_page' ) );
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
			require_once( 'admin/group_postbox.php' );

			require_once( 'view/shortcode.php' );
			require_once( 'view/single_group.php' );
			require_once( 'view/single_member.php' );

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

		public function load_group_page( $template ) {

			if( BPGCI_PPAGE_SLUG === 'check-in' ) {
				$template = BPGCI_PATH . 'view/group_page.php';
			}

			return $template;

		}

		public function yoast_change_group_page_title($title) {

			if ( BPGCI_PPAGE_SLUG === 'check-in' ) {
				$new_title = ucwords( str_replace( '-', ' ', esc_html(BPGCI_GROUP_SLUG)) );
				if (strpos($title, 'Page not found') !== false) {
				  $title = str_replace('Page not found', $new_title, $title );
				}else {
					$title = $new_title;
				}
	    }
	    return $title;
		}

		public function change_group_page_title($title_parts) {
			if ( BPGCI_PPAGE_SLUG === 'check-in' ) {
	        $title_parts['title'] = ucwords( str_replace( '-', ' ', esc_html(BPGCI_GROUP_SLUG)) );
	    }
	    return $title_parts;
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
		_e('<strong>Buddy Up</strong></a> requires the BuddyBoss Platform plugin to work. Please <a href="https://buddyboss.com/platform/" target="_blank">install BuddyBoss Platform</a> first.', '');
		echo '</p></div>';
	}

	function BPGCI_BB_Platform_update_bb_platform_notice() {
		echo '<div class="error fade"><p>';
		_e('<strong>Buddy Up</strong></a> requires BuddyBoss Platform plugin version 1.2.6 or higher to work. Please update BuddyBoss Platform.', '');
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

		if ( version_compare( BP_PLATFORM_VERSION, '1.2.6', '<' ) ) {
			add_action( 'admin_notices', 'BPGCI_BB_Platform_update_bb_platform_notice' );
			add_action( 'network_admin_notices', 'BPGCI_BB_Platform_update_bb_platform_notice' );
			return;
		}

		BPGCI_Addon();
	}

	add_action( 'plugins_loaded', 'BPGCI_BB_Platform_init', 9 );

}

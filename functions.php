<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;



if ( ! function_exists( 'BGCI_admin_enqueue_script' ) ) {
	function BGCI_admin_enqueue_script() {

		$group_id = isset( $_REQUEST['gid'] ) ? (int) $_REQUEST['gid'] : '';

		wp_enqueue_style( 'bgci-admin-css', plugin_dir_url( __FILE__ ) . 'style.css' );
		wp_enqueue_script( 'bgci-admin-js', plugin_dir_url( __FILE__ ) . 'js/script.js' );
		wp_localize_script( 'bgci-admin-js', 'bgci_args', array(
			'siteURL' => site_url(),
			'groupID' => $group_id
		));

	}

	add_action( 'admin_enqueue_scripts', 'BGCI_admin_enqueue_script' );
}

if ( ! function_exists( 'BGCI_get_settings_sections' ) ) {
	function BGCI_get_settings_sections() {

		$settings = array(
			'BGCI_settings_section' => array(
				'page'  => 'addon',
				'title' => __( 'Add-on Settings', '' ),
			),
		);

		return (array) apply_filters( 'BGCI_get_settings_sections', $settings );
	}
}

if ( ! function_exists( 'BGCI_get_settings_fields_for_section' ) ) {
	function BGCI_get_settings_fields_for_section( $section_id = '' ) {

		// Bail if section is empty
		if ( empty( $section_id ) ) {
			return false;
		}

		$fields = BGCI_get_settings_fields();
		$retval = isset( $fields[ $section_id ] ) ? $fields[ $section_id ] : false;

		return (array) apply_filters( 'BGCI_get_settings_fields_for_section', $retval, $section_id );
	}
}

if ( ! function_exists( 'BGCI_get_settings_fields' ) ) {
	function BGCI_get_settings_fields() {

		$fields = array();

		$fields['BGCI_settings_section'] = array(

			'BGCI_field' => array(
				'title'             => __( 'Add-on Field', '' ),
				'callback'          => 'BGCI_settings_callback_field',
				'sanitize_callback' => 'absint',
				'args'              => array(),
			),

		);

		return (array) apply_filters( 'BGCI_get_settings_fields', $fields );
	}
}

if ( ! function_exists( 'BGCI_settings_callback_field' ) ) {
	function BGCI_settings_callback_field() {
		?>
        <input name="BGCI_field"
               id="BGCI_field"
               type="checkbox"
               value="1"
			<?php checked( BGCI_is_addon_field_enabled() ); ?>
        />
        <label for="BGCI_field">
			<?php _e( 'Enable this option', '' ); ?>
        </label>
		<?php
	}
}

if ( ! function_exists( 'BGCI_is_addon_field_enabled' ) ) {
	function BGCI_is_addon_field_enabled( $default = 1 ) {
		return (bool) apply_filters( 'BGCI_is_addon_field_enabled', (bool) get_option( 'BGCI_field', $default ) );
	}
}

/***************************** Add section in current settings ***************************************/

/**
 * Register fields for settings hooks
 * bp_admin_setting_general_register_fields
 * bp_admin_setting_xprofile_register_fields
 * bp_admin_setting_groups_register_fields
 * bp_admin_setting_forums_register_fields
 * bp_admin_setting_activity_register_fields
 * bp_admin_setting_media_register_fields
 * bp_admin_setting_friends_register_fields
 * bp_admin_setting_invites_register_fields
 * bp_admin_setting_search_register_fields
 */
if ( ! function_exists( 'BGCI_bp_admin_setting_groups_register_fields' ) ) {
    function BGCI_bp_admin_setting_groups_register_fields( $setting ) {
	    // Main General Settings Section
	    $setting->add_section( 'BGCI_addon', __( 'Group Check-in', '' ) );

	    $args          = array();
	    $setting->add_field( 'bp-enable-check-in', __( 'Check-in', '' ), 'BGCI_admin_general_setting_callback_my_addon', 'intval', $args );
    }

	add_action( 'bp_admin_setting_groups_register_fields', 'BGCI_bp_admin_setting_groups_register_fields' );
}

if ( ! function_exists( 'BGCI_admin_general_setting_callback_my_addon' ) ) {
	function BGCI_admin_general_setting_callback_my_addon() {
		?>
        <input id="bp-enable-check-in" name="bp-enable-check-in" type="checkbox"
               value="1" <?php checked( BGCI_enable_check_in() ); ?> />
        <label for="bp-enable-check-in"><?php _e( 'Enable Check-in Option', '' ); ?></label>
		<?php
	}
}

if ( ! function_exists( 'BGCI_enable_check_in' ) ) {
	function BGCI_enable_check_in( $default = false ) {
		return (bool) apply_filters( 'BGCI_enable_check_in', (bool) bp_get_option( 'bp-enable-check-in', $default ) );
	}
}

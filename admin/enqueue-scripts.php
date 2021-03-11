<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'BPGCI_admin_enqueue_script' ) ) {
	function BPGCI_admin_enqueue_script() {

		$group_id = isset( $_REQUEST['gid'] ) ? (int) $_REQUEST['gid'] : '';

		wp_enqueue_style( 'bgci-admin-css', plugin_dir_url( __FILE__ ) . '../css/admin-style.css' );
		wp_enqueue_script( 'bgci-admin-js', plugin_dir_url( __FILE__ ) . '../js/admin-script.js' );
		wp_localize_script( 'bgci-admin-js', 'BPGCI_args', array(
			'siteURL' => esc_url(site_url()),
			'group_id' => absint($group_id),
			'isCheckinEnabled' => absint(bp_get_option( 'bpg-enable-check-in', 0 ))
		));

	}

	add_action( 'admin_enqueue_scripts', 'BPGCI_admin_enqueue_script' );
}

if( ! function_exists( 'BPGCI_view_enqueue_script' ) ) {
	function BPGCI_view_enqueue_script() {
		wp_register_style( 'bgci-view-css', plugin_dir_url( __FILE__ ) . '../css/view-style.css' );
		wp_register_style( 'bgci-check-in-form-css', plugin_dir_url( __FILE__ ) . '../css/check-in-form-style.css' );
		wp_register_style( 'bgci-check-in-status-css', plugin_dir_url( __FILE__ ) . '../css/check-in-status-style.css' );
	}

	add_action( 'wp_enqueue_scripts', 'BPGCI_view_enqueue_script' );
}

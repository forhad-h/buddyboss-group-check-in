<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'BPGCI_admin_enqueue_script' ) ) {
	function BPGCI_admin_enqueue_script() {

		wp_enqueue_style( 'bgci-admin-css', BPGCI_URL . 'css/admin-style.css' );
		wp_enqueue_script( 'bgci-admin-js', BPGCI_URL . 'js/admin-script.js' );
		wp_localize_script( 'bgci-admin-js', 'BPGCI_args', array(
			'siteURL' => esc_url(site_url()),
			'isCheckinEnabled' => absint(bp_get_option( 'bpg-enable-check-in', 0 ))
		));

	}

	add_action( 'admin_enqueue_scripts', 'BPGCI_admin_enqueue_script' );
}

if( ! function_exists( 'BPGCI_view_enqueue_script' ) ) {

	function BPGCI_view_enqueue_script() {
		wp_register_style( 'bgci-view-css', BPGCI_URL . 'css/view-style.css' );
		wp_register_style( 'bgci-check-in-form-css', BPGCI_URL . 'css/check-in-form-style.css' );
		wp_register_style( 'bgci-check-in-status-css', BPGCI_URL . 'css/check-in-status-style.css' );
	}

	add_action( 'wp_enqueue_scripts', 'BPGCI_view_enqueue_script' );
}

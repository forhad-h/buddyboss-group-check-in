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
			'groupID' => absint($group_id),
			'isCheckinEnabled' => absint(bp_get_option( 'bpg-enable-check-in', 0 ))
		));

	}

	add_action( 'admin_enqueue_scripts', 'BPGCI_admin_enqueue_script' );
}

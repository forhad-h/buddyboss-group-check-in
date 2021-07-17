<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


if( ! function_exists('BPGCI_group_check_in_report_page_content') ) {
  function BPGCI_group_check_in_report_page_content() {

    require_once('get_data.php');
    require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/remote_get.php' );

    global $wpdb;

    $get_all_data = BPGCI_get_all_data($wpdb);

    $group_ids = array_map( function($item){
      return $item->group_id;
    }, $get_all_data);

    $group_ids = array_unique($group_ids);

    if( isset($_GET['page_type']) && $_GET['page_type'] === BPGCI_PAGE_TYPE_SINGLE_GROUP ) {
      require_once( 'single_group_report.php' );
      BPGCI_single_group_report( $wpdb );
    }else {
      require_once( 'all_groups_report.php' );
      BPGCI_all_groups_report( $wpdb, $group_ids );
    }

  }
}

if( ! function_exists('BPGCI_create_submenu_page') ) {

  function BPGCI_create_submenu_page() {

    $hook = add_submenu_page(
      BPGCI_MENU_SLUG,
      __( 'Buddy Up Report', 'bp-group-check-in' ),
      __( 'Report', 'bp-group-check-in' ),
      'publish_pages',
      'buddy-up-report',
      'BPGCI_group_check_in_report_page_content',
      3
    );

    remove_submenu_page( BPGCI_MENU_SLUG, BPGCI_MENU_SLUG );

    add_action( "load-$hook", 'BPGCI_add_options' );

  }

  add_action( 'admin_menu', 'BPGCI_create_submenu_page', 10 );

}

if( ! function_exists( 'BPGCI_add_options' ) ) {
  function BPGCI_add_options() {

    $option = 'per_page';
    $args = array(
      'label'     => 'Groups',
      'default'   => 10,
      'option'    => 'groups_per_page'
    );

    add_screen_option( $option, $args );

  }

}

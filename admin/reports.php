<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists('BPGCI_group_check_in_reports_page_content') ) {
  function BPGCI_group_check_in_reports_page_content() {
    ?>
      <h1>Group Check-in Reports</h1>
    <?php
  }
}

if( ! function_exists('create_submenu_page') ) {

  function create_submenu_page() {

    add_submenu_page(
      'buddyboss-platform',
      __( 'Group Check-in Reports', 'bp-group-check-in' ),
      __( 'Group Check-in Reports', 'bp-group-check-in' ),
      'bp_moderate',
      'group-check-in-reports',
      'BPGCI_group_check_in_reports_page_content'
    );

  }

  add_action( bp_core_admin_hook(), 'create_submenu_page', 160 );

}

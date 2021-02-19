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

    ?>
    <div class="bpgci_report_wrapper">
      <h1>Group Check-in Report</h1>
      <?php
      if(isset($_GET['group_id'])) {
        require_once( 'group_report.php' );
        BPGCI_group_report($wpdb);
      }else {
        require_once( 'group_tiles.php' );
        BPGCI_group_tiles($wpdb, $group_ids);
      }
      ?>
    </div>

    <?php
  }
}

if( ! function_exists('BPGCI_create_menu_page') ) {

  function BPGCI_create_menu_page() {

    add_menu_page(
      __( 'Group Check-in Report', 'bp-group-check-in' ),
      __( 'Group Check-in Report', 'bp-group-check-in' ),
      'publish_pages',
      'group-check-in-report',
      'BPGCI_group_check_in_report_page_content'
    );

  }

  add_action( 'admin_menu', 'BPGCI_create_menu_page', 10 );

}

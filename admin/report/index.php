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
    <div class="bpgci_report_wrapper wrap">
      <h1 class="wp-heading-inline"><?= __( 'BuddyUp Report', 'bp-group-check-in' ); ?></h1>
      <?php
      if(isset($_GET['group_id'])) {
        require_once( 'group_report.php' );
        BPGCI_group_report($wpdb);
      }else {
        require_once( 'groups_list_table.php' );
        BPGCI_groups_list_table($wpdb, $group_ids);
      }
      ?>
    </div>

    <?php
  }
}

if( ! function_exists('BPGCI_create_menu_page') ) {

  function BPGCI_create_menu_page() {

    $hook = add_menu_page(
      __( 'BuddyUp', 'bp-group-check-in' ),
      __( 'BuddyUp', 'bp-group-check-in' ),
      'publish_pages',
      'group-check-in-report',
      'BPGCI_group_check_in_report_page_content'
    );

    add_action( "load-$hook", 'BPGCI_add_options' );

  }

  add_action( 'admin_menu', 'BPGCI_create_menu_page', 10 );

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

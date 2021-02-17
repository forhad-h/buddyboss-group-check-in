<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists('BPGCI_group_check_in_reports_page_content') ) {
  function BPGCI_group_check_in_reports_page_content() {

    require_once('get_data.php');
    require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/remote_get.php' );

    global $wpdb;
    $group_ids = [];

    $get_all_data = BPGCI_get_all_data($wpdb);

    foreach( $get_all_data as $data ) {
      array_push($group_ids, $data->group_id);
    }

    $group_ids = array_unique($group_ids);

    ?>
      <h1>Group Check-in Reports</h1>
      <div class="bpgci_tiles">
        <?php
          require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/remote_get.php' );

          foreach($group_ids as $group_id):
            $group = BPGCI_get_group($group_id);

            require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/count.php' );
            $count_complete = BPGCI_count_data( $wpdb, $group_id, 'complete' );
            $count_pending = BPGCI_count_data( $wpdb, $group_id, 'pending' );
            $count_incomplete = BPGCI_count_data( $wpdb, $group_id, 'incomplete' );
            $count_partially_complete = BPGCI_count_data( $wpdb, $group_id, 'partially_complete' );
        ?>
          <div class="bpgci_each_tile">
            <h2><?= $group['name']; ?></h2>
            <ul>
              <li class="total_complete"><?= $count_complete; ?> Complete </li>
              <li class="total_pending"><?= $count_pending; ?> Pending </li>
              <li class="total_partially_completed"><?= $count_incomplete; ?> Incomplete </li>
              <li class="total_incomplete"><?= $count_partially_complete; ?> Partially Complete </li>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    <?php
  }
}

if( ! function_exists('BPGCI_create_menu_page') ) {

  function BPGCI_create_menu_page() {

    add_menu_page(
      __( 'Group Check-in Reports', 'bp-group-check-in' ),
      __( 'Group Check-in Reports', 'bp-group-check-in' ),
      'publish_pages',
      'group-check-in-reports',
      'BPGCI_group_check_in_reports_page_content'
    );

  }

  add_action( 'admin_menu', 'BPGCI_create_menu_page', 10 );

}

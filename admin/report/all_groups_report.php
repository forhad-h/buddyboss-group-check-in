<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'BPGCI_all_groups_report' ) ) {
  function BPGCI_all_groups_report($wpdb, $group_ids) {

  ?>

  <?php
    $list_data = array();

    foreach( $group_ids as $gid ) {
      // get group
      $group = BPGCI_get_group($gid);

      require_once( BPGCI_PATH . 'data/count.php' );

      $group_id = $group['id'] ? $group['id'] : (int)$gid;
      $group_name = $group['name'] ? $group['name'] : '(Not Found!)';

      $count_complete = BPGCI_count_data_by_group_and_status( $wpdb, $group_id, 'complete' );
      $count_pending = BPGCI_count_data_by_group_and_status( $wpdb, $group_id, 'pending' );
      $count_incomplete = BPGCI_count_data_by_group_and_status( $wpdb, $group_id, 'incomplete' );
      $count_partially_complete = BPGCI_count_data_by_group_and_status( $wpdb, $group_id, 'partially_complete' );

      $each_row = array(
        'ID' => $group_id,
        'group_name' => $group_name,
        'complete' => $count_complete,
        'pending' => $count_pending,
        'incomplete' => $count_incomplete,
        'partially_complete' => $count_partially_complete,
      );

      array_push( $list_data, $each_row );

    }

  ?>
    <div class="bpgci_all_groups_report_table wrap">
      <h1 class="wp-heading-inline"><?= __( 'Buddy Up All Groups Report', 'bp-group-check-in' ); ?></h1>

      <form method="post" id="bpgci-action-form">
        <input type="hidden" name="page" value="groups_list_table">

        <?php
          // require- BPGCI_List_Table
          require_once( BPGCI_PATH . 'admin/list_table.php' );
          $BPGCI_groups_data_table = new BPGCI_List_Table( BPGCI_PAGE_TYPE_ALL_GROUPS, $list_data );
          $BPGCI_groups_data_table->prepare_items();
          $BPGCI_groups_data_table->display();
        ?>

      </form>
    </div>

  <?php


  }
}

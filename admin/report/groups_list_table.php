<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// access to class - BPGCI_List_Table
require_once( __DIR__ . '/../list_table.php' );

if( ! function_exists( 'BPGCI_groups_list_table' ) ) {
  function BPGCI_groups_list_table($wpdb, $group_ids) {

  ?>

    <div class="bpgci_tiles">
      <?php
        require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/remote_get.php' );

        foreach( $group_ids as $group_id ):
          $group = BPGCI_get_group($group_id);

          require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/count.php' );
          $count_complete = BPGCI_count_data_by_group_and_status( $wpdb, $group_id, 'complete' );
          $count_pending = BPGCI_count_data_by_group_and_status( $wpdb, $group_id, 'pending' );
          $count_incomplete = BPGCI_count_data_by_group_and_status( $wpdb, $group_id, 'incomplete' );
          $count_partially_complete = BPGCI_count_data_by_group_and_status( $wpdb, $group_id, 'partially_complete' );
      ?>
        <a class="bpgci_each_tile" href="<?= $_SERVER['REQUEST_URI'] . '&group_id=' . $group_id; ?>">
          <h2><?= $group['name']; ?></h2>
          <ul>
            <li class="total_complete"><?= $count_complete; ?> Complete </li>
            <li class="total_pending"><?= $count_pending; ?> Pending </li>
            <li class="total_partially_completed"><?= $count_partially_complete; ?> Partially Complete </li>
            <li class="total_incomplete"><?= $count_incomplete; ?> Incomplete </li>
          </ul>
        </a>
      <?php endforeach; ?>
    </div>



  <?php
    $list_data = array();

    foreach( $group_ids as $group_id ) {
      // get group
      $group = BPGCI_get_group($group_id);

      require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/count.php' );

      $group_id = $group['id'];
      $group_name = $group['name'];

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
    <div class="wrap">
      <h1 class="wp-heading-inline"><?= __( 'BuddyUp Report', 'bp-group-check-in' ); ?></h1>

      <form method="post">
        <input type="hidden" name="page" value="groups_list_table">

        <?php
          $BPGCI_groups_data_table = new BPGCI_List_Table( 'groups', $list_data );
          $BPGCI_groups_data_table->prepare_items();
          $BPGCI_groups_data_table->display();
        ?>

      </form>
    </div>

  <?php


  }
}

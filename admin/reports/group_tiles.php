<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'BPGCI_group_tiles' ) ) {
  function BPGCI_group_tiles($wpdb, $group_ids) {

  ?>

    <div class="bpgci_tiles">
      <?php
        require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/remote_get.php' );

        foreach($group_ids as $group_id):
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
  }
}

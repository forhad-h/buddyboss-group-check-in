<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists('BPGCI_check_in_status') ) {

  function BPGCI_check_in_status( $group_id, $member_id ) {

    global $wpdb;

    // get group info
    $is_group_check_in_enabled = absint(bp_get_option( 'bpg-enable-check-in', 0 ));

    require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/remote_get.php' );
    $group = BPGCI_get_group( $group_id, $member_id );

    // get members info
    $memebers = BPGCI_get_members( $group_id );

    // date
    $current_date =  date('Y-m-d');
    $formatted_date = date("F j, Y");

    // Get check-in data
    require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/count.php' );
    $count_complete = BPGCI_count_data_by_user_group_status( $wpdb, $member_id, $group_id, 'complete', $current_date );
    $count_pending = BPGCI_count_data_by_user_group_status( $wpdb, $member_id, $group_id, 'pending', $current_date );
    $count_incomplete = BPGCI_count_data_by_user_group_status( $wpdb, $member_id, $group_id, 'incomplete', $current_date );
    $count_partially_complete = BPGCI_count_data_by_user_group_status( $wpdb, $member_id, $group_id, 'partially_complete', $current_date );

    // enqueue view stylesheet
    wp_enqueue_style( 'bgci-check-in-status-css' );

    ob_start();
?>
    <?php if ( ! $group_id ): ?>
      <p class="bpgci_notice"><strong>Not found!</strong> Group not exists.</p>
    <?php return ob_get_clean(); endif; ?>

    <?php if ( ! $group['is_member'] ): ?>
      <p class="bpgci_notice"><strong>Access forbidden!</strong> You are not allowed to see contents.</p>
    <?php  return ob_get_clean(); endif; ?>

    <?php if ( ! $is_group_check_in_enabled ): ?>
      <p class="bpgci_notice"><strong>Not enabled!</strong> Check-in option is not enabled for group - <a href="<?= esc_url( bp_get_admin_url( 'admin.php?page=bp-settings&tab=bp-groups' ) )?>" target="_new"> <?= __( 'Enable', 'bp-group-check-in' ); ?></a></p>
    <?php  return ob_get_clean(); endif; ?>


    <div class="bpgci_group_check_in_status_wrapper">
      <div class="bpgci_group_info">
        <h2><?= $group['name']; ?></h2>
      </div>

      <div class="bpcgi_divider"></div>

      <div class="bpgci_total_result">
        <p><span class="result_complete"><?= $count_complete; ?> Complete</span>, <span class="result_pending"><?= $count_pending; ?> Pending</span>, <span class="result_partially_completed"><?= $count_partially_complete; ?> Partially Completed</span>, <span class="result_incomplete"><?= $count_incomplete; ?> Incomplete</span></p>
      </div>

    </div>

<?php

    return ob_get_clean();

  }

}

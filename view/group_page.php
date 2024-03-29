<?php
get_header();


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

require_once( 'store_data.php' );


    global $wpdb;

    // get group info


    require_once( BPGCI_PATH . 'data/get.php' );

    $group = BPGCI_group_by_slug( $wpdb, BPGCI_GROUP_SLUG );

    if($group) {
      $group_id = $group->id;
      $group_name = $group->name;
      $group_desc = $group->description;

      require_once( BPGCI_PATH . 'data/remote_get.php' );

      // get members info
      $memebers = BPGCI_get_members( $group_id );

      // get current user info
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;

      $is_group_member = BPGCI_is_group_member( $wpdb, $group_id, $user_id);

      // store data
      global $bpgci_success;
      global $bpgci_error;
      $current_date =  date('Y-m-d');

      // Is submitted
      require_once( 'is_submitted.php' );
      $is_submitted = BPGCI_is_submitted($wpdb, $group_id, $user_id, $current_date);

      if( $_SERVER['REQUEST_METHOD'] == 'POST' && !$is_submitted ) {

        $data = array(
          'group_id' => $group->id,
          'user_id' => $current_user->ID,
          'date' => $current_date,
          'status' => $_POST['status']
        );

        require_once( 'store_data.php' );

        BPGCI_store_data( $wpdb, $data );
      }

      // Get check-in data
      $formatted_date = date("F j, Y, g:i a");

      require_once( BPGCI_PATH . 'data/count.php' );
      $count_complete = BPGCI_count_data_by_group_status_and_date( $wpdb, $group_id, 'complete', $current_date );
      $count_pending = BPGCI_count_data_by_group_status_and_date( $wpdb, $group_id, 'pending', $current_date );
      $count_incomplete = BPGCI_count_data_by_group_status_and_date( $wpdb, $group_id, 'incomplete', $current_date );
      $count_partially_complete = BPGCI_count_data_by_group_status_and_date( $wpdb, $group_id, 'partially_complete', $current_date );

      // enqueue view stylesheet
      wp_enqueue_style( 'bgci-view-css' );
    }
?>

<?php if ( ! $group ): ?>
  <p class="bpgci_notice"><strong>Not found!</strong> Group not exists.</p>

<?php elseif ( ! $is_group_member ): ?>
  <p class="bpgci_notice"><strong>Access forbidden!</strong> You are not allowed to see contents.</p>

<?php elseif ( ! BPGCI_IS_ENABLED ): ?>
  <p class="bpgci_notice"><strong>Not enabled!</strong> Check-in option is not enabled for group. <a href="<?= esc_url( bp_get_admin_url( 'admin.php?page=bp-settings&tab=bp-groups' ) )?>" target="_new"> <?php //echo __( 'Enable Now', 'bp-group-check-in' ); ?></a></p>

<?php else: ?>

<div id="group-check-in-wrapper">
  <div class="bpgci_group_info">
    <h2><?= $group_name; ?></h2>
    <p><?= $group_desc; ?></p>
  </div>

  <div class="bpcgi_divider"></div>

  <div class="bpgci_today">
    <h2><?= $formatted_date; ?></h2>
  </div>

  <div class="bpgci_total_result">
    <p><span class="result_complete"><?= $count_complete; ?> Complete</span>, <span class="result_pending"><?= $count_pending; ?> Pending</span>, <span class="result_partially_completed"><?= $count_partially_complete; ?> Partially Completed</span>, <span class="result_incomplete"><?= $count_incomplete; ?> Incomplete</span></p>
  </div>

  <div class="bpcgi_divider"></div>

  <?php if( !$bpgci_success && !$is_submitted ): ?>
    <form id="bpgci-form" method="post" action="<?= htmlspecialchars( $_SERVER['REQUEST_URI'] ); ?>" >
      <label><input type="radio" name="status" value="complete" /> Complete</label>
      <label><input type="radio" name="status" value="pending" /> Pending</label>
      <label><input type="radio" name="status" value="partially_complete" /> Partially Completed</label>
      <label><input type="radio" name="status" value="incomplete" /> Incomplete</label>
      <input type="submit" value="Submit" />
    </form>
  <?php endif; ?>

  <?php if( $bpgci_success ): ?>
    <div class="bpgci_success_notice">
      <p>Your submission is successful</p>
    </div>
  <?php endif; ?>

  <?php if( $bpgci_error ): ?>
    <div class="bpgci_error_notice">
      <p>There is a error occured. Please contact with admin.</p>
    </div>
  <?php endif; ?>

  <?php if( $is_submitted ): ?>
    <div class="bpgci_submit_status">
      <p>You have submitted your status today.</p>
    </div>
  <?php endif; ?>

</div>

<?php endif;?>



<?php get_footer(); ?>

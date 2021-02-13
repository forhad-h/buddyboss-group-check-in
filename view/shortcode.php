<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

require_once( 'store_data.php' );

if( ! function_exists('BPGCI_remote_get') ) {

  function BPGCI_remote_get( $endpoint ) {
    $request = new WP_Rest_Request( 'GET', $endpoint );
    $response = rest_do_request( $request )->get_data();
    return $response;
  }

}

if( ! function_exists('BPGCI_template') ) {

  function BPGCI_template() {

    // get group info
    $group_id = $_GET ? $_GET['groupid'] ? $_GET['groupid'] : null : null;
    $is_group_check_in_enabled = absint(bp_get_option( 'bpg-enable-check-in', 0 ));

    $group = BPGCI_remote_get( '/buddyboss/v1/groups/1' );

    // get members info
    $memebers = BPGCI_remote_get( '/buddyboss/v1/groups/1/members' );

    // get current user info
    $current_user = wp_get_current_user();

    // store data
    global $bpgci_success;
    global $bpgci_error;

    if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
      global $wpdb;


      $data = array(
        'group_id' => $group['id'],
        'user_id' => $current_user->ID,
        'date' => date('Y-m-d'),
        'status' => $_POST['status']
      );

      require_once( 'store_data.php' );

      BPGCI_store_data( $wpdb, $data );
    }

    ob_start();
?>
    <?php if ( ! $group_id ): ?>
      <p class="bpgci_notice"><strong>Not found!</strong> Group not exists.</p>
    <?php endif; ?>

    <?php if ( ! $group['is_member'] ): ?>
      <p class="bpgci_notice"><strong>Access forbidden!</strong> You are not allowed to see contents.</p>
    <?php endif; ?>

    <?php if ( ! $is_group_check_in_enabled ): ?>
      <p class="bpgci_notice"><strong>Not enabled!</strong> Check-in option is not enabled for group - <a href="<?= esc_url( bp_get_admin_url( 'admin.php?page=bp-settings&tab=bp-groups' ) )?>" target="_new"> <?= __( 'Enable', 'bp-group-check-in' ); ?></a></p>
    <?php endif; ?>


    <div id="group-check-in-wrapper">
      <div class="group_info">
        <h2><?= $group['name']; ?></h2>
        <p><?= $group['description']['rendered']; ?></p>
      </div>

      <div class="total_result">
        <p><span class="result_complete">3 Complete</span>, <span class="result_pending">5 Pending</span>, <span class="result_incomplete">4 Incomplete</span>, <span class="result_partially_completed">7 Partially Completed</span></p>
      </div>

      <?php if( !$bpgci_success ): ?>
        <form method="post" action="<?= htmlspecialchars( $_SERVER['REQUEST_URI'] ); ?>" >
          <label><input type="radio" name="status" value="complete" /> Complete</label>
          <label><input type="radio" name="status" value="pending" /> Pending</label>
          <label><input type="radio" name="status" value="incomplete" /> Incomplete</label>
          <label><input type="radio" name="status" value="partially_complete" /> Partially Completed</label>
          <input type="submit" value="Submit" />
        </form>
      <?php endif; ?>

      <?php if( $bpgci_success ): ?>
        <div class="bpgc_success_notice">
          <p>Your submission is successful</p>
        </div>
      <?php endif; ?>

      <?php if( $bpgci_error ): ?>
        <div class="bpgc_error_notice">
          <p>There is a error occured. Please contact with admin.</p>
        </div>
      <?php endif; ?>

    </div>

<?php

    return ob_get_clean();

  }

  add_shortcode( 'bpgci_template', 'BPGCI_template' );

}

<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists('BPGCI_remote_get') ) {

  function BPGCI_remote_get( $endpoint ) {
    $request = new WP_Rest_Request( 'GET', $endpoint );
    $response = rest_do_request( $request )->get_data();
    return $response;
  }

}

if( ! function_exists('BPGCI_template') ) {

  function BPGCI_template() {
    ob_start();

    $group_id = $_GET ? $_GET['groupid'] ? $_GET['groupid'] : null : null;
    $is_group_check_in_enabled = absint(bp_get_option( 'bpg-enable-check-in', 0 ));

    $group = BPGCI_remote_get( '/buddyboss/v1/groups/1' );
    $memebers = BPGCI_remote_get( '/buddyboss/v1/groups/1/members' );

    $user = wp_get_current_user();



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


    <h2>Shortcode Working...</h2>

<?php

    return ob_get_clean();

  }

  add_shortcode( 'bpgci_template', 'BPGCI_template' );

}

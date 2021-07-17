<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * adds the profile user nav link
 */
if( ! function_exists( 'BPGCI_member_check_in_subnav' ) ) {
  function BPGCI_member_check_in_subnav() {

  	$args = array(
  		'name' => __( 'Check-in Status', 'buddypress' ),
  		'slug' => 'check-in-status',
  		'default_subnav_slug' => 'check-in-status',
  		'position' => 100,
  		'screen_function' => 'BPGCI_member_check_in_subnav_screen',
  		'item_css_id' => 'check_in_status',
  	);

  	bp_core_new_nav_item( $args );
  }

  $is_group_check_in_enabled = absint(bp_get_option( 'bpg-enable-check-in', 0 ));

  if( $is_group_check_in_enabled ) {
    add_action( 'bp_setup_nav', 'BPGCI_member_check_in_subnav' );
  }

}

if( ! function_exists( 'BPGCI_member_check_in_subnav_screen' ) ) {
  function BPGCI_member_check_in_subnav_screen() {
  	add_action( 'bp_template_title',  'BPGCI_member_check_in_subnav_screen_title' );
  	add_action( 'bp_template_content',  'BPGCI_member_check_in_subnav_screen_content' );
  	bp_core_load_template( apply_filters( 'bp_core_template_plugin',  'members/single/plugins' ) );
  }
}

if( ! function_exists( 'BPGCI_member_check_in_subnav_screen_title' ) ) {
  function BPGCI_member_check_in_subnav_screen_title() {
  	echo sprintf( '<h4>%s</h4>', __( 'Check-in Status', 'bp-group-check-in' ) );
  }
}

if( ! function_exists( 'BPGCI_member_check_in_subnav_screen_content' ) ) {
  function BPGCI_member_check_in_subnav_screen_content() {

      global $bp;
      $member_id = $bp->displayed_user->id;

      $group_args = array(
  			'user_id'  => $member_id
  		);

      if ( bp_has_groups( $group_args ) ) :

        require_once( 'check_in_status.php' );

        while ( bp_groups() ) :
					bp_the_group();
          $group_id = bp_get_group_id();
          echo BPGCI_check_in_status( $group_id, $member_id );
        endwhile;

      endif;

  }
}

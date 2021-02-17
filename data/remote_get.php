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

if( ! function_exists('BPGCI_get_group') ) {

  function BPGCI_get_group( $group_id ) {
    $group = BPGCI_remote_get( '/buddyboss/v1/groups/' . $group_id );
    return $group;
  }

}

if( ! function_exists('BPGCI_get_members') ) {

  function BPGCI_get_members( $group_id ) {
    $memebers = BPGCI_remote_get( '/buddyboss/v1/groups/' . $group_id . '/members' );
    return $memebers;
  }

}

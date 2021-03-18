<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'BPGCI_delete_data_by_group_id' ) ) {

  function BPGCI_delete_data_by_group_id( $wpdb, $group_id ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    return $wpdb->delete( $table_name, array( 'group_id' => $group_id ), array( '%d' ) );

  }

}

if( ! function_exists( 'BPGCI_delete_data_by_group_ids' ) ) {

  function BPGCI_delete_data_by_group_id( $wpdb, $group_ids ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    foreach($group_ids as $group_id) {
      $wpdb->delete( $table_name, array( 'group_id' => $group_id ), array( '%d' ) );
    }

  }

}

<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'BPGCI_reset_data_by_group_id' ) ) {

  function BPGCI_reset_data_by_group_id( $wpdb, $group_id ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    return $wpdb->delete( $table_name, array( 'group_id' => $group_id ), array( '%d' ) );

  }

}

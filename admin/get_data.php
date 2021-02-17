<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'BPGCI_get_all_data' ) ) {

  function BPGCI_get_all_data( $wpdb ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT * FROM {$table_name}";

    $result = $wpdb->get_results( $sql );

    return $result;

  }

}

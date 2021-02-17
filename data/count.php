<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'BPGCI_count_data' ) ) {

  function BPGCI_count_data( $wpdb, $group_id, $status ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT COUNT(*) FROM {$table_name} WHERE group_id='{$group_id}' AND status='{$status}'";

    $result = $wpdb->get_var( $sql );

    return $result;

  }

}

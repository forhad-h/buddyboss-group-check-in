<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'BPGCI_is_submitted' ) ) {

  function BPGCI_is_submitted( $wpdb, $group_id, $user_id, $current_date ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT COUNT(*) FROM {$table_name} WHERE group_id='{$group_id}' AND user_id='{$user_id}' AND date='{$current_date}'";

    $result = $wpdb->get_var( $sql );

    if( $result ) {
      return true;
    }

    return false;

  }

}

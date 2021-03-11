<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'BPGCI_count_data_by_group_and_status' ) ) {

  function BPGCI_count_data_by_group_and_status( $wpdb, $group_id, $status ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT COUNT(*) FROM {$table_name} WHERE group_id='{$group_id}' AND status='{$status}'";

    $result = $wpdb->get_var( $sql );

    return $result;

  }

}


if( ! function_exists( 'BPGCI_count_data_by_group_status_and_date' ) ) {

  function BPGCI_count_data_by_group_status_and_date( $wpdb, $group_id, $status, $date ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT COUNT(*) FROM {$table_name} WHERE group_id='{$group_id}' AND status='{$status}' AND date='{$date}'";

    $result = $wpdb->get_var( $sql );

    return $result;

  }

}


if( ! function_exists( 'BPGCI_count_data_by_user_group_status_and_date' ) ) {

  function BPGCI_count_data_by_user_group_status_and_date( $wpdb, $user_id, $group_id, $status, $date ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT COUNT(*) FROM {$table_name} WHERE user_id='{$user_id}' AND group_id='{$group_id}' AND status='{$status}' AND date='{$date}'";

    $result = $wpdb->get_var( $sql );

    return $result;

  }

}


if( ! function_exists( 'BPGCI_count_data_by_group_user_and_date_range' ) ) {

  function BPGCI_count_data_by_group_user_and_date_range( $wpdb, $group_id, $user_id, $from_date = '', $to_date = '', $status ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT COUNT(*) FROM {$table_name} WHERE group_id='{$group_id}' AND user_id='{$user_id}' AND date BETWEEN '{$from_date}' AND '$to_date' AND status='{$status}'";

    $result = $wpdb->get_var( $sql );

    return $result;

  }

}

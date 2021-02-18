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

if( ! function_exists( 'BPGCI_get_group_data_by_id' ) ) {

  function BPGCI_get_group_data_by_id( $wpdb, $group_id ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT * FROM {$table_name} WHERE group_id='{$group_id}'";

    $result = $wpdb->get_results( $sql );

    return $result;

  }

}

if( ! function_exists( 'BPGCI_get_group_data_by_group_and_date' ) ) {

  function BPGCI_get_group_data_by_group_and_date( $wpdb, $group_id, $date ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT * FROM {$table_name} WHERE group_id='{$group_id}' AND date='{$date}'";

    $result = $wpdb->get_results( $sql );

    return $result;

  }

}

if( ! function_exists( 'BPGCI_get_group_data_by_group_and_date_range' ) ) {

  function BPGCI_get_group_data_by_group_and_date_range( $wpdb, $group_id,  $from_date, $to_date ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT * FROM {$table_name} WHERE group_id='{$group_id}' AND date BETWEEN '{$from_date}' AND '$to_date'";

    $result = $wpdb->get_results( $sql );

    return $result;

  }

}

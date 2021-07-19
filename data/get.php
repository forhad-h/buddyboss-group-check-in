<?php

if( ! function_exists( 'BPGCI_has_group_check_in' ) ) {

  function BPGCI_has_group_check_in( $wpdb, $group_id ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT * FROM {$table_name} WHERE group_id='{$group_id}'";

    $result = $wpdb->get_var( $sql );

    return $result;

  }

}

if( ! function_exists( 'BPGCI_group_by_slug' ) ) {

  function BPGCI_group_by_slug( $wpdb, $slug ) {

    try {
      $table_name = $wpdb->prefix . 'bp_groups';

      $query = "SELECT * FROM {$table_name} WHERE slug='{$slug}'";

      $group = $wpdb->get_row( $query );

      return $group;

    }catch( Exception $e ) {
      return new WP_Error('not_found', "Group not found with the slug!", $e);
    }

  }

}

if( ! function_exists('BPGCI_is_group_member') ) {
  function BPGCI_is_group_member($wpdb, $group_id, $user_id) {
    try{
      $table_name = $wpdb->prefix . 'bp_groups_members';

      $query = "SELECT * FROM {$table_name} WHERE group_id={$group_id} AND user_id={$user_id}";

      $result = $wpdb->get_var( $query );

      return $result;

    }catch( Exception $e ) {
      return new WP_Error('not_found', "Group not found with the slug!", $e);
    }
  }
}

?>

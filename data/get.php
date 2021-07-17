<?php

if( ! function_exists( 'BPGCI_has_group_check_in' ) ) {

  function BPGCI_has_group_check_in( $wpdb, $group_id ) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    $sql = "SELECT * FROM {$table_name} WHERE group_id='{$group_id}'";

    $result = $wpdb->get_var( $sql );

    return $result;

  }

}

?>

<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists('BPGCI_store_data') ) {

  function BPGCI_store_data($wpdb, $data) {

    global $bpgci_success;
    global $bpgci_error;

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];

    try {
      $insert = $wpdb->insert( $table_name, [
        'group_id' => trim( esc_sql( $data['group_id'] ) ),
        'user_id' => trim( esc_sql( $data['user_id'] ) ),
        'date' => trim( esc_sql( $data['date'] ) ),
        'status' => trim( esc_sql( $data['status'] ) )
      ] );
      if($insert) {
        $bpgci_success = true;
      }
    }catch(Exception $e) {
      $bpgci_error = true;
      var_dump($e);
    }
  }
}

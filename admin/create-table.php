<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists('BPGCI_create_table') ) {
  function BPGCI_create_table($wpdb) {

    $table_name = BPGCI_ADDON_PLUGIN['table_name'];
    $charset_collate = $wpdb->get_charset_collate();

    $sql = <<<QUERY
             CREATE TABLE IF NOT EXISTS {$table_name} (
               id mediumint(9) NOT NULL AUTO_INCREMENT,
               group_id mediumint(9) NOT NULL,
               user_id mediumint(9) NOT NULL,
               date date NOT NULL,
               status varchar(20),
               PRIMARY KEY (id)
             ) {$charset_collate}
QUERY;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbdelta( $sql );

    add_option( BPGCI_ADDON_PLUGIN[ 'name' ] . '_version', BPGCI_ADDON_PLUGIN[ 'version' ] );

  }
}

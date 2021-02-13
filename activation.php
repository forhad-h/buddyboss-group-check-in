<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists('BPGCI_activation_events') ) {

  function BPGCI_activation_events() {

      if(!current_user_can('activate_plugin')) return;

      global $wpdb;

      // create table
      require_once( 'admin/create-table.php' );
      BPGCI_create_table( $wpdb );

      // create group check-in view page
      $sql = sprintf("SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name='%s'", 'group-check-in');

      if( null === $wpdb->get_row( $sql, "ARRAY_A" ) ) {

        $user = wp_get_current_user();

        $page_option = array(
          'post_title' => __('Group Check-in', 'bp-group-check-in'),
          'post_name' => 'group-check-in',
          'post_status' => 'publish',
          'post_author' => $user->ID,
          'post_type' => 'page',
          'post_content' => sprintf('[%s]', 'bpgci_template')
        );

        wp_insert_post($page_option);

      }

  }

  register_activation_hook( BPGCI_ADDON_PLUGIN_FILE, 'BPGCI_activation_events' );

}

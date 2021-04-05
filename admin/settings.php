<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists('BPGCI_create_menu_page') ) {

  function BPGCI_create_menu_page() {

    add_menu_page(
      __( 'Buddy Up', 'bp-group-check-in' ),
      __( 'Buddy Up', 'bp-group-check-in' ),
      'publish_pages',
      BPGCI_MENU_SLUG,
      '',
      'dashicons-yes-alt',
      3
    );
  }

  add_action( 'admin_menu', 'BPGCI_create_menu_page', 10 );


}

if ( ! function_exists( 'BPGCI_admin_setting_groups_register_fields' ) ) {
   function BPGCI_admin_setting_groups_register_fields( $setting ) {
     $setting->add_section( 'bb_group_check-in', __( 'Group Check-in', '' ) );

     $args          = array();
     $setting->add_field( 'bpg-enable-check-in', __( 'Check-in', '' ), 'BPGCI_admin_general_setting_callback_my_addon', 'intval', $args );
   }

 add_action( 'bp_admin_setting_groups_register_fields', 'BPGCI_admin_setting_groups_register_fields' );
}

if ( ! function_exists( 'BPGCI_admin_general_setting_callback_my_addon' ) ) {
 function BPGCI_admin_general_setting_callback_my_addon() {
   ?>
       <input id="bpg-enable-check-in" name="bpg-enable-check-in" type="checkbox"
              value="1" <?php checked( BPGCI_enable_check_in() ); ?> />
       <label for="bpg-enable-check-in"><?php _e( 'Enable Check-in Option', '' ); ?></label>
   <?php
 }
}

if ( ! function_exists( 'BPGCI_enable_check_in' ) ) {
 function BPGCI_enable_check_in( $default = false ) {
   return (bool) apply_filters( 'BPGCI_enable_check_in', (bool) bp_get_option( 'bpg-enable-check-in', $default ) );
 }
}

<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( ! function_exists( 'BPGCI_group_check_in_subnav' ) ) {

	function BPGCI_group_check_in_subnav(){
		 global $bp;
		 /* Add some group subnav items */
		 $user_access = false;
		 $group_link = '';
		 if( bp_is_active('groups') && !empty($bp->groups->current_group) ){
			 $group_link = $bp->root_domain . '/' . bp_get_groups_root_slug() . '/' . $bp->groups->current_group->slug . '/';
			 $user_access = $bp->groups->current_group->user_has_access;
			 bp_core_new_subnav_item( array(
				 'name' => __( 'Check-in', 'bp-group-check-in' ),
				 'slug' => 'check-in',
				 'parent_url' => $group_link,
				 'parent_slug' => $bp->groups->current_group->slug,
				 'screen_function' => 'BPGCI_group_check_in_subnav_screen',
				 'position' => 1100,
				 'user_has_access' => $user_access,
				 'item_css_id' => 'check_in'
			 ));
		 }
	 }

   $is_group_check_in_enabled = absint(bp_get_option( 'bpg-enable-check-in', 0 ));

   if( $is_group_check_in_enabled ) {
	   add_action( 'bp_init', 'BPGCI_group_check_in_subnav' );
   }

 }

if( ! function_exists( 'BPGCI_group_check_in_subnav_screen' ) ) {

  function BPGCI_group_check_in_subnav_screen() {
 	 add_action('bp_template_content', 'BPGCI_group_check_in_subnav_screen_content');

 	 $templates = array('groups/single/plugins.php', 'plugin-template.php');

 	 if (strstr(locate_template($templates), 'groups/single/plugins.php')) {
 		 bp_core_load_template(apply_filters('bp_core_template_plugin', 'groups/single/plugins'));
 	 } else {
 		 bp_core_load_template(apply_filters('bp_core_template_plugin', 'plugin-template'));
 	 }

  }

}

if( ! function_exists( 'BPGCI_group_check_in_subnav_screen_content' ) ) {
 function BPGCI_group_check_in_subnav_screen_content() {
   global $bp;
   require_once( 'check_in_form.php' );
   echo BPGCI_check_in_form( $bp->groups->current_group->id );
 }
}

<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

 /*
 To Change group slug
 add_filter( 'bp_get_groups_directory_permalink', function($value) {
 	$current_types = bgci_bp_habits_get_group_type( $_GET['gid'] );
 	return site_url().'/test/';
 });
 */

 // add_action( 'bgci_bp_habits_admin_load', function( $doaction ) {
 // 	if( $doaction === 'edit' ) {
 //
 // 	}
 // } );


 /* Show contents in single group */

 /*function groups_screen_group_members_all_members2() {
 	//
 	// if ( 'all-members' != bp_get_group_current_members_tab() ) {
 	// 	return false;
 	// }


 	$bp = buddypress();

 	do_action( 'groups_screen_group_members_all_members2', $bp->groups->current_group->id );

 	bp_core_load_template( apply_filters( 'groups_template_group_members', 'groups/single/home' ) );
 }
 add_action( 'bp_screens', 'groups_screen_group_members_all_members2' );*/

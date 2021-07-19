<?php

if( ! function_exists('BPGCI_group_post_box') ) {
  function BPGCI_group_post_box() {

     $doaction = bp_admin_list_table_current_bulk_action();

     if( 'edit' == $doaction && ! empty( $_GET['gid'] ) && absint(bp_get_option( 'bpg-enable-check-in', 0 )) ) {
         add_meta_box(
           'bpgci_check_in',
           __( 'Check-In', 'bp-group-check-in' ),
           'BPGCI_group_post_box_callback',
           get_current_screen()->id,
           'side',
           'core'
         );
     }

  }

  add_action( 'bp_groups_admin_load', 'BPGCI_group_post_box' );
}

if( ! function_exists('BPGCI_group_post_box_callback') ) {
  function BPGCI_group_post_box_callback() {
	$group = groups_get_group( (int) $_GET['gid'] );

  $checkin_link = trailingslashit( bp_get_root_domain() ) . 'check-in/' . bp_get_group_slug( $group );

?>
  <h3>Check-in link: </h3>
  <a href="<?php echo $checkin_link;?>" target="_new">
    <?php echo $checkin_link; ?>
  </a>
<?php
   }
}

<?php
/*
Plugin Name: Test List Table Example
*/

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BPGCI_List_Table extends WP_List_Table {

  private $list_type;
  private $list_data;

  function __construct( $list_type, $list_data ){
    global $status, $page;

    $this->list_type = $list_type;
    $this->list_data = $list_data;

    parent::__construct( array(
        'singular'  => __( 'group', 'bp-group-check-in' ),     //singular name of the listed records
        'plural'    => __( 'groups', 'bp-group-check-in' ),   //plural name of the listed records
        'ajax'      => false        //does this table support ajax?
    ) );

  }

  function no_items() {
    _e( 'No groups found, dude.' );
  }

  function column_default( $item, $column_name ) {

    if( !empty( $item[ $column_name ] ) ) {
      return $item[ $column_name ];
    }else {
      return '-';
    }
  }

  function get_sortable_columns() {
    $sortable_columns = array(
      'group_name'  => array('group_name',false),
      'complete' => array('complete',false),
      'pending'   => array('pending',false),
      'partially_complete'   => array('partially_complete',false),
      'incomplete'   => array('incomplete',false),
    );
    return $sortable_columns;
  }

  function get_columns(){
    $columns = array(
        'cb'        => '<input type="checkbox" />',
        'group_name' => __( 'Group Title', 'bp-group-check-in' ),
        'complete'    => __( 'Complete', 'bp-group-check-in' ),
        'pending'      => __( 'Pending', 'bp-group-check-in' ),
        'partially_complete'      => __( 'Partially Completed', 'bp-group-check-in' ),
        'incomplete'      => __( 'Incomplete', 'bp-group-check-in' ),
    );
    return $columns;
  }

  function usort_reorder( $a, $b ) {
    // If no sort, default to title
    $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'group_name';
    // If no order, default to asc
    $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
    // Determine sort order
    $result = strcmp( $a[$orderby], $b[$orderby] );
    // Send final sort direction to usort
    return ( $order === 'asc' ) ? $result : -$result;
  }

  function column_group_name($item){
    $actions = array(
        'view'    => sprintf('<a href="?page=%s&action=%s&group_id=%s">View</a>',$_REQUEST['page'],'view',$item['ID']),
        'delete'  => sprintf('<a id="bpgci-delete" href="?page=%s&action=%s&group_id=%s&_wpnonce=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['ID'], wp_create_nonce( 'bpgci_delete_group' ) ),
    );

    return sprintf('%1$s %2$s', $item['group_name'], $this->row_actions($actions) );
  }

  function get_bulk_actions() {
    $actions = array(
      'delete'    => 'Delete'
    );
    return $actions;
  }

  function column_cb($item) {
    return sprintf(
        '<input type="checkbox" name="group[]" value="%s" />', $item['ID']
    );
  }

  function prepare_items() {

    $columns  = $this->get_columns();
    $hidden   = array();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array( $columns, $hidden, $sortable );

    $this->process_action();
    $this->process_bulk_action();

    usort( $this->list_data, array( &$this, 'usort_reorder' ) );

    $per_page = 5;
    $current_page = $this->get_pagenum();
    $total_items = count( $this->list_data );

    $this->set_pagination_args( array(
      'total_items' => $total_items,
      'per_page'    => $per_page
    ) );

    $this->items = $this->list_data;
  }

  public function process_action() {
    if ( 'delete' === $this->current_action() ) {

      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr( $_REQUEST['_wpnonce'] );

      if ( ! wp_verify_nonce( $nonce, 'aubu_delete_user' ) ) {
        die( 'Go get a life, script kiddies!' );
      } else {

      }

    }
  }

} //class

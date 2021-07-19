<?php
/*
Plugin Name: Test List Table Example
*/

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BPGCI_List_Table extends WP_List_Table {

  private $page_type;
  private $list_data;

  function __construct( $page_type, $list_data ){
    global $status, $page;

    $this->page_type = $page_type;
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
    $first_column = [];
    if( $this->page_type === BPGCI_PAGE_TYPE_ALL_GROUPS ) {
      $first_column = array(
        'group_name'  => array('group_name',false),
      );
    }else if( $this->page_type === BPGCI_PAGE_TYPE_SINGLE_GROUP ) {
      $first_column = array(
        'user_name'  => array('user_name',false),
      );
    }

    $other_columns = array(
      'complete' => array('complete',false),
      'pending'   => array('pending',false),
      'partially_complete'   => array('partially_complete',false),
      'incomplete'   => array('incomplete',false),
    );

    $sortable_columns = array_merge( $first_column, $other_columns );
    return $sortable_columns;
  }

  function get_columns(){
    $first_columns = [];

    if( $this->page_type === BPGCI_PAGE_TYPE_ALL_GROUPS ) {
      $first_columns = array(
        'cb'        => '<input type="checkbox" />',
        'group_name' => __( 'Group Title', 'bp-group-check-in' ),
      );
    }else if( $this->page_type === BPGCI_PAGE_TYPE_SINGLE_GROUP ) {
      $first_columns = array(
        'cb'        => '<input type="checkbox" />',
        'user_name' => __( 'Username', 'bp-group-check-in' ),
      );
    }

    $other_columns = array(
        'complete'    => __( 'Complete', 'bp-group-check-in' ),
        'pending'      => __( 'Pending', 'bp-group-check-in' ),
        'partially_complete'      => __( 'Partially Completed', 'bp-group-check-in' ),
        'incomplete'      => __( 'Incomplete', 'bp-group-check-in' ),
    );

    $columns = array_merge( $first_columns, $other_columns );

    return $columns;

  }

  function usort_reorder( $a, $b ) {

    // If no sort, default to title
    $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : $this->order_by();
    // If no order, default to asc
    $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
    // Determine sort order
    $result = strcmp( $a[$orderby], $b[$orderby] );
    // Send final sort direction to usort
    return ( $order === 'asc' ) ? $result : -$result;
  }

  function order_by() {
    $order_by = [];

    if( $this->page_type === BPGCI_PAGE_TYPE_ALL_GROUPS ) {
      $order_by = 'group_name';
    }else if( $this->page_type === BPGCI_PAGE_TYPE_SINGLE_GROUP ) {
      $order_by = 'user_name';
    }

    return $order_by;
  }

  function column_group_name($item){
    $actions = array(
        'view'    => sprintf('<a href="?page=%s&action=%s&page_type=%s&group_id=%s">View</a>',$_REQUEST['page'],'view', BPGCI_PAGE_TYPE_SINGLE_GROUP, $item['ID'] ),
        'delete'  => sprintf('<a class="bpgci_reset" href="?page=%s&action=%s&group_id=%s&_wpnonce=%s">Reset</a>', $_REQUEST['page'], 'delete', $item['ID'], wp_create_nonce( 'bpgci_reset_group' ) ),
    );

    return sprintf('%1$s %2$s', $item['group_name'], $this->row_actions($actions) );
  }

  function get_bulk_actions() {
    $actions = array();
    if( $this->page_type === BPGCI_PAGE_TYPE_ALL_GROUPS ) {
      $actions = array(
        'bulk-delete'    => sprintf('%s <input type="hidden" name="_wpnonce" value="%s" />', __('Reset', 'aubu'), wp_create_nonce( 'bpgci_bulk_reset_groups' ) ),
      );
    }
    return $actions;
  }

  function column_cb($item) {
    return sprintf(
        '<input type="checkbox" class="bpgci_bulk_checkbox" name="group_id[]" value="%s" />', $item['ID']
    );
  }

  function prepare_items() {

    $columns  = $this->get_columns();
    $hidden   = array();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array( $columns, $hidden, $sortable );

    $this->process_action();

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

    require_once( BPGCI_PATH . 'data/reset.php');

    global $wpdb;


    if('bulk-delete' === $this->current_action() ) {

      var_dump($_REQUEST['_wpnonce']);

      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr( $_REQUEST['_wpnonce'] );



      if ( ! wp_verify_nonce( $nonce, 'bpgci_bulk_reset_groups' ) ) {
        die( 'Go get a life, script kiddies!' );
      } else {
        foreach( $_POST['group_id'] as $group_id ) {
          BPGCI_reset_data_by_group_id( $wpdb, $group_id );
          header( 'Location: ?page=buddy-up-report' );
        }
      }

    }elseif ( 'delete' === $this->current_action() ) {

      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr( $_REQUEST['_wpnonce'] );

      if ( ! wp_verify_nonce( $nonce, 'bpgci_reset_group' ) ) {
        die( 'Go get a life, script kiddies!' );
      } else {
        BPGCI_reset_data_by_group_id( $wpdb, $_GET['group_id'] );
        header( 'Location: ?page=buddy-up-report' );
      }

    }
  }

} //class

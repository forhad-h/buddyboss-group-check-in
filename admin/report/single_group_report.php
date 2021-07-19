<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

//TODO: Implement a button to clear report

if( ! function_exists('BPGCI_single_group_report') ) {
  function BPGCI_single_group_report( $wpdb ) {

    $group_id = !empty($_GET['group_id']) ? $_GET['group_id'] : null;
    $current_date =  date('Y-m-d');
    $group_data_by_date = [];
    $report_for = $current_date;

    require_once( 'get_data.php' );
    $group_data_by_id = BPGCI_get_group_data_by_id( $wpdb, $group_id );

    if( !empty( $_GET['single_date'] ) ) {

      $group_data_by_date = BPGCI_get_group_data_by_group_and_date( $wpdb, $group_id, $_GET['single_date'] );

    }elseif( !empty( $_GET['from_date'] ) && !empty( $_GET['to_date'] ) ) {

      $group_data_by_date = BPGCI_get_group_data_by_group_and_date_range( $wpdb, $group_id, $_GET['from_date'], $_GET['to_date'] );

      $user_ids = array_map( function( $item ) {
        return $item->user_id;
      }, $group_data_by_date);

      $user_ids = array_unique( $user_ids );

      $user_data_by_date_range = array_map( function( $user_id ) {

        global $wpdb;
        $group_id = !empty($_GET['group_id']) ? $_GET['group_id'] : null;

        $user = get_user_by( 'id', $user_id );

        require_once( BPGCI_PATH . 'data/count.php' );
        $total_complete = BPGCI_count_data_by_group_user_and_date_range( $wpdb, $group_id, $user_id, $_GET['from_date'], $_GET['to_date'], 'complete' );
        $total_pending = BPGCI_count_data_by_group_user_and_date_range( $wpdb, $group_id, $user_id, $_GET['from_date'], $_GET['to_date'], 'pending' );
        $total_partially_complete = BPGCI_count_data_by_group_user_and_date_range( $wpdb, $group_id, $user_id, $_GET['from_date'], $_GET['to_date'], 'partially_complete' );
        $total_incomplete = BPGCI_count_data_by_group_user_and_date_range( $wpdb, $group_id, $user_id, $_GET['from_date'], $_GET['to_date'], 'incomplete' );

        return array(
          'user_id' => $user_id,
          'user_name' => $user->user_login,
          'complete' => $total_complete,
          'pending' => $total_pending,
          'partially_complete' => $total_partially_complete,
          'incomplete' => $total_incomplete
        );

      }, $user_ids );


    }else {
      if( !empty($current_date) ) {
        $group_data_by_date = BPGCI_get_group_data_by_group_and_date( $wpdb, $group_id, $current_date );
      }
    }

    $dates = array_map( function($item) {
      return $item->date;
    }, $group_data_by_id);

    $dates = array_unique( $dates );

    usort( $dates, function( $a, $b ) {
      return strtotime( $a ) - strtotime( $b );
    });


    if(!empty( $_GET['single_date'] )) {
      $report_for = "( {$_GET['single_date']} )";
    }elseif( !empty( $_GET['from_date'] ) && !empty( $_GET['to_date'] ) ) {
      $report_for = "( <span>From </span> {$_GET['from_date']} <span>to </span> {$_GET['to_date']} )";
    }

?>

<?php
  $list_data = array();


  require_once( BPGCI_PATH . 'data/remote_get.php' );
  $group = BPGCI_get_group($group_id);
  $group_name = $group['name'];

  if( !empty($user_data_by_date_range) ) {
      foreach( $user_data_by_date_range as $data ) {

        $user_name = $data['user_name'];
        $count_complete = $data['complete'] ? "<strong>{$data['complete']}</strong>" : '-';
        $count_pending = $data['pending'] ? "<strong>{$data['pending']}</strong>" : '-';
        $count_partially_complete = $data['partially_complete'] ? "<strong>{$data['partially_complete']}</strong>" : '-';
        $count_incomplete = $data['incomplete'] ? "<strong>{$data['incomplete']}</strong>" : '-';

        $each_row = array(
          'ID' => $group_id,
          'user_name' => $user_name,
          'complete' => $count_complete,
          'pending' => $count_pending,
          'partially_complete' => $count_partially_complete,
          'incomplete' => $count_incomplete,
        );

        array_push( $list_data, $each_row );

      }
  }else {
      foreach( $group_data_by_date as $data ) {

        $user = get_user_by('id', $data->user_id);

        $user_name = $user->user_login;
        $count_complete = $data->status == 'complete' ? "<strong>1</strong>" : '-';
        $count_pending = $data->status == 'pending' ? "<strong>1</strong>" : '-';
        $count_incomplete = $data->status == 'partially_complete' ? "<strong>1</strong>" : '-';
        $count_partially_complete = $data->status == 'incomplete' ? "<strong>1</strong>" : '-';

        $each_row = array(
          'ID' => $data->id,
          'user_name' => $user_name,
          'complete' => $count_complete,
          'pending' => $count_pending,
          'partially_complete' => $count_partially_complete,
          'incomplete' => $count_incomplete,
        );

        array_push( $list_data, $each_row );

      }
    }
?>

<div class="bpgci_single_group_report_table wrap">
  <h1 class="wp-heading-inline"><?= __( "Buddy Up Report of - {$group_name} <span class='sub'>{$report_for}<span>", 'bp-group-check-in' ); ?></h1>
  <div class="query_forms">
    <form class="single_date_form" method="get" action="<?= $_SERVER['REQUEST_URI']; ?>" >
      <input type="hidden" name="page" value="<?= $_GET['page']; ?>" />
      <input type="hidden" name="page_type" value="<?= BPGCI_PAGE_TYPE_SINGLE_GROUP ?>" />
      <input type="hidden" name="group_id" value="<?= $_GET['group_id']; ?>" />
      <select name="single_date">
        <option value=''>Select Date</option>

        <?php foreach($dates as $date): ?>
          <option
            value="<?= $date; ?>"
            <?= !empty($_GET['single_date']) && ($date ==  $_GET['single_date']) ? 'selected' : ''; ?> >
              <?= $date; ?></option>
        <?php endforeach;?>

      </select>
      <input type="submit" class="button button-small" value="Get Report" />
    </form>
    <span class="query_form_separator">or</span>
    <form class="multiple_dates_form" method="get" action="<?= $_SERVER['REQUEST_URI']; ?>" >
      <input type="hidden" name="page" value="<?= $_GET['page']; ?>" />
      <input type="hidden" name="page_type" value="<?= BPGCI_PAGE_TYPE_SINGLE_GROUP ?>" />
      <input type="hidden" name="group_id" value="<?= $_GET['group_id']; ?>" />
      <label> From
        <select name="from_date">
          <option value=''>Select Date</option>

          <?php foreach($dates as $date): ?>
            <option
            value="<?= $date; ?>"
            <?= !empty($_GET['from_date']) && ($date ==  $_GET['from_date']) ? 'selected' : ''; ?>>
              <?= $date; ?></option>
          <?php endforeach;?>

        </select>
      </label>

      <label> To
        <select name="to_date">
          <option value=''>Select Date</option>

          <?php foreach($dates as $date): ?>
            <option
            value="<?= $date; ?>"
            <?= !empty($_GET['to_date']) && ($date ==  $_GET['to_date']) ? 'selected' : ''; ?>>
              <?= $date; ?></option>
          <?php endforeach;?>

        </select>
      </label>

      <input type="submit" class="button button-small" value="Get Report" />
    </form>
  </div>

  <form method="post">
    <input type="hidden" name="page" value="groups_list_table">

<?php
  // require- BPGCI_List_Table
  require_once( BPGCI_PATH . 'admin/list_table.php' );
  $BPGCI_groups_data_table = new BPGCI_List_Table( BPGCI_PAGE_TYPE_SINGLE_GROUP, $list_data );
  $BPGCI_groups_data_table->prepare_items();
  $BPGCI_groups_data_table->display();
?>

  </form>
</div>

<?php
  }
}

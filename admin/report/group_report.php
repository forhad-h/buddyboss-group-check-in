<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

//TODO: Implement a button to clear report

if( ! function_exists('BPGCI_group_report') ) :
  function BPGCI_group_report( $wpdb ) {

    $group_id = isset($_GET['group_id']) ? $_GET['group_id'] : null;
    $current_date =  date('Y-m-d');
    $group_data_by_date = [];
    $report_for = $current_date;

    require_once( 'get_data.php' );
    $group_data_by_id = BPGCI_get_group_data_by_id( $wpdb, $group_id );

    if( isset( $_GET['single_date'] ) ) {

      $group_data_by_date = BPGCI_get_group_data_by_group_and_date( $wpdb, $group_id, $_GET['single_date'] );

    }elseif( isset( $_GET['from_date'] ) && isset( $_GET['to_date'] ) ) {

      $group_data_by_date = BPGCI_get_group_data_by_group_and_date_range( $wpdb, $group_id, $_GET['from_date'], $_GET['to_date'] );

      $user_ids = array_map( function( $item ) {
        return $item->user_id;
      }, $group_data_by_date);

      $user_ids = array_unique( $user_ids );

      $user_data_by_date_range = array_map( function( $user_id ) {

        global $wpdb;
        $group_id = isset($_GET['group_id']) ? $_GET['group_id'] : null;

        $user = get_user_by( 'id', $user_id );

        require_once( BPGCI_ADDON_PLUGIN_PATH . 'data/count.php' );
        $total_complete = BPGCI_count_data_by_group_user_and_date_range( $wpdb, $group_id, $user_id, $_GET['from_date'], $_GET['to_date'], 'complete' );
        $total_pending = BPGCI_count_data_by_group_user_and_date_range( $wpdb, $group_id, $user_id, $_GET['from_date'], $_GET['to_date'], 'pending' );
        $total_partially_complete = BPGCI_count_data_by_group_user_and_date_range( $wpdb, $group_id, $user_id, $_GET['from_date'], $_GET['to_date'], 'partially_complete' );
        $total_incomplete = BPGCI_count_data_by_group_user_and_date_range( $wpdb, $group_id, $user_id, $_GET['from_date'], $_GET['to_date'], 'incomplete' );

        return array(
          'user_name' => $user->user_login,
          'complete' => $total_complete,
          'pending' => $total_pending,
          'partially_complete' => $total_partially_complete,
          'incomplete' => $total_incomplete
        );

      }, $user_ids );


    }else {
      $group_data_by_date = BPGCI_get_group_data_by_group_and_date( $wpdb, $group_id, $current_date );
    }

    $dates = array_map( function($item) {
      return $item->date;
    }, $group_data_by_id);

    $dates = array_unique( $dates );

    usort( $dates, function( $a, $b ) {
      return strtotime( $a ) - strtotime( $b );
    });


    if(isset( $_GET['single_date'] )) {
      $report_for = $_GET['single_date'];
    }elseif( isset( $_GET['from_date'] ) && isset( $_GET['to_date'] ) ) {
      $report_for = "<span>From:</span> {$_GET['from_date']} <span>to</span> {$_GET['to_date']}";
    }

?>
<div class="bpgci_single_group_report">

  <div class="query_forms">
    <form class="single_date_form" method="get" action="<?= $_SERVER['REQUEST_URI']; ?>" >
      <input type="hidden" name="page" value="<?= $_GET['page']; ?>" />
      <input type="hidden" name="group_id" value="<?= $_GET['group_id']; ?>" />
      <select name="single_date">
        <option>Select Date</option>

        <?php foreach($dates as $date): ?>
          <option
            value="<?= $date; ?>"
            <?= isset($_GET['single_date']) && ($date ==  $_GET['single_date']) ? 'selected' : ''; ?> >
              <?= $date; ?></option>
        <?php endforeach;?>

      </select>
      <input type="submit" class="button button-small" value="Get Report" />
    </form>
    <span class="query_form_separator">or</span>
    <form class="multiple_dates_form" method="get" >
      <input type="hidden" name="page" value="<?= $_GET['page']; ?>" />
      <input type="hidden" name="group_id" value="<?= $_GET['group_id']; ?>" />
      <label> From
        <select name="from_date">
          <option>Select Date</option>

          <?php foreach($dates as $date): ?>
            <option
            value="<?= $date; ?>"
            <?= isset($_GET['from_date']) && ($date ==  $_GET['from_date']) ? 'selected' : ''; ?>>
              <?= $date; ?></option>
          <?php endforeach;?>

        </select>
      </label>

      <label> To
        <select name="to_date">
          <option>Select Date</option>

          <?php foreach($dates as $date): ?>
            <option
            value="<?= $date; ?>"
            <?= isset($_GET['to_date']) && ($date ==  $_GET['to_date']) ? 'selected' : ''; ?>>
              <?= $date; ?></option>
          <?php endforeach;?>

        </select>
      </label>

      <input type="submit" class="button button-small" value="Get Report" />
    </form>
  </div>

  <h2>Report - <?= $report_for; ?></h2>
  <table class="wp-list-table widefat fixed striped table-view-list bpgci_data_table" >
    <thead>
      <tr>
        <th>Username</th>
        <th>Complete</th>
        <th>Pending</th>
        <th>Partially Complete</th>
        <th>Incomplete</th>
      </tr>
    </thead>

    <tbody>

      <?php  if( isset($user_data_by_date_range) ): ?>

        <?php
          foreach( $user_data_by_date_range as $data ):
          ?>
            <tr>
              <td><?= $data['user_name']; ?></td>
              <td><?= $data['complete'] ? "<strong>{$data['complete']}</strong>" : '-'; ?></td>
              <td><?= $data['pending'] ? "<strong>{$data['pending']}</strong>" : '-'; ?></td>
              <td><?= $data['partially_complete'] ? "<strong>{$data['partially_complete']}</strong>" : '-'; ?></td>
              <td><?= $data['incomplete'] ? "<strong>{$data['incomplete']}</strong>" : '-'; ?></td>
            </tr>
        <?php endforeach; ?>

      <?php else:?>

        <?php
          foreach( $group_data_by_date as $data ):
            $user = get_user_by('id', $data->user_id);
          ?>
            <tr>
              <td><?= $user->user_login; ?></td>
              <td><?= $data->status == 'complete' ? "<strong>1</strong>" : '-'; ?></td>
              <td><?= $data->status == 'pending' ? "<strong>1</strong>" : '-'; ?></td>
              <td><?= $data->status == 'partially_complete' ? "<strong>1</strong>" : '-'; ?></td>
              <td><?= $data->status == 'incomplete' ? "<strong>1</strong>" : '-'; ?></td>
            </tr>
        <?php endforeach; ?>

      <?php endif; ?>

    </tbody>

    <tfoot>
      <tr>
        <th>Username</th>
        <th>Complete</th>
        <th>Pending</th>
        <th>Partially Complete</th>
        <th>Incomplete</th>
      </tr>
    </tfoot>

  </table>
</div>

<?php } endif; ?>

!(function($) {
  $(document).ready(function() {

    // Confirm before reset data
    $('.bpgci_reset').on( 'click', function(e) {
      e.preventDefault();
      var isConfirm = confirm("Are you sure to reset this group?")
      if(isConfirm) {
        $(location).attr('href', $(this).attr('href'))
      }
    } )

    var actionForm = $("#bpgci-action-form");

    if(actionForm.length > 0) {
      actionForm.on('click', '#doaction', function(e) {
        e.preventDefault();

        var action = $(this).parent().find('#bulk-action-selector-top option:selected').val() ||
                  $(this).parent().find('#bulk-action-selector-bottom option:selected').val();

        if(action == -1) return false;

        if($('.bpgci_bulk_checkbox:checked').length < 1) return false;


        if( action === 'bulk-delete' ) {
          confirmMsg = 'Are you sure to reset all selected groups data?';
        }

        var isConfirm = confirm( confirmMsg );

        if( !isConfirm ) return false;

        $(this).closest('form').submit()


      })
    }


    /*if( BPGCI_args.isCheckinEnabled != 0 ) {
      var checkinPermalink = '';
      var groupSlug = $('#bp-groups-slug').val();
      var checkURL = BPGCI_args.siteURL + '/check-in/' + groupSlug;

      var groupType = $('#bp-groups-group-type').val();
      var groupSlug = $('#bp-groups-slug').val();
      var groupTypeLink = BPGCI_args.siteURL + '/' + groupType + '/' + groupSlug;

      // checkinPermalink += '<div id="group-type-permalink-box">';
      // checkinPermalink += '<strong>Permalink (Group Type): </strong>';
      // checkinPermalink += '<span id="group-permalink">' + groupTypeLink + '</span>';
      // checkinPermalink += '<a href="' + groupTypeLink + '" class="button button-small" target="_blank">View Group</a>';
      // checkinPermalink += '</div>';

      checkinPermalink += '<div id="check-in-permalink-box">';
      checkinPermalink += '<strong>Check-in link: </strong>';
      checkinPermalink += '<span id="check-in-permalink">' + checkURL + '</span>';
      checkinPermalink += '<a href="' + checkURL + '" class="button button-small" target="_blank">View Group Check-in</a>';
      checkinPermalink += '</div>';

      $('#bp-groups-permalink-box').after( checkinPermalink );
    }*/

  })
})(jQuery)

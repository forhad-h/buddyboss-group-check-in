!(function($) {
  $(document).ready(function() {

    if( BPGCI_args.isCheckinEnabled != 0 ) {
      var checkinPermalink = '';
      var checkURL = BPGCI_args.siteURL + '/group-check-in?groupid=' + BPGCI_args.groupID;

      checkinPermalink += '<div id="check-in-permalink-box">';
      checkinPermalink += '<strong>Check-in link: </strong>';
      checkinPermalink += '<span id="check-in-permalink">' + checkURL + '</span>';
      checkinPermalink += '<a href="' + checkURL + '" class="button button-small" target="_blank">View Group Check-in</a>';
      checkinPermalink += '</div>';

      $('#bp-groups-permalink-box').after( checkinPermalink );
    }

  })
})(jQuery)

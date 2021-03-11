!(function($) {
  $(document).ready(function() {

    if( BPGCI_args.isCheckinEnabled != 0 ) {
      var checkinPermalink = '';
      var checkURL = BPGCI_args.siteURL + '/group-check-in?group_id=' + BPGCI_args.group_id;

      var groupType = $('#bp-groups-group-type').val();
      var groupSlug = $('#bp-groups-slug').val();
      var groupTypeLink = BPGCI_args.siteURL + '/' + groupType + '/' + groupSlug;

/*      checkinPermalink += '<div id="group-type-permalink-box">';
      checkinPermalink += '<strong>Permalink (Group Type): </strong>';
      checkinPermalink += '<span id="group-permalink">' + groupTypeLink + '</span>';
      checkinPermalink += '<a href="' + groupTypeLink + '" class="button button-small" target="_blank">View Group</a>';
      checkinPermalink += '</div>';*/

      checkinPermalink += '<div id="check-in-permalink-box">';
      checkinPermalink += '<strong>Check-in link: </strong>';
      checkinPermalink += '<span id="check-in-permalink">' + checkURL + '</span>';
      checkinPermalink += '<a href="' + checkURL + '" class="button button-small" target="_blank">View Group Check-in</a>';
      checkinPermalink += '</div>';

      $('#bp-groups-permalink-box').after( checkinPermalink );
    }

  })
})(jQuery)

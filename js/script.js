!(function($) {
  $(document).ready(function() {
    var checkinPermalink = '';
    var checkURL = bgci_args.siteURL + '/group-check-in?groupid=' + bgci_args.groupID;

    checkinPermalink += '<div id="check-in-permalink-box">';
    checkinPermalink += '<strong>Check-in link: </strong>';
    checkinPermalink += '<span id="check-in-permalink">' + checkURL + '</span>';
    checkinPermalink += '<a href="' + checkURL + '" class="button button-small" target="_new">View Group Check-in</a>';
    checkinPermalink += '</div>';

    $('#bp-groups-permalink-box').after( checkinPermalink );

  })
})(jQuery)

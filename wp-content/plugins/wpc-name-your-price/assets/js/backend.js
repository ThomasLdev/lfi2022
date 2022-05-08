'use strict';

(function($) {
  $(function() {
    if ($('input[name="_woonp_status"]:checked').val() === 'overwrite') {
      $('.woonp_show_if_overwrite').css('display', 'flex');
    }

    $('input[name="_woonp_status"]').on('change', function() {
      if ($(this).val() === 'overwrite') {
        $('.woonp_show_if_overwrite').css('display', 'flex');
      } else {
        $('.woonp_show_if_overwrite').css('display', 'none');
      }
    });
  });
})(jQuery);

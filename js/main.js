;(function($){

  // document ready
  $(function(){
    // aesthetic
    $('table.mediagroove tbody tr:odd').addClass('odd');

    // order form
    {
      $('ul.picker li').click(function(ev){
        // none -> regular -> double -> none
        var li = $(this);
        if (li.hasClass('none')) {
          li.removeClass('none').addClass('regular');
        } else if (li.hasClass('regular')) {
          li.removeClass('regular').addClass('double');
        } else if (li.hasClass('double')) {
          li.removeClass('double').addClass('none');
        }
      });
    }
  });

})(jQuery);

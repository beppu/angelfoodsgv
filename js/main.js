;(function($){

  // document ready
  $(function(){
    // aesthetic
    $('table.mediagroove tbody tr:odd').addClass('odd');

    // redo odd/even row colors
    var alternatingRows = function(){
      $('table.mediagroove tbody tr').removeClass('odd');
      $('table.mediagroove tbody tr:odd').addClass('odd');
    };

    // load prices from form
    var price = {
      'regular' : parseFloat($('#regular_price').val()),
      'double'  : parseFloat($('#double_price').val())
    };

    // recalculate totals
    var totals = function(){
      $('#order table.mediagroove tbody tr').each(function(i,tr){
        var tr    = $(tr);
        var days  = tr.find('input[type=hidden]');
        var li    = tr.find('ul.picker li');
        var r     = tr.find('ul.picker li.regular').length;
        var d     = tr.find('ul.picker li.double').length;
        var total = ((r * price['regular']) + (d * price['double'])).toFixed(2);

        var hidden = $.makeArray(li.map(function(i,x){
          var day = $(x);
          if (!day.hasClass('none')) {
            var n = day.text().replace(/^\s*/,'');
            return n + "-" + day.attr('class');
          }
        })).join(',');
        days.val(hidden);

        tr.find('td.total').html('$'+total);
      });
      var grandTotal = 0;
      $('#order table.mediagroove tbody td.total').each(function(i, td){
        var subTotal = parseFloat($(td).text().replace(/\$/, ''));
        grandTotal += subTotal;
      });
      $('#order td.grand-total').html('$'+grandTotal.toFixed(2));
    };

    // deleting child
    $('#order td.closer').live('click', function(ev){
      if (confirm("Are you sure you want to remove this row?")) {
        $(this).parents('tr').remove();
        alternatingRows();
        totals();
      }
    });

    // choosing meals
    $('#order ul.picker li').live('click', function(ev){
      var li   = $(this);

      // none -> regular -> double -> none
      if (li.hasClass('none')) {
        li.removeClass('none').addClass('regular');
      } else if (li.hasClass('regular')) {
        li.removeClass('regular').addClass('double');
      } else if (li.hasClass('double')) {
        li.removeClass('double').addClass('none');
      }
      totals();
    });

    // choosing all meals
    $('#order span.all').live('click', function(ev){
      $(this).parents('tr').find('ul.picker li').each(function(i, li){
        if ($(li).hasClass('none')) {
          $(li).click();
        }
      });
    });

    // adding another child
    $('#order div.add').click(function(ev){
      $('#factory tr').clone().appendTo('#order table.mediagroove tbody');
      alternatingRows();
      return false;
    });

  });

})(jQuery);

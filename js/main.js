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
            var n = day.text().match(/(\d+)/)[0];
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

    // validate
    $('#order').submit(function(ev){
      var errors = [];

      // reset
      $('#family-name').removeClass('error');
      $('#phone-number').removeClass('error');
      $('#order tbody td').removeClass('error');

      // validate family_name
      if ($('#family-name').val().match(/^\s*$/)) {
        errors.push({
          title  : 'Error:  Family Name',
          text   : 'Please fill in your Family Name.',
          action : function(){ $('#family-name').addClass('error') }
        });
      }

      // validate phone_number
      if ($('#phone-number').val().match(/^\s*$/)) {
        errors.push({
          title  : 'Error:  Phone Number',
          text   : 'Please fill in your Phone Number.',
          action : function(){ $('#phone-number').addClass('error') }
        });
      }

      // a row that has only 1 or 2 (of 3) fields filled in;
      // completely empty and completely full are the only ones allowed.
      var incompleteRow = function(tr) {
        var childName = tr.find('input.name');
        var grade = tr.find('select');
        var order = tr.find('input.order');
        var x = [];
        if (childName.val().match(/^\s*$/)) {
          x.push("Child's Name");
        }
        if (grade.val() == '-') {
          x.push('Grade');
        }
        if (order.val().match(/^\s*$/)) {
          x.push('Order');
        }
        if (x.length == 0) {
          return false;
        }
        if (x.length == 3) {
          return true;
        } else {
          return x;
        }
      };

      // validate each row
      var emptyRowCount = 0;
      $('#order table.mediagroove tbody tr').each(function(i,el){
        var tr = $(el);
        var x;
        if (x = incompleteRow(tr)) {
          if (typeof x == "boolean") {
            emptyRowCount++;
          } else {
            errors.push({
              title  : 'Error:  Row ' + (i+1),
              text   : 'Missing ' + x.join(' and '),
              action : function(){
                for (var j = 0; j < x.length; j++) {
                  if (x[j] == "Child's Name") {
                    tr.find('td:eq(1)').addClass('error');
                  }
                  if (x[j] == "Grade") {
                    tr.find('td:eq(2)').addClass('error');
                  }
                  if (x[j] == "Order") {
                    tr.find('td:eq(3)').addClass('error');
                  }
                }
              }
            });
          }
        }
      });

      // are all rows empty?
      if (emptyRowCount == $('#order table.mediagroove tbody tr').length) {
        errors.push({
          title : 'Error',
          text  : 'All rows are empty!'
        });
      }

      if (errors.length) {
        for (var i = 0; i < errors.length; i++) {
          $.gritter.add({
            title : errors[i].title,
            text  : errors[i].text
          });
          if (errors[i].action) {
            errors[i].action.call()
          }
        }

        // XXX
        $.gritter.add({
          title : "Under Construction",
          text  : "We'll start taking orders on 8/22."
        });

        return false;
      } else {

        // XXX
        $.gritter.add({
          title : "Under Construction",
          text  : "We'll start taking orders on 8/22."
        });
        return false;

        return true;
      }
    });

  });

})(jQuery);

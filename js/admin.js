;(function($){

  // document ready
  $(function(){

    // initialized later
    var menuId = 0;

    // save menu item if it has changed
    var menuItem = function(tr) {
      var lastSerialize = tr.data('lastSerialize');
      var latestSerialize = tr.find(':input').serialize();
      if (lastSerialize != latestSerialize) {
        var bodyStrings = [];
        tr.find('input[name=body]').each(function(i,x){ bodyStrings.push($(x).val()) });
        var item = {
          action : 'item.update',
          id     : menuId,
          day    : tr.find('input[name=day]').val(),
          t      : tr.find('select').val(),
          title  : tr.find('input[name=title]').val(),
          body   : bodyStrings.join("|")
        };
        // TODO - start save notice
        tr.find('span.saving').html('...');
        $.post('/admin/menu.php', item, function(response, headers){
          // TODO - stop save notice
          setTimeout(function(){ tr.find('span.saving').html('') }, 300);
        });
        tr.data('lastSerialize', latestSerialize);
      }
    };

    // menu-editor
    {
      // init
      $('table.menu-editor tbody tr').each(function(i,el){
        var tr = $(el);
        menuId = $('#id').val();
        tr.data('lastSerialize', tr.find(':input').serialize());
      });

      // autosave menu items
      $('table.menu-editor tbody tr').mouseout(function(ev){
        menuItem($(this));
      });

      // autosave menu items
      $('table.menu-editor tbody tr').focus(function(ev){
        menuItem($(this));
      });

      // enable/disable fields based on type
      $('table.menu-editor tbody select').change(function(ev){
        var select = $(this);
        var t = select.val();
        var tr = select.parents('table.menu-editor tr');
        switch(t) {
          case 'food':
            tr.find('input').removeAttr('disabled');
            break;
          case 'holiday':
            tr.find('td.title input').removeAttr('disabled');
            tr.find('td.extra input').attr({ disabled: 'disabled' });
            break;
          case 'dismissal':
            tr.find('td.title input').attr({ disabled: 'disabled' });
            tr.find('td.extra input').attr({ disabled: 'disabled' });
            break;
          default:
            break;
        }
      });
    }

    // setting default menu
    {
      $('table#menus td.current').click(function(ev){
        var menuId = this.id.replace(/menu-current-/, '');
        var self = this;
        $.post('menu.php', { action: 'set_current', id: menuId }, function(response){
          $('table#menus td.current').each(function(i, td){
            $(td).html('');
          });
          $(self).html('*');
        });
      });
    }

  });
  
})(jQuery);

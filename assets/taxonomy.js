//Initialize the main section controller.
acf.addAction('new_field/type=produCustomTaxonomyField', function( field ){
    let props = {
        allowNull: false,
        placeholder: 'Select',
        multiple: true,
        ajax: true,
        ajaxAction: 'acf/fields/taxonomy/query',
        ajaxData: function (data) {
          data.field_key = field.get('key');
          return data;
        },
        ajaxResults: function (json) {
          return json;
        }
    }

    acf.select2.init( field.$el.find('select'), props, field );
});

jQuery(function ($) { 
  $('.jstree_demo_div').jstree(
      {
          'plugins': ['checkbox']
      }
  ); 
  /*$.jstree.defaults.core.themes.icons = false;
  var to = false;
  $('#plugins4_q').keyup(function () {
      if(to) { clearTimeout(to); }
      to = setTimeout(function () {
      var v = $('#plugins4_q').val();
      $('.jstree_demo_div').jstree(true).search(v);
      }, 250);
  });*/
});

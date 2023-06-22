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

    let select = field.$el.find('select');
    acf.select2.init( select, props, field );

    select.on('select2:select', function(e){
      jQuery('#produ-sub-sections').append('<div data-taxonomy-id="' + e.params.data.id + '" class="jstree_produ_div"></div>');
      jQuery('[data-taxonomy-id=' + e.params.data.id + ']').jstree(
        {
          'core' : {
            'data' : {
              "url" : PRODU_DATA.tax_endpoint + e.params.data.id,
              "dataType" : "json",
            }
          },
          'plugins': ['checkbox']
        }
      );
    });

    select.on('select2:unselect', function(e){
      jQuery('[data-taxonomy-id=' + e.params.data.id + ']').remove();
    });
});

jQuery(function ($) { 
  $('.jstree_demo_div').jstree(
      {
          'plugins': ['checkbox']
      }
  );
});

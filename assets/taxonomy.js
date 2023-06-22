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

    select.on('change.select2', function (e){
      let options = jQuery(this).select2('data');
      let html = '';

      jQuery.each(options, function( index, value ){
        html += '<div data-taxonomy-id="' + value['id'] + '" class="jstree_produ_div"></div>';
      });

      jQuery('#produ-sub-sections').html(html);

      let sections = jQuery('.jstree_produ_div');

      jQuery.each(sections, function( index, div ){
        jQuery(div).jstree(
          {
            'core' : {
              'data' : {
                "url" : "http://localhost/jsTree/demo/basic/root.json" /*+ jQuery(div).data('taxonomy-id')*/,
                "dataType" : "json",
              }
            },
            'plugins': ['checkbox']
          }
        );
      });
    });
});

jQuery(function ($) { 
  $('.jstree_demo_div').jstree(
      {
          'plugins': ['checkbox']
      }
  );
});

jQuery(function ($) {
  //What to do when a main category is selected.
  PRODU_DATA.select.on('select2:select', function(e){
    $('#produ-sub-sections').append('<div data-taxonomy-id="' + e.params.data.id + '" class="jstree_produ_div"></div>');
    $('[data-taxonomy-id=' + e.params.data.id + ']')
    .on('select_node.jstree', function(e, data){
      let parentId = $(e.target).data('taxonomy-id');

      if ( typeof PRODU_DATA.subCategories === 'undefined' ) {
        PRODU_DATA.subCategories = {};
      }

      if ( typeof PRODU_DATA.subCategories['cat_' + parentId] === 'undefined' ) {
        PRODU_DATA.subCategories['cat_' + parentId];
      }

      PRODU_DATA.subCategories['cat_' + parentId] = data.selected;

      $('input[name=produ-sub-categories]').val(JSON.stringify(PRODU_DATA.subCategories));

      // TODO: Define what to do when all items are selected.
      //console.log((data.node.children.length > 0));
    })
    .on('deselect_node.jstree', function(e, data){
      let parentId = $(e.target).data('taxonomy-id');

      PRODU_DATA.subCategories['cat_' + parentId] = data.selected;
      
      $('input[name=produ-sub-categories]').val(JSON.stringify(PRODU_DATA.subCategories));
      console.log($('input[name=produ-sub-categories]').val());
    })
    .jstree(
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

  //What to do when a main category is unselected.
  PRODU_DATA.select.on('select2:unselect', function(e){
    $('[data-taxonomy-id=' + e.params.data.id + ']').remove();
  });
});

//Initialize the main section selector.
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

  if ( typeof PRODU_DATA === 'undefined' ) {
    const PRODU_DATA = {};
  }
  PRODU_DATA.select = select;
});

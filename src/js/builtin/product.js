
  // CONTROLLER
  // --------------------------------

  var Product = 
  {
      _tplStructure:   'full'
    , _viewsNames:     'Subbly.View.ProductEntry'
    , _controllerName: 'product'
    , _mainNavRegister:
      {
        defaultUrl: 'products'
      }

    , routes: {
          'products/_new': 'display'
        , 'products/:sku': 'display'
      }

      // Routes
      //-------------------------------

    , display: function( sku ) 
      {
        var scope   = this
          , isNew   = ( sku === '_new' )
          , opts    = ( isNew ) ? {} : { sku: sku }
          , product = subbly.api('Subbly.Model.Product', opts )

        subbly.fetch( product,
        {
            data: { includes: ['options', 'images', 'categories'] }
          , success: function( model, response )
            {
              // TODO: get status list from config
              var json = model.toJSON()
              
              json.isNew      = isNew
              json.statusList = [ 'draft', 'active', 'hidden', 'sold_out', 'coming_soon' ]

              scope.getViewByPath( 'Subbly.View.ProductEntry' )
                .setValue( 'model', model )
                .displayTpl( json )
            }
        }, this )
      }
  }


  // VIEWS
  // --------------------------------

  // Product sheet view
  var ProductEntry = 
  {
      _viewName:     'ProductEntry'
    , _viewTpl:      TPL.products.entry

    , onDisplayTpl: function( tplData )
      {
        // !! always set form after html render
        this.setForm({
            id:       'subbly-product-entry'
          , rules:    this.model.getRules()
          , skip:     false
        })
      }

    , onSubmit: function()
      {
        subbly.store( this.model, this.getFormValues(), 
        {
          success: function( model, response )
          {
            subbly.trigger( 'hash::change', 'products' )
          }
        })
      }
  }



  // REGISTER PLUGIN
  // --------------------------------


  subbly.register( 'Subbly', 'Products', 
  {
      'ViewForm:ProductEntry': ProductEntry
    , 'Controller:Product':    Product
  })

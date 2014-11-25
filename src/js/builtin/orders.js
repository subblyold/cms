
  var Orders = 
  {
      _tplStructure:   'half' // full|half|third
    , _viewsNames:     [ 'Subbly.View.Orders', 'Subbly.View.OrderEntry' ]
    , _controllerName: 'orders'
    , _mainNavRegister:
      {
          name:       'Orders'
        , order:      190
        , defaultUrl: 'orders'
      }
      
    , routes: {
          'orders':     'display'
        , 'orders/:id': 'display'
      }

    , onRemove: function()
      {
        this._listDisplayed = false
      }
      // Routes
      //-------------------------------

    , display: function( id ) 
      {
        if( !this._listDisplayed )
        {
          var scope = this

          this.displayView( function()
          {
            if( !_.isNull( id ) )
              scope.sheet( id )
          })

          return
        }
        
        if( !_.isNull( id ) )
          this.sheet( id )
        else
          this.getViewByPath( 'Subbly.View.Orders' )
            .onInitialRender()
      }

    , displayView: function( sheetCB )
      {
        var scope  = this

        subbly.fetch( subbly.api('Subbly.Collection.Orders'),
        {
            data:   {
                offset:   0
              , limit:    5
              , includes: ['user', 'products']
            }
          , success: function( collection, response )
            {
              scope._listDisplayed = true
              scope.getViewByPath( 'Subbly.View.Orders' )
                .setValue( 'collection', collection )
                .displayTpl()

              if( _.isFunction( sheetCB ) )
                sheetCB()
            }
        }, this )
      }

    , sheet: function(  id ) 
      {
        this.getViewByPath( 'Subbly.View.Orders' ).setActiveRow( id )

        var scope  = this
          , order  = subbly.api('Subbly.Model.Order', { id: id })

        subbly.fetch( order,
        {
            data: { includes: ['user', 'shipping_address', 'billing_address', 'products'] }
          , success: function( model, response )
            {
              var json = model.toJSON()
              json.listStatus   = [ 'draft', 'confirmed', 'refused', 'waiting', 'paid', 'sent' ]
              json.customerName = 'Fake Name'
              json.createdDate  = moment.utc( model.get('created_at') ).fromNow()

              scope.getViewByPath( 'Subbly.View.OrderEntry' )
                .setValue( 'model', model )
                .displayTpl( json )
            }
        }, this )
      }
  }


  // Order Entry view
  var OrderEntry = 
  {
      _viewName:     'OrderEntry'
    , _viewTpl:      TPL.orders.entry
  }

  var OrdersList = 
  {
      _viewName:     'Orders'
    , _viewTpl:      TPL.orders.list
    , _classlist:    ['view-half-list']
    , _listSelector: '#orders-list'
    , _tplRow:        TPL.orders.listrow

      // On view initialize
    , onInitialize: function()
      {
        // add view's event
        this.addEvents( {'click li.js-trigger-goto':  'goTo'} )
      }

      // Call on list first render
    , onInitialRender: function()
      {
        var $firstRow = this.getListEl().children(':first')
          , id       = $firstRow.attr('data-id')

        this.callController( 'sheet', id )
      }

      // Build single list's row
    , displayRow: function( model )
      {
        var html = this._tplRowCompiled({
            id:           model.get('id')
          , totalPrice:   model.get('total_price')
          , totalItems:   model.get('products').length
          , orderStatus:  model.get('status')
          , customerName: 'Fake Name'
          , createdDate:  moment.utc( model.get('created_at') ).fromNow()
        })

        return html
      }

      // Higthligth active row
    , setActiveRow: function( id )
      {
        var $listRows  = this.getListRows()
          , $activeRow = $listRows.filter('[data-id="' + id + '"]')

        $listRows.removeClass('active')
        $activeRow.addClass('active')
      }

      // go to customer profile
    , goTo: function( event )
      {
        var id = event.currentTarget.dataset.id

        subbly.trigger( 'hash::change', 'orders/' + id )
      }
  }

  subbly.register( 'Subbly', 'Orders', 
  {
      'ViewList:Orders':   OrdersList
    , 'View:OrderEntry':   OrderEntry
    , 'Controller:Orders': Orders
  })

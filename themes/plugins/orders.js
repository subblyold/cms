
(function( window )
{
  var Orders = 
  {
      _tplStructure:   'half' // full|half|third
    , _viewsNames:     [ 'Subbly.View.Orders', 'Subbly.View.Order' ]
    , _controllerName: 'orders'
    , _mainNavRegister:
      {
          name:       'Orders'
        , order:      190
        , defaultUrl: 'orders'
      }

    , routes: {
          'orders':      'list'
        , 'orders/:uid': 'details'
      }

      // Routes
      //-------------------------------

    , list: function() 
      {
        this._mainRouter._currentView = this

        this.getViewByPath( 'Subbly.View.Orders' ).displayTpl()
      }

    , details: function( uid ) {}
  }

  var OrdersUserRow = 
  {
      tagName:   'li'
    , className: 'cln-lst-rw cust-row js-trigger-goto'
    , _viewName: 'Order'

    , onInitialize: function( options )
      {
        this.tplRow = options.tpl
      }

    , render: function()
      {
        var html = this.tplRow({
            displayName: this.model.displayName()
          , createdDate: moment.utc( this.model.get('created_at') ).fromNow()
        })

        this.$el.html( html )

        return this
      }
  }

  var OrdersList = 
  {
      _viewName:     'Orders'
    , _viewTpl:      TPL.orders.list
    , _classlist:    ['view-half-list']
    , _listSelector: '#orders-list'
    , _tplRow:        TPL.orders.listrow
    , _viewRow:       'Subbly.View.Order'
  }

  Subbly.register( 'Subbly', 'Orders', 
  {
      'ViewList:Orders':   OrdersList
    , 'ViewListRow:Order': OrdersUserRow
    , 'Controller:Orders': Orders
  })

})( window )

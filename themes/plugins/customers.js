
(function( window )
{
  var Customers = 
  {
      _tplStructure:   'half' // full|half|third
    , _viewsNames:     [ 'Subbly.View.Customers', 'Subbly.View.Customer' ]
    , _controllerName: 'customers'
    , _mainNavRegister:
      {
          name:       'Customers'
        , order:      90
        , defaultUrl: 'customers'
      }

    , routes: {
          'customers':      'list'
        , 'customers/:uid': 'details'
      }

    // Local method
    , getCollection: function()
      {
        if( !this.collection )
          this.collection = Subbly.api('Subbly.Collection.Users')
      }

      // Routes
      //-------------------------------

    , list: function() 
      {
        this._mainRouter._currentView = this

        this.getCollection()

        this.fetch( this.collection,
        {
            data:   {
                offset: 0
              , limit:  1
            }
          , success: _.bind( this.cbAlaCon, this )
        }, this )
      }

      // test Callback
    , cbAlaCon: function( collection, response )
      {
        this.getViewByPath( 'Subbly.View.Customers' )
          .setValue( 'collection', collection )
          .displayTpl()
      }

    , details: function( uid ) 
      {
        this.getCollection()
        
        var user = this.collection.get( '48cc9851f125ea646d7dd3e26988abae' )

        this.fetch( user,
        {
            data: { includes: ['addresses', 'orders'] }
          , success: function( model, response )
            {
    console.log( model.displayName() )
    console.log( response )
            }
        }, this )
      }
  }

  var CustomersUserRow = 
  {
      tagName:   'li'
    , className: 'cln-lst-rw cust-row js-trigger-goto'
    , _viewName: 'Customer'

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

  var CustomersUsers = 
  {
      _viewName:     'Customers'
    , _viewTpl:      TPL.customers.list
    , _classlist:    ['view-half-list']
    , _listSelector: '#customers-list'
    , _tplRow:        TPL.customers.listrow
    , _viewRow:       'Subbly.View.Customer'
  }

  Subbly.register( 'Subbly', 'Customers', 
  {
      'ViewList:Customers':   CustomersUsers
    , 'ViewListRow:Customer': CustomersUserRow
    , 'Controller:Customers': Customers
  })

})( window )


(function( window )
{
  var Customers = 
  {
      _tplStructure:   'half' // full|half|third
    , _viewsNames:     [ 'Subbly.View.Customers', 'Subbly.View.Customer' ]
    , _controllerName: 'customers'
    , _mainNav:
      {
          name:       'Customers'
        , order:      90
        , defaultUrl: 'customers'
      }

    , onInitialize: function()
      {
  console.log( 'onInitialize Customers')
      }

    , routes: {
          'customers':      'list'
        , 'customers/:uid': 'details'
      }

    // Local method
    , fetch: function()
      {
        Subbly.trigger( 'loader::show' )

        this.collection.fetch(
        {
          success: function()
          {
            Subbly.trigger( 'pagination::changed' )
          } 
        })
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
        // return
  console.info('call customer list')
        this.getCollection()

        this.collection.fetch(
        {
            success: function( collection, response )
            {
    // console.log( collection )
    // console.log( response )
            }
        })
      }

    , details: function( uid ) 
      {
        this.getCollection()
        
        var user = this.collection.get( '48cc9851f125ea646d7dd3e26988abae' )
    console.log( user )

        user.fetch(
        {
            data: { includes: ['addresses', 'orders'] }
          , success: function( model, response )
            {
    console.log( model.displayName() )
    console.log( response )
            }
        })
      }
  }

  var CustomersUsers = 
  {
      _viewName: 'Users'
  }

  var CustomersUser = 
  {
      _viewName: 'User'
  }

  Subbly.register('Subbly', 'Customers', 
  {
      'View:Customers':       CustomersUsers
    , 'View:Customer':        CustomersUser
    , 'Controller:Customers': Customers
  })

})( window )

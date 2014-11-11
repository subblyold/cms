
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

//     , onInitialize: function()
//       {
//   console.log( 'onInitialize Customers')
//         this.on( 'fetch::calling', function()
//         {
// console.log('fetch::calling')
//         } )

//         this.on( 'fetch::responds', function()
//         {
// console.log('fetch::responds')
//         } )
//       }

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
            success: _.bind( this.cbAlaCon, this )
        }, this )
      }

      // test Callback
    , cbAlaCon: function( collection, response )
      {
        console.log( collection )
        // console.log( response )
        // console.log( this._controllerName )
        this.getViewByPath( 'Subbly.View.Customers' ).displayTpl()
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

  var CustomersUsers = 
  {
      _viewName:  'Users'
    , _viewTpl:   TPL.customers.list
    , _classlist: ['view-half-list']

    , display: function()
      {
  console.info('display customers view')
      }

  }

  var CustomersUser = 
  {
      _viewName: 'User'
  }

  Subbly.register( 'Subbly', 'Customers', 
  {
      'View:Customers':       CustomersUsers
    , 'View:Customer':        CustomersUser
    , 'Controller:Customers': Customers
  })

})( window )

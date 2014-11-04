
Components.Controller.Customers = SubblyController.extend(
// var Customers = 
{
    _tplStructure:   'half' // full|half|third
  , _viewsNames:     [ 'Users', 'User' ]
  , _controllerName: 'customers'

  , onInitialize: function()
    {
console.log( 'onInitialize Customers')
    }

  , routes: {
        'customers':      'list'
      , 'customers/:uid': 'details'
    }

  , fetch: function()
    {
      subbly.event.trigger( 'loader::show' )

      this.collection.fetch(
      {
        success: function()
        {
          subbly.event.trigger( 'pagination::changed' )
        } 
      })
    }

  , getCollection: function()
    {
      if( !this.collection )
        this.collection = subbly.api('Collection.Users')
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
  console.log( collection )
  console.log( response )
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
// }
})
// console.log( subbly )
SubblyPlugins.register( 'Customers' )
// subbly.extend( 'Controller', 'Customers', Customers )

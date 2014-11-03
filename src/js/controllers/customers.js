
Components.Controller.Customers = Backbone.Controller.extend(
{
    routes: {
        'customers':      'list'
      , 'customers/:uid': 'details'
    }

  , initialize: function() 
    {
console.log('customers Controller initialized')
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

  , list: function() 
    {
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
})

SubblyPlugins.register( 'Customers' )
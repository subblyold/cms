
Components.Controller.Customers = Backbone.Controller.extend(
{
    routes: {
        'customers':      'list'
      , 'customers/:uid': 'details'
    }

  , initialize: function() 
    {
console.log('customers Controller initialized')
//       this.collection = subbly.api('Collection.Users')

//       this.collection.fetch(
//       {
//         success: function( collection, response )
//         {
// console.log( collection )
// console.log( response )
//         }
//       })
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

  , list: function() 
    {
      console.info('customers list')
    }

  , details: function( uid ) 
    {

    }
})

SubblyPlugins.register( 'Customers' )
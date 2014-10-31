
// Subbly's Backbone base collection

var SubblyCollection = Backbone.Collection.extend(
{
    parse: function ( response ) 
    {
      return response.response[ this.serviceName ]
    }

  , url: function()
    {
      return subbly.apiUrl( this.serviceName )
    }

  , credentials: function() 
    {
      return subbly.getCredentials()
    }

  , fetch: function( options ) 
    {
      options = options || {}

      var data = options.data || {}
      
      if( this.queryParams )
        options.data = $.extend( this.queryParams(), options.data )

      return Backbone.Collection.prototype.fetch.call( this, options )
    }
})

// Subbly's Backbone base model

var SubblyModel = Backbone.Model.extend(
{
    url: function()
    {
      var baseUrl = subbly.apiUrl( this.serviceName )

      if( this.isNew() )
        return baseUrl

      return baseUrl + '/' + this.id
    }

  , credentials: function() 
    {
      return subbly.getCredentials()
    }

  , parse: function ( response ) 
    {
      // single item api call 
      if( response.response )
          return response.response[ this.singleResult ]

      // collection fetch
      return response
    }

  , getNested: function( property, defaults )
    {
      return Helpers.getNested( this.attributes, property, defaults || false )
    }
})
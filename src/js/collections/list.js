
// Base collection with pagination

var SubblyCollectionList

Components.Subbly.Collection.List = SubblyCollectionList = SubblyCollection.extend(
{
    defaultPerPage: 5

  , initialize: function( options )
    {
      options = options || {}

      this.total  = false
      this.offset = 0
      this.limit  = options.limit || this.defaultPerPage
      this.page   = 1

      if( this.init )
        this.init( options )
    }

  , resetPagination: function()
    {
      this.page   = 1
    }

  , queryParams: function()
    {
      return {
          offset: this.offset
        , limit:  this.limit
      }
    }

  , parse: function( response )
    {
      this.limit  = response.response.limit
      this.total  = response.response.total
      this.offset = response.response.offset

      return response.response[ this.serviceName ]
    }

  , getTotal: function()
    {
      if( !this.total )
        return false

      return this.total
    }

  , getPerPage: function()
    {
      return this.limit
    }

  , setPerPage: function( value )
    {
      this.limit = value
    }

  , pageInfo: function() 
    {
      var info = 
      {
          total:   this.total
        , page:    this.page
        , limit:   this.limit
        , pages:   Math.ceil( this.total / this.limit )
        , prev:    false
        , next:    false
      };

      var max = Math.min( this.total, this.page * this.limit )

      if( this.total == this.pages * this.limit )
        max = this.total

      info.range = [ ( this.page - 1 ) * this.limit + 1, max ]

      if( this.page > 1 )
        info.prev = this.page - 1

      if( this.page < info.pages )
        info.next = this.page + 1

      return info
    }

  , nextPage: function()
    {
      if( !this.pageInfo().next )
        return false

      this.offset = ( this.page * this.limit )
      this.page   = this.page + 1

      return true
    }

  , prevPage: function() 
    {
      if( !this.pageInfo().prev )
        return false

      this.page   = ( this.page - 1 )
      this.offset = ( ( this.page - 1 ) * this.limit )

      return true
    }
})

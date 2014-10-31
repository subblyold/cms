
var Helpers = 
{
    getNested: function( obj, path, defaults )
    {
      var fields = path.split('.')
        , result = obj

      for( var i = -1, n = fields.length; ++i < n; )
      {
        result = result[ fields[ i ] ]

        if( result == 'undefined' && i < n - 1 )
           result = {}
        
        if( typeof result === 'undefined' )
          return result || defaults
      }

      return result
    }

  , setNested: function( obj, path, val, options )
    {
      options = options || {}

      var fields = path.split('.')
        , result = obj

      for( var i = 0, n = fields.length; i < n && result !== undefined ; i++ )
      {
        var field = fields[ i ]

        // If the last in the path, set the value
        if( i === n - 1 )
        {
          options.unset ? delete result[ field ] : result[ field ] = val
        }
        else
        {
          // Create the child object if it doesn't exist, or isn't an object
          if( typeof result[ field ] === 'undefined' || ! _.isObject( result[ field ] ) ) 
          {
            result[ field ] = {}
          }

          // Move onto the next part of the path
          result = result[ field ]
        }
      }
    }

  , deleteNested: function ( obj, path )
    {
      Helpers.setNested(obj, path, null, { unset: true })
    }

  , deepMerge: function( target, source )
    {
      for( var key in source )
      {
        var original = target[ key ]
          , next     = source[ key ]

        if( original && next && typeof next == 'object' )
        {
          Helpers.deepMerge( original, next )
        }
        else
        {
          target[key] = next
        }
      }

      return target
    }
}

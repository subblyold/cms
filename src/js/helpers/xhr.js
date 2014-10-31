
var xhrCall = function( options )
{
  var _void    = function(){}

  var defaults = {
      type:        'GET'
    , dataType:    'json'
    , data:        {}
    , cache:       false
    , onSuccess:   _void
    , onError:     _void
    , queryString: false
  }

  options = options || {}

  if( !options.url )
    throw new Error('no URL provided')

  var settings = $.extend( {}, defaults, options )
  
  var url = subbly.apiUrl( options.url )

  if( settings.queryString )
  {
    for( var key in settings.queryString )
    {
      url += '&' + key + '=' + settings.queryString[ key ]
    }
  }

console.info( 'xhr url', url )

  var xhr = $.ajax({
      url:     url
    , type:    settings.type
    , data:    settings.data
    , cache:   settings.cache
    , success: settings.onSuccess
    , error:   settings.onError
  })

  return xhr
}
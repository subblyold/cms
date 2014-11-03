
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

  options.url = subbly.apiUrl( options.url )

  var settings = $.extend( {}, defaults, options )

  if( settings.queryString )
  {
    for( var key in settings.queryString )
    {
      url += '&' + key + '=' + settings.queryString[ key ]
    }
  }

  var xhr = $.ajax( settings )

  return xhr
}
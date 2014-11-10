
  // Global error handler for backbone.js ajax requests
  // http://stackoverflow.com/a/6154922
  $document.ajaxError( function( e, xhr, options )
  {
    var isJson     = ( xhr.responseJSON )
      , response = false
      , message  = false

    if( isJson )
    {
      response = ( xhr.responseJSON.response )
                 ? xhr.responseJSON.response
                 : false

      message = ( response && response.error )  
                ? response.error 
                : false
    }

console.group('Ajax Error')
console.log( xhr )
console.log( xhr.status )
console.log( xhr.responseJSON )
console.log( ( message ) ? message : 'no error message' )
console.groupEnd()

    if( xhr.status === 401 )
    {
      subbly.trigger( 'user::logout', message )
      return 
    }

    // if( xhr.status === 418 )
    //   return 

    // // ask delete token
    // if( xhr.status === 406 && !xhr.responseJSON.error )
    //   return
    
    // // real error
    // // display message
    // App.feedback.handleError( xhr )
  })

  // On DOMready
  $(function()
  {
    subbly.init()
  })

})( window ); // end closure

// In case we forget to take out console statements. 
// IE becomes very unhappy when we forget. Let's not make IE unhappy
if(typeof(console) === 'undefined')
{
  var console = {}
  console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function () {};
}

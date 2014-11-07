
  // Zombies! RUN!
  // Managing Page Transitions In Backbone Apps
  // http://lostechies.com/derickbailey/2011/09/15/zombies-run-managing-page-transitions-in-backbone-apps/
  Backbone.View.prototype.close = function()
  {
    if ( this.onClose )
      this.onClose()

    this.remove()
    this.unbind()
    this.undelegateEvents()
  }

  // Remove empty properties / falsy values from Object with Underscore.js
  // http://stackoverflow.com/a/14058408
  _.mixin(
  {
      compactObject: function( o ) 
      {
        _.each( o, function( v, k )
        {
           if( !v )
            delete o[ k ]
        })
        return o
      }
  })

  // Length of Javascript Object
  // http://stackoverflow.com/questions/5223/length-of-javascript-object-ie-associative-array
  Object.size = function( obj )
  {
    var size = 0
      , key

    for ( key in obj )
    {
      if ( obj.hasOwnProperty( key ) ) 
        ++size
    }

    return size
  }

  // Convert form data to JS object with jQuery
  // http://stackoverflow.com/a/1186309/3908378
  $.fn.serializeObject = function( skipEmpty )
  {
    var obj       = {}
      , arr       = this.serializeArray()
      , skipEmpty = skipEmpty || false

    $.each( arr, function() 
    {
      var value = this.value.trim()
        , empty = _.isEmpty( value )

      if( skipEmpty && empty )
        return

      if( empty )
        value = ''

      if( obj[ this.name ] !== undefined )
      {
        if( !obj[ this.name ].push )
          obj[ this.name ] = [ obj[ this.name ] ]

        obj[ this.name ].push( value )
      }
      else
      {
        obj[ this.name ] = value
      }
    })

    return obj
  }

  // form reset jQuery compliant
  $.fn.reset = function()
  {
    this[0].reset()

    return this
  }

  $.fn.addAttr = function( key )
  {
    this.attr( key, key )
  }

  $window.on('resize', function( event )
  {
    // update viewport
    var viewport = {
        height: $window.height()
      , width:  $window.width()
    }

    // publish jQuery event + viewport 
    subbly.trigger( 'window::resize', event, viewport )
  })
  
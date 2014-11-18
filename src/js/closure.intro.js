
;(function (window, undefined) 
{
  'use strict';

  // Defined what event we must call at the end of a CSS transition
  var whichTransitionEvent = function ()
  {
    var t
      , el = document.createElement('fakeelement')
      , transitions = 
        {
            'transition':       'transitionend'
          , 'OTransition':      'oTransitionEnd'
          , 'MozTransition':    'transitionend'
          , 'WebkitTransition': 'webkitTransitionEnd'
        }

    for( t in transitions )
      if( el.style[ t ] !== undefined )
        return transitions[ t ]
  }

  // Prepare our Variables
  var document      = window.document
    , $             = window.jQuery
    , $document     = $( document )
    , $window       = $( window )
    , $body         = $( document.body )
    , subbly        = false
    , AppRouter     = false
    , transitionEnd = whichTransitionEvent()

  // Extend default 
  // components structure
  // for each Vendors
  var defautlsFwObj = function()
  {
    var defaults  = {
        Model:      {}
      , Collection: {}
      , View:       {}
      , Controller: {}
      , Component:  {}
    }

    return $.extend( {}, defaults, {} )
  }

  var Components = 
  {
    Subbly: defautlsFwObj()
  }


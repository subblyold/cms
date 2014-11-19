
;(function (window, undefined) 
{
  'use strict';

  // Defined what event we must call at the end of a CSS transition/animation
  var animEndEventNames = {
      WebkitAnimation: 'webkitAnimationEnd'
    , OAnimation:      'oAnimationEnd'
    , msAnimation:     'MSAnimationEnd'
    , animation:       'animationend'
  }

  var transitionEndEventNames = {
      WebkitTransition: 'webkitTransitionEnd'
    , OTransition:      'oTransitionEnd'
    , MozTransition:    'transitionend'
    , transition:       'transitionend'
  }

  var transitionEndEventName = transitionEndEventNames[ Modernizr.prefixed( 'transition' ) ]
    , animEndEventName       = animEndEventNames[ Modernizr.prefixed( 'animation' ) ]

  // Prepare our Variables
  var document      = window.document
    , $             = window.jQuery
    , $document     = $( document )
    , $window       = $( window )
    , $body         = $( document.body )
    , subbly        = false
    , AppRouter     = false

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


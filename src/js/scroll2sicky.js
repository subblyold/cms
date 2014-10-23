
var scroll2sicky = (function()
{
  var $elements
    , $nano
    , $window
    , lists

  var refresh = function( event, values )
  {
    lists.forEach( function( item )
    {
      item.update( values.position )
    })
  }

  var reset = function()
  {
    lists.forEach( function( item )
    {
      item.reset()
    })
  }

  var getDisplay = function( element )
  {
    var cnt    = element.querySelector('.scrll-stck-cnt')
      , height = cnt.offsetHeight
      , width  = element.offsetWidth

    element.style.height = height + 'px'
    cnt.style.width      = width + 'px'

    return cnt    
  }

  // TODO, $el should not have more than one stickable element
  var init = function( $el )
  {    
    $elements = $el[0]
    $nano     = $el.find('div.nano')
    $window   = $( window )

    lists = []

    ;[].forEach.call(
        $elements.querySelectorAll('.scrll-stck')
      , function( element )
        {
          var cnt  = getDisplay( element )
            , list = new scroll2sickyList( cnt )

          // Add this element to the collection
          lists.push( list )
        }
    )

    $nano.on('update', refresh )
    $window.on( 'resize', function()
    {
      getDisplay( $elements.querySelector('.scrll-stck') )
    })
  }

  var unload = function()
  {
    $nano.off('update', refresh )
  }

  return {
      'init':   init
    , 'unload': unload
    , 'reset':  reset
  }

})()


/**
 * The basic type of list; applies sticky classe to
 * list items based on scroll state.
 */
function scroll2sickyList( element )
{
  this.element = element

  var t = element.offsetTop
    , o = element

  while ( o = o.offsetParent )
      t += o.offsetTop

  this.top     = t

  this.update( 0 )
}

/**
 * Force remove sticky class
 */
scroll2sickyList.prototype.reset = function() 
{
  this.element.classList.remove('sticky')
}

/**
 * Apply sticky classes to list items outside of the viewport
 */
scroll2sickyList.prototype.update = function( scrollTop ) 
{
  this.element.classList[ ( this.top <= scrollTop ) ? 'add' : 'remove' ]('sticky')
}

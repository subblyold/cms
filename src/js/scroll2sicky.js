
var scroll2sicky = function( $el )
{
  this.$element = this.getDisplay( $el[0].querySelector('.scrll-stck') )

  if( !this.$element )
    return

  this.$nano    = $el.find('div.nano')
  this.$window  = $( window )
  this.list     = new scroll2sickyList( this.$element, this )

  this.resizeCB = function()
  {
    if( $el[0].querySelectorAll('.scrll-stck')[0] )
      this.getDisplay( $el[0].querySelectorAll('.scrll-stck')[0] )
  }

  this.$nano.on('update', _.bind( this.refresh, this ) )
  this.$window.on( 'resize.scroll2sicky', _.bind( this.resizeCB, this ) )

  return this
}

scroll2sicky.prototype.refresh = function( event, values )
{
  this.list.update( values.position )
}

scroll2sicky.prototype.reset = function()
{
  this.list.reset()
}

scroll2sicky.prototype.getDisplay = function( element )
{
  if( _.isNull( element ) )
    return false

  var cnt    = element.querySelector('.scrll-stck-cnt')
    , height = cnt.offsetHeight
    , width  = element.offsetWidth

  element.style.height = height + 'px'
  cnt.style.width      = width + 'px'

  return cnt
}

scroll2sicky.prototype.kill = function()
{
  if( !this.$element )
    return

  this.$window.off( 'resize.scroll2sicky' )
}

/**
 * The basic type of list; applies sticky classe to
 * list items based on scroll state.
 */
function scroll2sickyList( element, context )
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


var Feedback = function()
{
  this.wrapper      = document.body
  this.overlay      = document.getElementById('feedback-overlay')
  this.content      = document.getElementById('main-view')
}

/*
 *  type 'success|error|warning'
 */
Feedback.prototype.add = function( message, type )
{
  this.entry = document.createElement( 'div' )
  this.entry.className = 'feedback'

  var inner = document.createElement( 'div' ) 
  inner.className = 'feedback-inner'
  inner.innerHTML = message

  this.entry.appendChild( inner )

  this.wrapper.insertBefore( this.entry, document.body.firstChild )

  var feedback = this
    , view     = this.content.querySelector( '.view-full' )
    , entry    = this.entry

  var remove = function( event ) 
  {
    entry.addEventListener( transitionEndEventName, function()
    {
      entry.remove()
    })

    entry.classList.add( 'hide' )
    view.classList.remove( 'w-feedback' )
  }

  this.entry.addEventListener( animEndEventName, function()
  {
    view.classList.add( 'w-feedback' )
    this.classList.add( type )
    this.classList.add( 'done' )

    var timer = window.setTimeout( remove, 3000 )

    this.addEventListener( 'click', function()
    {
      window.clearTimeout( timer )
      remove()

    }, false)

  }, false)

  this.entry.classList.add('show')
  this.overlay.classList.add('show')
}

/*
 * 
 */
Feedback.prototype.setState = function( state )
{
  this.entry.classList.add( state )
}

/*
 * 
 */
Feedback.prototype.done = function()
{
  this.entry.classList.remove('show')
  this.overlay.classList.remove('show')
}


var FEEDBACK_TIMEOUT = 3000
  , FEEDBACK_TIMER   = null 

var Feedback = function()
{
  this.overlay      = document.getElementById('feedback-overlay')
  this.content      = document.getElementById('main-view')

  var wrapper = document.body
    , entry   = document.createElement( 'div' )
    , inner   = document.createElement( 'div' )
    , scope   = this

  entry.className = 'feedback'
  inner.className = 'feedback-inner'

  entry.appendChild( inner )

  wrapper.insertBefore( entry, document.body.firstChild )

  this.view    = document.getElementById('main-view')
  this.$entry  = $( entry )
  this.$msg    = $( inner )
  this.isDone  = false

  this.$entry
    .one( 'click', function()
    {
      scope.dismiss()
    })

  this.overlay.classList.add('show')

  return this
}

/*
 * 
 */
Feedback.prototype.display = function( message )
{
  this.isDone = true
  this.upMainView()
  this.message( message )
  this.$msg.addClass('display')

  var scope = this

  FEEDBACK_TIMER = window.setTimeout( function()
  {
    scope.dismiss()
  }, FEEDBACK_TIMEOUT )
}

/*
 * 
 */
Feedback.prototype.message = function( message )
{
  this.$msg.text( message )

  return this
}

/*
 * 
 */
Feedback.prototype.state = function( state )
{
  this.$entry.addClass( state )

  return this
}

/*
 * 
 */
Feedback.prototype.progress = function()
{
  this.$entry
    .css({
        width:  0
      , height: '12px'
    })
    .animate({
      width: '100%'
    }, 30000, function()
    {
      console.log('callback')
    })

  return this
}

/*
 * 
 */
Feedback.prototype.progressEnd = function( state, message )
{
  var width = this.$entry.width() 
    , scope = this
  
  this.state( state )
  
  this.$entry
    .stop( true, true )
    .css( 'width', width)
    .animate(
    {
      width: '100%'
    }
    , 500
    , function()
    {
      scope.$entry.animate({ height: 66 }, 250 )
      scope.display( message )
    })

  return this
}

/*
 * 
 */
Feedback.prototype.downMainView = function()
{
  this.view.classList.remove( 'w-feedback' )
}

/*
 * 
 */
Feedback.prototype.upMainView = function()
{
  this.view.classList.add( 'w-feedback' )
}


/*
 * 
 */
Feedback.prototype.dismiss = function()
{
  if( !this.isDone )
    return

  window.clearTimeout( FEEDBACK_TIMER )

  var scope = this

  this.overlay.classList.remove('show')

  this.$entry.fadeOut( 300, function()
  {
    scope.downMainView()
    this.remove()
  })
}

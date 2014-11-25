
var FEEDBACK_TIMEOUT = 3000
  , FEEDBACK_TIMER   = null 

var Feedback = function()
{
  this.overlay   = document.getElementById('feedback-overlay')
  this.view      = document.getElementById('main-view')
  this.isDisplay = false
  this.ended     = false

  return this
}

/*
 * 
 */
Feedback.prototype.add = function()
{
  if( this.isDisplay )
    return this

  this.isDisplay = true

  var wrapper = document.body
    , entry   = document.createElement( 'div' )
    , inner   = document.createElement( 'div' )
    , scope   = this

  entry.className = 'feedback'
  inner.className = 'feedback-inner'

  entry.appendChild( inner )

  wrapper.insertBefore( entry, document.body.firstChild )

  // this.view    = document.getElementById('main-view')
  this.$entry  = $( entry )
  this.$msg    = $( inner )
  this.isDone  = false

  this.$entry
    .one( 'click', function()
    {
      scope.dismiss()
    })

  // this.overlay.classList.add('show')

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
    }, 5000, function(){})

  return this
}

/*
 * 
 */
Feedback.prototype.progressEnd = function( state, message )
{
  if( this.ended || _.isUndefined( this.$entry ) )
    return this

  this.ended = true

  var width = this.$entry.width() 
    , scope = this

  if( !_.isUndefined( state ) )
    this.state( state )
  
  this.$entry
    .stop( true, true )
    .css( 'width', width)
    .animate(
        { width: '100%' }
      , 250
      , function()
        {
          if( !_.isUndefined( message ) )
          {
            scope.$entry.stop( true, true ).animate({ height: 66 }, 250 )
            scope.display( message )
            return
          }

          scope.ended = false
          
          scope.overlay.classList.remove('show')

          scope.$entry.stop( true, true ).fadeOut( 300, function()
          {
            scope.isDisplay = false
            this.remove()
          })
        }
    )

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
    return this

  window.clearTimeout( FEEDBACK_TIMER )

  var scope = this

  this.overlay.classList.remove('show')

  this.$entry.fadeOut( 300, function()
  {

    scope.ended     = false  
    scope.isDisplay = false

    scope.downMainView()
    this.remove()
  })
}

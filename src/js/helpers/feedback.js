
var Feedback = function()
{
  this.messageCache = []
  this.wrapper      = document.body
  this.overlay      = document.getElementById('feedback-overlay')
}

/*
 *  type 'success|error|warning'
 */
Feedback.prototype.add = function( type, message )
{
  this.entry = document.createElement( 'div' )
  this.entry.className = 'feedback'

  var inner = document.createElement( 'div' ) 
  inner.className = 'feedback-inner'
  inner.innerHTML = message

  this.entry.appendChild( inner )

  this.wrapper.insertBefore( this.entry, document.body.firstChild )

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

  var $this = $( this.entry )

  $this
    .one( transitionEndEventName, function()
    {
console.log('transitionEndEventName callback')
      // $this
      //   .removeClass('done')
    })
    .addClass('done')
}

/*
 *  type 'success|error|warning'
 */
Feedback.prototype.dismiss = function()
{
  this.entry.classList.remove('show')
  this.overlay.classList.remove('show')

  $( this.entry )
    .one( animEndEventName, function()
    {
      $( this ).remove()
    })
    .addClass('hide')
}


var SubblyCore = function( config )
{
  console.info('init Subbly object', config)

  // enviroment config
  this._config          = config

  // current user model
  this._user              = false
  this._changesAreSaved   = true
  this._credentialsCookie = 'SubblyCredentials'

  // current user credentials
  this._credentials = false

  // Pub/Sub channel
  this.event  = _.extend( {}, Backbone.Events )

  return this
}


/*
 * Initialize App router
 *
 * @return  {void}
 */

SubblyCore.prototype.init = function()
{
  this._router = new Router()

  var scope = this

  this.event.on( 'hash::change', function( href )
  {
    if( scope._changesAreSaved )
    {
      scope.event.trigger( 'hash::changed' )
      scope._router.navigate( href, { trigger: true } )
    }
    else
    {
      // display confirm modal
      // App.changesModal = new Views.Modal(
      // {
      //     message:  __('label.form_leave')
      //   , success:  __('label.leave')
      //   , onSubmit: function()
      //     {
      //       Pubsub.trigger( 'form::reset' )
      //       AppRouter.navigate( href, { trigger: true } )
      //     }
      // })
    }
  })

  this.isLogin()

  this._router.ready()
}

/*
 * Get config value
 *
 * @return  {mixed}
 */

SubblyCore.prototype.getConfig = function( path, defaults )
{
  return Helpers.getNested( this._config, path, defaults || false ) 
}

/*
 * Set config value
 *
 * @return  {void}
 */

SubblyCore.prototype.setConfig = function( path, value )
{
  return Helpers.setNested( this._config, path, value ) 
}

/*
 * Set user credentials
 *
 * @return  {void}
 */

SubblyCore.prototype.setCredentials = function( credentials )
{
  this._credentials = credentials

  // not safe at all
  // TODO: find a client side crypto lib
  document.cookie = this._credentialsCookie + '=' + JSON.stringify( this._credentials ) + '; path=/'

  this.event.trigger('user::loggedIn')

  this.event.trigger( 'hash::change', 'customers' )
}

/*
 * Return current user credentials object
 *
 * @return  {object}
 */

SubblyCore.prototype.getCredentials = function()
{
  if( !this._credentials )
    throw new Error( 'User credentials are not set' )

  return this._credentials
}

/*
 * Check if current user is logged in
 *
 * @return  {void}
 */

SubblyCore.prototype.isLogin = function()
{
  if( !document.cookie )
  {
    this.event.trigger( 'hash::change', 'login' )
    return
  }

  // retrive credentials from cookies
  var regexp      = new RegExp("(?:^" + this._credentialsCookie + "|;\s*"+ this._credentialsCookie + ")=(.*?)(?:;|$)", 'g')
    , result      = regexp.exec( document.cookie )
    , credentials = result = ( result === null ) ? null : JSON.parse( result[1] )

  if( _.isNull( credentials ) )
  {
    this.event.trigger( 'hash::change', 'login' )
    return
  }

  this.setCredentials( credentials )
}

/*
 * Unset user credentials
 * Trigger logout event
 *
 * @return  {void}
 */

SubblyCore.prototype.logout = function()
{
  this._credentials = false

  document.cookie = this._credentialsCookie + '=' + null + '; path=/'

  this.event.trigger( 'hash::change', 'login' )
}


/*
 * Format full URL to API service
 *
 * @params  {string}  service
 * @return  {string}
 */

SubblyCore.prototype.apiUrl = function( url )
{
  return this._config.apiUrl + url
}

/*
 * Call service protected by closure
 *
 * @params  {string}  service name
 * @return  {mixed}
 */

SubblyCore.prototype.api = function( serviceName, args )
{
  var service = Helpers.getNested( Components, serviceName, false )
    , args    = args || {}

  if( !service )
    throw new Error( 'Subbly API do not include ' + serviceName )

  // if it's a backbone element (model, collection, view)
  // we create a new instance
  if( _.isFunction( service ) )
    return new service( args )

  return service
}


/*
 * Extend Subbly Components
 *
 * @params  {string}  component type
 * @params  {string}  component name
 * @params  {object}  component
 * @return  {string}
 */

SubblyCore.prototype.extend = function( type, name, obj )
{
  Components[ type ][ name ] = SubblyController.extend( obj )
}

// Global Init

subbly = new SubblyCore( subblyConfig )

window.Subbly = subbly


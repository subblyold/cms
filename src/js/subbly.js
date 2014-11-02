
var SubblyCore = function( config )
{
  console.info('init Subbly object', config)

  // enviroment config
  this._config          = config

  // current user model
  this._user            = false
  this._changesAreSaved = true

  // current user credentials
  // this._credentials = false
  this._credentials = {
      username: 'michael@scenedata.com',
      password: 'michael'
  }

  // Pub/Sub channel
  this.event  = _.extend( {}, Backbone.Events )
}


/*
 * Initialize App router
 *
 * @return  void
 */

SubblyCore.prototype.init = function()
{
  this._router = new Router()

  var scope = this

  this.event.on( 'hash::change', function( href )
  {
console.info('hash changed: ' + href )
    if( scope._changesAreSaved )
    {
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
  
  this.event.trigger( 'hash::change', 'login' )
return
  var isLogged = new xhrCall({
      url:       'auth/test-credentials'
    , onSuccess: function( response )
      {
console.log( response )
      }
    , onError:   function( response )
      {
        var route = scope._router.setRoute( 'login' )

console.log( route )
        scope.event.trigger( 'hash::change', 'login' )
      }
  })
}

/*
 * Get config value
 *
 * @return  mixed
 */

SubblyCore.prototype.getConfig = function( path, defaults )
{
  return Helpers.getNested( this._config, path, defaults || false ) 
}

/*
 * Set config value
 *
 * @return  void
 */

SubblyCore.prototype.setConfig = function( path, value )
{
  return Helpers.setNested( this._config, path, value ) 
}

/*
 * Set user credentials
 *
 * @return  void
 */

SubblyCore.prototype.setCredentials = function( credentials )
{
  this._credentials = credentials
}

/*
 * Format full URL to API service
 *
 * @params  string  service
 * @return  string
 */

SubblyCore.prototype.apiUrl = function( url )
{
  return this._config.apiUrl + url
}

/*
 * Call service protected by closure
 *
 * @params  string  service name
 * @return  mixed
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
 * Return current user credentials object
 *
 * @return  object
 */

SubblyCore.prototype.getCredentials = function()
{
  if( !this._credentials )
    throw new Error( 'User credentials are not set' )

  return this._credentials
}


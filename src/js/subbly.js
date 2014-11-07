
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
  this._event  = _.extend( {}, Backbone.Events )

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

  this.on( 'hash::change', function( href )
  {
    if( scope._changesAreSaved )
    {
      scope.trigger( 'hash::changed', href )
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


// CONFIG
//-------------------------------


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


// EVENTS
//-------------------------------


/*
 * Bind an event to a `callback` function. Passing `"all"` will bind
 * the callback to all events fired.
 *
 * @return  {void}
 */

SubblyCore.prototype.on = function( name, callback, context )
{
  this._event.on( name, callback, context )
}

/*
 * Bind an event to only be triggered a single time. After the first time
 * the callback is invoked, it will be removed.
 *
 * @return  {void}
 */

SubblyCore.prototype.once = function( name, callback, context )
{
  this._event.once( name, callback, context )
}

/*
 * Remove one or many callbacks. If `context` is null, removes all
 * callbacks with that function. If `callback` is null, removes all
 * callbacks for the event. If `name` is null, removes all bound
 * callbacks for all events.
 *
 * @return  {void}
 */

SubblyCore.prototype.off = function( name, callback, context )
{
  this._event.off( name, callback, context )
}

/*
 * Trigger one or many events, firing all bound callbacks. Callbacks are
 * passed the same arguments as `trigger` is, apart from the event name
 * (unless you're listening on `"all"`, which will cause your callback to
 * receive the true name of the event as the first argument).
 *
 * @return  {void}
 */

// TODO: args1... ho my god ! need to find a better solution
SubblyCore.prototype.trigger = function( args1, args2, args3, args4, args5, args6, args7 )
{
  // var args = [].slice.call( arguments, 1 )
// console.log( arguments )
//   this._event.trigger.apply( this, arguments )
  this._event.trigger( args1, args2, args3, args4, args5, args6, args7 )
}


// CREDENTIALS / LOGIN
//-------------------------------


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

  this.trigger('user::loggedIn')

  // TODO: do not trigger `dashboard` if URL set
  this.trigger( 'hash::change', 'dashboard' )
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
    this.trigger( 'hash::change', 'login' )
    return
  }

  // retrive credentials from cookies
  var regexp      = new RegExp("(?:^" + this._credentialsCookie + "|;\s*"+ this._credentialsCookie + ")=(.*?)(?:;|$)", 'g')
    , result      = regexp.exec( document.cookie )
    , credentials = result = ( result === null ) ? null : JSON.parse( result[1] )

  if( _.isNull( credentials ) )
  {
    this.trigger( 'hash::change', 'login' )
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

  this.trigger( 'hash::change', 'login' )
}


// API
//-------------------------------


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
// console.log( service, Components, serviceName )

  if( !service )
    throw new Error( 'Subbly API do not include ' + serviceName )

  // if it's a backbone element (model, collection, view)
  // we create a new instance
  if( _.isFunction( service ) )
    return new service( args )

  return service
}


// PLUGINS
//-------------------------------


/*
 * Extend Subbly Components
 *
 * @params  {string}  component type
 * @params  {string}  component name
 * @params  {object}  component object
 * @return  {void}
 */

SubblyCore.prototype.extend = function( vendor, type, name, obj )
{
  var allowedType = [ 'Model', 'Collection', 'View', 'Controller' ]

  if( allowedType.indexOf( type ) == -1 )
    throw new Error( 'Extend can not accept "' + type + '" as extend' )

  if( !Components[ vendor ] )
  {
    // TODO: build obj dynamically
    Components[ vendor ] =
    {
        Model:      {}
      , Collection: {}
      , View:       {}
      , Supervisor: {}
      , Controller: {}
      , Component:  {}
    }
  }

  if( Components[ vendor ][ type ][ name ] )
  {
    // TODO: extend existing components + log 
    throw new Error( vendor + '.'  + type + '.'  + name + ' already exist' )
  }

  var alias

  switch( type )
  {
    case 'Controller':
        alias = SubblyController
      break
    case 'View':
        alias = SubblyView
      break
    case 'Model':
        alias = SubblyModel
      break
    case 'Collection':
        alias = SubblyCollection
      break
  }

  Components[ vendor ][ type ][ name ] = alias.extend( obj )
}

/*
 * Register Plugin
 *
 * @params  {object}  plugin object
 * @return  {void}
 */

SubblyCore.prototype.register = function( vendor, name, plugin )
{
  _.each( plugin, function( component, typeName )
  {
    var arr = typeName.split(':')

    this.extend( vendor, arr[0], arr[1], component )
  }, this )
}

// Global Init

subbly = new SubblyCore( subblyConfig )

window.Subbly = subbly



var SubblyCore = function( config )
{
  console.info( 'Init Subbly object with this config:' )
  console.log( config )

  // enviroment config
  this._config            = config

  // form change's flag
  this._changesAreSaved   = true

  // current user model
  this._user              = false
  
  // user credentials cookie's name
  this._credentialsCookie = 'SubblyCredentials'

  // current user credentials
  this._credentials       = false

  // XHR call reference
  this._fetchXhr          = {}

  // Feedback
  this._feedback = new Feedback()

  // Pub/Sub channel
  this._event  = _.extend( {}, Backbone.Events )

  this._event.on( 'user::loggedIn', this.setCredentials, this )
  this._event.on( 'user::logout',   this.logout,         this )
  // this._event.on( 'feedback::add',  this.feedback,       this )
  // this._event.on( 'feedback::done', this.feedbackDone,   this )
  this._event.on( 'loader::progressEnd', this._feedback.progressEnd  )

  this._viewAllowedType = [ 
      'Model'
    , 'Collection'
    , 'View'
    , 'ViewForm'
    , 'ViewList'
    , 'ViewListRow'
    , 'Controller' 
  ]

  return this
}


/*
 * Initialize App router
 *
 * @return  {void}
 */

SubblyCore.prototype.init = function()
{
  console.info( 'Initialize App router' )
  console.info( 'Components list' )
  console.log( Components )

  this._router = new Router()

  var scope = this

  this.on( 'hash::change', function( href, trigger )
  {
    trigger = ( _.isUndefined( trigger ) ) ? true : trigger

    var opts = ( trigger ) 
               ? { trigger: true }
               : { replace: true } 

    if( scope._changesAreSaved )
    {
      scope._feedback.add().progress()
      scope._router.navigate( href, opts )
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
      //       scope.trigger( 'form::reset' )
      //       AppRouter.navigate( href, opts )
      //     }
      // })
    }
  })

  if( !this.isLogin() )
  {
    console.info( 'trigger login view' )
    this.trigger( 'hash::change', 'login' )
  }
  else
  {
    console.info( 'user is logged in' )
  }
  
  console.info( 'release app router' )
  console.groupEnd()

  var scope = this
  
  this._router.ready(function( route )
  {
    scope.trigger( 'hash::changed', route )
  })
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

/*
 * Trigger loader
 *
 * @return  {void}
 */

SubblyCore.prototype.feedback = function()
{
  return this._feedback
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
  console.info('Set user credentials')

  this._credentials = credentials

  this.setCookie( this._credentialsCookie, this._credentials )
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
  var credentials = this.getCookie( this._credentialsCookie )

  if( !credentials )
    return false
  
  this.trigger( 'user::loggedIn', credentials )

  return true
}

/*
 * Unset user credentials
 * Trigger logout event
 *
 * @return  {void}
 */

SubblyCore.prototype.logout = function()
{
  console.info( 'user logout' )

  this._credentials = false

  this.setCookie( this._credentialsCookie, null )

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

/*
 * Generic method to fetch model/collection
 * stores the XHR call which allows the abort on route change
 * trigger local loading event
 *
 * @params  {object}  object to fetch
 * @params  {object}  data and callbacks options
 * @params  {object}  call context
 * @return  {object}
 */
SubblyCore.prototype.fetch = function( obj, options, context )
{
  var _feedback = this._feedback
    , options   = options || {}
    , xhrId     = _.uniqueId( 'xhr_' )
    , cbShared  = function()
      {
        _feedback.progressEnd()

        if( context )
          context.trigger( 'fetch::responds' )
      }

  if( context )
    context.trigger( 'fetch::calling' )
  
  _feedback.add().progress()

  this._fetchXhr[ xhrId ] = obj.fetch({
      data:    options.data || {} 
    , xhrId:   xhrId
    , success: function( bbObj, response, opts )
      {
        cbShared()

        if( options.success && _.isFunction( options.success ) )
          options.success( bbObj, response, opts )
      }
    , error: function( bbObj, response, opts )
      {
        cbShared()
        
        if( options.error && _.isFunction( options.error ) )
          options.error( bbObj, response, opts  )
      }
  })
}

/*
 * Generic method to save a model
 * format json
 * stores the XHR call to prevent cancel
 *
 * @params  {object}  model to save
 * @params  {object}  data to set
 * @params  {object}  callbacks options
 * @params  {object}  call context
 * @return  {object}
 */
SubblyCore.prototype.store = function( model, data, options, context )
{
  var data    = data || false
    , options = options || {}
    , scope   = this

  if( !data )
    throw new Error( 'No data pass to Subbly.store' )

  options.json = {}

  options.json[ model.singleResult ] = data 

  var _feedback = this.feedback()
  
  _feedback.add().progress()

  model.save( options.json, 
  {
      success: function( model, response, opts )
      {
        _feedback.progressEnd( 'success', options.successMsg || response.headers.status.message )

        if( options.success && _.isFunction( options.success ) )
          options.success( model, response, opts )
      }
    , error: function( model, response, opts )
      {
        _feedback.progressEnd( 'error', options.errorMsg || response.responseJSON.headers.status.message )

        if( options.error && _.isFunction( options.error ) )
          options.error( model, response, opts  )
      }
  })
}

/*
 * Abort unfinish XHR call on route change
 *
 * @return  {void}
 */
SubblyCore.prototype.cleanXhr = function()
{
  _( this._fetchXhr )
    .forEach( function( xhr, key )
    {
      if( xhr.readyState > 0 && xhr.readyState < 4 )
        xhr.abort()

      delete this._fetchXhr[ key ]
    }, this)
}


// PLUGINS
//-------------------------------


/*
 * get Cookie
 *
 * @return  {mixed}
 */

SubblyCore.prototype.getCookie = function( cookieName )
{
  console.groupCollapsed( 'get cookie: ' + cookieName )

  if( !document.cookie )
  {
    console.warn('no cookie for this domain')
    console.groupEnd()
    return false
  }

  // retrive data from cookies
  var data = decodeURIComponent( document.cookie.replace( new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent( cookieName ).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null
  
  console.log( document.cookie )
  console.log( data )
  
  if( data === 'null' )
  {
    console.warn('cookie "' + cookieName + '" found but data are null')
    console.groupEnd()

    return false
  }

  console.groupEnd()
  return data
}

/*
 * set Cookie
 *
 * @return  {void}
 */

SubblyCore.prototype.setCookie = function( cookieName, value, expire )
{
  document.cookie = cookieName + '=' + value + '; path=/'
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
  if( this._viewAllowedType.indexOf( type ) == -1 )
    throw new Error( 'Extend can not accept "' + type + '" as extend' )

  // create a new Vendor
  if( !Components[ vendor ] )
    Components[ vendor ] = defautlsFwObj()

  var alias

  switch( type )
  {
    case 'Controller':
        alias = SubblyController
      break
    case 'View':
        alias = SubblyView
      break
    case 'ViewForm':
        type  = 'View' 
        alias = SubblyViewForm
      break
    case 'ViewList':
        type  = 'View' 
        alias = SubblyViewList
      break
    case 'ViewListRow':
        type  = 'View' 
        alias = SubblyViewListRow
      break
    case 'Model':
        alias = SubblyModel
      break
    case 'Collection':
        alias = SubblyCollection
      break
    case 'CollectionList':
        alias = SubblyCollectionList
      break
  }

  if( Components[ vendor ][ type ][ name ] )
  {
    // TODO: extend existing components + log 
    throw new Error( vendor + '.'  + type + '.'  + name + ' already exist' )
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
  console.groupCollapsed( 'Register Plugin ' + vendor + '.' + name )

  _.each( plugin, function( component, typeName )
  {
    var arr = typeName.split(':')

    this.extend( vendor, arr[0], arr[1], component )
  }, this )

  console.groupEnd()
}

// Global Init

console.groupCollapsed( 'Subbly Global Init' )
// console.group( 'Subbly Global Init' )

subbly = new SubblyCore( subblyConfig )

window.Subbly = subbly


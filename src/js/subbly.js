
var SubblyCore = function( config )
{
  console.info('init Subbly object', config)

  // enviroment config
  this._config      = config

  // current user model
  this._user        = false

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
 * Format full URL to API service
 *
 * @params  string  service
 * @return  string
 */

SubblyCore.prototype.apiUrl = function( url )
{
  return this._config.apiUrl + '/' + url
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

  return new service( args )
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


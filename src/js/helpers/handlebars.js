
// Handlebars Helpers

// Handlebars.registerHelper( 'i18n', function( str )
// {
//   // wrap `__` function because Handlebars
//   // doesn't have access to App variables scope
//   return __( str )
// })

// Select/Checkbox/Radio button options helper
// {{isSelected this default="string or reference" attribute="selected"}}
Handlebars.registerHelper('isSelected', function( value, options )
{
  if( _.isUndefined( options.hash ) )
    return

  if( _.isUndefined( options.hash.default ) )
    return

  if( _.isArray( value ) )
  {
    if( $.inArray( options.hash.default, value ) !== -1 )
      return ' ' + options.hash.attribute
  }
  else
  {
    if( value === options.hash.default )
      return ' ' + options.hash.attribute
  }

  return
})

Handlebars.registerHelper('isEmpty', function( value )
{
  return _.isEmpty( value )
})

Handlebars.registerHelper('isNotEmpty', function( value, options )
{
  if( !_.isEmpty( value ) )
  {
     return options.hash.echo
  }
})

Handlebars.registerHelper('formatNumber', function( int )
{
  if( _.isUndefined( int ) )
    return

  var str = int.toString()
  
  return str.replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
})

Handlebars.registerHelper('bytesToSize', function( bytes )
{
  if( _.isUndefined( bytes ) )
    return '0 MB'

  var sizes = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']

  if(bytes == 0) return 'n/a'

  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)))

  if (i == 0)
  {
    return (bytes / Math.pow(1024, i)) + ' ' + sizes[i] 
  }

  return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i]
})

Handlebars.registerHelper('thumbnail', function( id )
{
  var url = BASE_URL + APP_THUMB
  
  return url.replace( /:app_slug/, Tapioca.currentApp ).replace( /:file_id/, id ) + '?' +_.now()
})

Handlebars.registerHelper('truncate', function( fullStr, strLen, separator )
{
  try
  {
    if( fullStr.length <= strLen )
      return fullStr        
  }
  catch( e )
  {
    console.error( e )
    return ''
  }

  separator = separator || '...'

  var sepLen      = separator.length
    , charsToShow = ( strLen - sepLen )
    , frontChars  = Math.ceil( charsToShow / 2 )
    , backChars   = Math.floor( charsToShow / 2 )

  return fullStr.substr( 0, frontChars ) + 
          separator + 
          fullStr.substr( fullStr.length - backChars )
})


Handlebars.registerHelper('secondsToHms', function( d )
{
  d = Number(d)
  var h = Math.floor(d / 3600)
  var m = Math.floor(d % 3600 / 60)
  var s = Math.floor(d % 3600 % 60)
  return ((h > 0 ? h + ':' : '') + (m > 0 ? (h > 0 && m < 10 ? '0' : '') + m + ':' : '0:') + (s < 10 ? '0' : '') + s) + ' min'
})

Handlebars.registerHelper('now', function()
{
  return _.now()
})


// HELPER: #keyValue
// https://gist.github.com/strathmeyer/1371586
// Usage: {{#keyValue obj}} Key: {{key}} // Value: {{value}} {{/keyValue}}
//
// Iterate over an object, setting 'key' and 'value' for each property in
// the object.
Handlebars.registerHelper('keyValue', function( obj, hash ) 
{
  var buffer = ''
    , key

  for (key in obj)
  {
      if (obj.hasOwnProperty(key))
      {
          buffer += hash.fn({key: key, value: obj[key]})
      }
  }
  
  return buffer
})

Handlebars.registerHelper('nl2br', function(text)
{
  text = Handlebars.Utils.escapeExpression(text)
  var nl2br = (text + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br>' + '$2')
  return new Handlebars.SafeString(nl2br)
})

// http://stackoverflow.com/a/9405113
Handlebars.registerHelper('ifCond', function(v1, v2, options) 
{
  if( v1 === v2 ) 
  {
      return options.fn( this )
  }

  return options.inverse( this )
})

Handlebars.registerHelper('compare', function( v1, op, v2, options ) 
{
  var c = 
  {
      'eq': function( v1, v2 ) 
      {
        return v1 == v2
      }
    , 'neq': function( v1, v2 ) 
      {
        return v1 != v2
      }
  }

  if( Object.prototype.hasOwnProperty.call( c, op ) ) 
  {
    return c[ op ].call( this, v1, v2 ) ? options.fn( this ) : options.inverse( this )
  }

  return options.inverse( this )
})

Handlebars.registerHelper('capitalize', function( str ) 
{
  return _.capitalize( str )
})

Handlebars.registerHelper('checked', function ( currentValue )
{
  return currentValue == true ? ' checked ' : ''
})

Handlebars.registerHelper('json', function( obj ) 
{
  return JSON.stringify( obj, undefined, 4)
})

Handlebars.registerHelper('indexPlusOne', function( str ) 
{
  return (( +str ) + 1 )
})

Handlebars.registerHelper('userAddress', function( obj ) 
{
  var str = [
      // obj.lastname + ' ' + obj.firstname + '<br>'
      obj.address1 + '<br>'
  ]

  if( !_.isNull( obj.address2 ) )
    str.push( obj.address2 + '<br>' )

  str.push( obj.zipcode + ' ' + obj.city + '<br>' )
  str.push( obj.country )

  return str.join('')
})
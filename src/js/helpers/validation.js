/*
 * validation inspired by validate.js by Rick Harrison, http://rickharrison.me
 * validate.js is open sourced under the MIT license.
 * Portions of validate.js are inspired by CodeIgniter.
 */

var Validation = {

    // Define the regular expressions that will be used
    // -------------------------------------------------------

    ruleRegex:         /^(.+)\[(.+)\]$/
  , numericRegex:      /^[0-9]+$/
  , integerRegex:      /^\-?[0-9]+$/
  , decimalRegex:      /^\-?[0-9]*\.?[0-9]+$/
  , emailRegex:        /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i
  , alphaRegex:        /^[a-z]+$/i
  , alphaNumericRegex: /^[a-z0-9]+$/i
  , alphaDashRegex:    /^[a-z0-9_-]+$/i

    // @private
    // Object containing all of the validation hooks        
  , _hooks:
    {
      required: function( field ) 
      {
        var value = field.value
    
        return ( value !== false && value !== null && value !== undefined && value !== '' )
      }

    , matches: function( field, matchName )
      {
        var el = this.form.element.elements[ matchName ]

        if( el )
          return field.value === el.value

        return false
      }

    , valid_email: function( field )
      {
        return Validation.emailRegex.test( field.value )
      }

    , min_length: function( field, length )
      {
        if( !Validation.numericRegex.test( length ) )
        {
            return false
        }
        
        return ( (''+field.value).length >= length )
      }

    , max_length: function( field, length )
      {
        if( !Validation.numericRegex.test( length ) )
        {
            return false
        }
        
        return ( (''+field.value).length <= length )
      }

    , exact_length: function( field, length )
      {
        if( !Validation.numericRegex.test( length ) )
        {
            return false
        }
        
        return ( (''+field.value).length == length )
      }

    , greater_than: function( field, param )
      {
        if( !Validation.decimalRegex.test( field.value ) )
        {
            return false
        }

        return ( parseFloat( field.value ) > parseFloat( param ) )
      }

    , less_than: function( field, param )
      {
        if( !Validation.decimalRegex.test( field.value ) )
        {
            return false
        }
        
        return ( parseFloat( field.value ) < parseFloat( param ) )
      }

    , alpha: function( field )
      {
        return ( Validation.alphaRegex.test( field.value ) )
      }

    , alpha_numeric: function( field )
      {
        return ( Validation.alphaNumericRegex.test( field.value ) )
      }

    , alpha_dash: function( field )
      {
        return ( Validation.alphaDashRegex.test( field.value ) )
      }

    , numeric: function( field )
      {
        return ( Validation.decimalRegex.test( field.value ) )
      }

    , integer: function( field )
      {
        return ( Validation.integerRegex.test( field.value ) )
      }
    }
}

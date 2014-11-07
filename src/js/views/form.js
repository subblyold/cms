
Components.Subbly.View.FormView = Backbone.View.extend(
{
    form:             false
  , $formInputs:      false

  , events: {
        'click button[type="submit"]'                        : 'submit'
      , 'click button[type="reset"]'                         : 'cancel'
      , 'keypress :input:not(textarea,[data-bypass="true"])' : 'onEnter'
      , 'change :input'                                      : 'removeWarning'
    }

    // usefull to override event's method
    // ---------------------------------------
    
  , _void: function()
    {
      return
    }

    // events related methods
    // -------------------------

  , onEnter: function( event )
    {
      this.removeWarning( event )

      subbly.trigger( 'form::changed' )

      if (event.keyCode != 13) 
          return

      // prevent bubbling
      event.stopPropagation()
      event.preventDefault()

      this.submit( event )
    }

  , cancel: function()
    {
      if( this.onCancel )
      {
        this.onCancel()
      }

      subbly.trigger( 'form::reset' )
    }

  , removeWarning: function( event )
    {
      event.target.classList.remove('warning')
    }

    // Form definition
    // -------------------------

  , setForm: function( options )
    {
      options = options || {}

      if( _.isUndefined( options.id ) )
        return

      this.form = {
          id:       options.id
        , element:  document.getElementById( options.id )
        , data:     options.data      || {}
        , extra:    options.extra     || {}
        , rules:    options.rules     || []
        , skip:     ( _.isUndefined( options.skip ) ) ? true  : options.skip      // ignore blank fields 
      }

      this.form.$el = $( this.form.element )
    }

  , setRules: function( rules )
    {
      if( this.form )
        this.form.rules = rules
    }
    
  , setExtra: function( key, value, init )
    {
      Helpers.setNested( this.form.extra, key, value )
    }

  , getFormValue: function( key, defaults )
    {
      defaults = defaults || false

      // if( _.isUndefined( key ) )
      //   throw new Error( 'getFormValue need a key' )

      if( !this.form.data[ key ] )
        return defaults

      return this.form.data[ key ]
    }

  , validateForm: function()
    {
      if( !this.form )
        return

      this.$formInputs = this.form.$el.find(':input[name]')

      this.$formInputs.removeClass('warning')

      var formData = this.$formInputs.serializeObject( this.form.skip )

      this.form.data = Helpers.deepMerge( formData, this.form.extra )

      this.errors = []
  
      for( var key in this.form.rules )
      {
        this._validateField( this.form.rules[ key ] )
      }

      if (this.errors.length > 0)
      {
        this.displayErrors()
        return false
      }

      return true
    }

    // validation
    // -------------

  , displayErrors: function()
    {
      console.info('displayErrors', this.errors )

      for( var i = -1, l = this.errors.length; ++i < l; )
      {
        var $input = $( this.errors[ i ].element )

        $input.addClass('warning')
      }
    }

  , addRules: function(fields)
    {
      if( this.form )
      {
        for (var i = -1, l = fields.length; ++i < l;) 
        {
          var field = fields[ i ]

          this.form.rules.push({
              name:    field.name
            , rules:   field.rules
            , value:   null
          })
        }
      }

      return this
    }

    // @private
    // Looks at the fields value and evaluates it against the given rules
  , _validateField: function( field )
    {
      var rules = field.rules.split('|')

      field.value = Helpers.getNested( this.form.data, field.name )

      // If the value is null and not required, we don't need to run through validation             
      if( field.rules.indexOf('required') === -1 
          && ( !field.value || field.value === '' || field.value === undefined ) )
          return

      // Run through the rules and execute the validation methods as needed
      for( var i = -1, ruleLength = rules.length; ++i < ruleLength; )
      {
        var method = rules[i]
          , param  = null
          , failed = false
          , parts

        // If the rule has a parameter (i.e. matches[param]) split it out
        if( parts = Validation.ruleRegex.exec( method ) )
            method = parts[1]
          , param  = parts[2]

        // If the hook is defined, run it to find any validation errors                
        if( typeof Validation._hooks[ method ] === 'function' )
        {
          if( !Validation._hooks[ method ].apply( this, [ field, param ] ) )
          {
            failed = true
          }
        }
        else if( method.substring(0, 9) === 'callback_' )
        {
          // Custom method. Execute the handler if it was registered
          method = method.substring( 9, method.length );
          
          if( typeof this.handlers[ method ] === 'function' )
          {
            if( this.handlers[ method ].apply( this, [ field.value ] ) === false )
            {
              failed = true
            }
          }
        }
        
        // If the hook failed, add a message to the errors array                 
        if( failed )
        {
          var args = null

          // ( this.fields[param] ) ? this.fields[param].display : 
          if( param )
            args = param
          
          var _obj = {
                  field:   field
                , element: this.form.element.querySelector('[name="'+ field.name + '"]')
              }
          
          this.errors.push( _obj )

          // Break out so as to not spam with validation errors (i.e. required and valid_email)
          break
        }
      }
    }
})

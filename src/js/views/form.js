var SubblyViewForm

Components.Subbly.View.FormView = SubblyViewForm = SubblyView.extend(
{
    _form:            false
  , $formInputs:      false

  , initialize: function( options )
    {
      // Call parent `initialize` method
      SubblyView.prototype.initialize.apply( this, arguments )

      // add view's event
      this.addEvents( {
          'click .js-submit-form'                        : 'submit'
        , 'click .js-cancel-form'                        : 'cancel'
        , 'keypress :input:not(textarea,[data-bypass="true"])' : 'onEnter'
        , 'change :input'                                      : 'removeWarning'
      })
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

      this._form = {
          id:       options.id
        , element:  document.getElementById( options.id )
        , data:     options.data      || {}
        , extra:    options.extra     || {}
        , rules:    options.rules     || []
        , skip:     ( _.isUndefined( options.skip ) ) ? true  : options.skip      // ignore blank fields 
      }

      this._form.$el = $( this._form.element )
    }

  , setRules: function( rules )
    {
      if( this._form )
        this._form.rules = rules
    }
    
  , setExtra: function( key, value, init )
    {
      Helpers.setNested( this._form.extra, key, value )
    }


  , getFormValues: function()
    {
      return this._form.data
    }

  , getFormValue: function( key, defaults )
    {
      defaults = defaults || false

      // if( _.isUndefined( key ) )
      //   throw new Error( 'getFormValue need a key' )

      if( !this._form.data[ key ] )
        return defaults

      return this._form.data[ key ]
    }

  , submit: function( event )
    {
      if( !_.isUndefined( event ) )
          event.preventDefault()

      if( this.validateForm() && this.onSubmit )
        this.onSubmit()
    }


  , validateForm: function()
    {
      if( !this._form )
        return

      this.$formInputs = this._form.$el.find(':input[name]')

      this.$formInputs.removeClass('warning')

      var formData = this.$formInputs.serializeObject( this._form.skip )

      this._form.data = Helpers.deepMerge( formData, this._form.extra )

      this.errors = []
  
      for( var key in this._form.rules )
      {
        this._validateField( this._form.rules[ key ] )
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
      if( this._form )
      {
        for (var i = -1, l = fields.length; ++i < l;) 
        {
          var field = fields[ i ]

          this._form.rules.push({
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

      field.value = Helpers.getNested( this._form.data, field.name )

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
                , element: this._form.element.querySelector('[name="'+ field.name + '"]')
              }
          
          this.errors.push( _obj )

          // Break out so as to not spam with validation errors (i.e. required and valid_email)
          break
        }
      }
    }
})

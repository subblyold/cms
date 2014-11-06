
Components.Subbly.View.Login = Components.Subbly.View.FormView.extend(
{
    el: '#login'

  , initialize: function()
    {
      var rules = [
          {
              display: ''
            , name:    'password'
            , rules:   'required'
          }
        , {
              display: ''
            , name:    'email'
            , rules:   'required|valid_email'
          }
      ]

      this.$btn = $( document.getElementById('login-submit') )

      // !! always set form after html render
      this.setForm({
          id:       'login-form'
        , rules:    rules
        , autoSave: false
        , skip:     false
      })

      subbly.on( 'user::loggedIn', function()
      {
        this.hide()
        this.form.$el.reset()
        this.btnReset()

      }, this)

      return this
    }

  , display: function()
    {
      var login = this

      window.setTimeout(function()
      {
        login.$el
          .show()
          .removeClass('logged')
          .addClass('active')

        document.getElementById('login-email').focus()
        
        subbly.trigger( 'loader::hide' )
      }, 500)
    }

  , hide: function()
    {
      var login = this

      this.$el
        .one( transitionEnd, function()
        {
          login.$el.hide()
        })
        .addClass('logged')
    }

  , btnReset: function()
    {
      this.$btn.button('reset')
    }

  , submit: function( event )
    {
      this.$btn.button('loading')

      if( !_.isUndefined( event ) )
          event.preventDefault()

      if( this.validateForm() )
      {
        var credentials  = 
            {
                username: this.form.data.email
              , password: this.form.data.password 
            }
          , encode       = window.btoa( unescape( encodeURIComponent( [ this.getFormValue( 'email' ), this.getFormValue( 'password' ) ].join(':') ) ) )
          , login        = this
          , authenticate = new xhrCall(
            {
                url:       'auth/test-credentials'
              , headers: {
                  Authorization: 'Basic ' + encode
                }
              , success: function( response )
                {
                  subbly.setCredentials( credentials )
                }
              , error:   function( jqXHR, textStatus, errorThrown )
                {
                  $( document.getElementById('login-msg') ).text( jqXHR.responseJSON.response.error )

                  login.$formInputs.addClass('warning')
                  document.getElementById('login-email').focus()
                  login.btnReset()
                }
            })
        
        return
      }

      this.btnReset()
    }
})

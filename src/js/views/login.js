
Components.Subbly.View.Login = SubblyViewForm.extend(
{
    el: '#login'

  , onInitialize: function()
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
        this._form.$el.reset()
        this.btnReset()

      }, this)
    }

  , display: function()
    {
      // do not display form to logged users
      try
      {
        // if `getCredentials` didn't throws errors
        // that means that user is already logged in
        subbly.getCredentials()
        // so we send him to default controller
        subbly.trigger( 'hash::change', '' )
        // and stop this action to go on
        return
      }
      catch( e ){}

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
        .one( transitionEndEventName, function()
        {
          login
            .$el
            .removeClass('active')
            .removeClass('logged')
            .hide()
        })
        .addClass('logged')
    }

  , btnReset: function()
    {
      this.$btn.button('reset')
    }

  , onSubmit: function()
    {
      this.$btn.button('loading')

      var credentials  = 
          {
              username: this.getFormValue( 'email' )
            , password: this.getFormValue( 'password' )
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
                subbly.trigger( 'user::loggedIn', encode )
              }
            , error:   function( jqXHR, textStatus, errorThrown )
              {
                $( document.getElementById('login-msg') ).text( jqXHR.responseJSON.response.error )

                login.$formInputs.addClass('warning')
                document.getElementById('login-email').focus()
                login.btnReset()
              }
          })
        
      //   return
      // }

      // this.btnReset()
    }
})


Views.Login = Views.FormView.extend(
{
    el: '#tapioca-login'

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

      this.$btn = $('#login-submit')

      // !! always set form after html render
      this.setForm({
          id:       'tapioca-login'
        , rules:    rules
        , autoSave: false
        , skip:     false
      })

      var login = this

      Pubsub.on( 'view::app', function()
      {
        login.$el.reset()
        login.btnReset()
      })

      document.getElementById('login-email').focus()
      
      Pubsub.trigger( 'loader::hide' )

      return this
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
        Pubsub.trigger( 'form::reset' )
        
        var url   = API_URL + 'oauth' 
          , jqxhr = $.post( url, this.form.data )
          , login = this

        jqxhr.success( function( obj )
        {
          App.session.storeToken( obj )
          Pubsub.trigger( 'app::getToken' )
        })

        jqxhr.fail( function( response )
        {
          App.feedback.add( 'error', JSON.parse( response.responseText ).error )
          login.btnReset()
        })
        return
      }
      this.btnReset()
    }
})
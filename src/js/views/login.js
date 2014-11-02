
Components.View.Login = Components.View.FormView.extend(
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

      var login = this

      subbly.event.on( 'view::app', function()
      {
        login.$el.reset()
        login.btnReset()
      })

      return this
    }

  , display: function()
    {
      this.$el.addClass('active')

      document.getElementById('login-email').focus()
      
      subbly.event.trigger( 'loader::hide' )
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

console.log('ok')
return
      if( this.validateForm() )
      {
        subbly.event.trigger( 'form::reset' )
        
        // var url   = API_URL + 'oauth' 
        //   , jqxhr = $.post( url, this.form.data )
        //   , login = this

        // jqxhr.success( function( obj )
        // {
        //   App.session.storeToken( obj )
        //   subbly.event.trigger( 'app::getToken' )
        // })

        // jqxhr.fail( function( response )
        // {
        //   App.feedback.add( 'error', JSON.parse( response.responseText ).error )
        //   login.btnReset()
        // })
        return
      }

      this.btnReset()
    }
})


var Router = Backbone.Router.extend(
{
    _controllers:  {}
  , _viewspointer: {}
  , _currentView:  false
  , _mainNav:      []

  , routes: {
        '':       'default'
      , 'login':  'login'
      , 'logout': 'logout'
    }

  , initialize: function() 
    {
      var controllers = SubblyPlugins.getList()
        , router      = this

      _.each( Components, function( vendorComponents, vendor )
      {
        if( !vendorComponents.Controller )
          return

        _.each( vendorComponents.Controller, function( controller, name )
        {
          this._controllers[ vendor + name ] = new controller( { router: this } )
        }, this )

      }, this )

      this._viewspointer.login   = subbly.api( 'Subbly.View.Login' )
      this._viewspointer.mainNav = subbly.api( 'Subbly.View.MainNav', {
          items: this._mainNav
      })

// console.log( this._mainNav )
  
      Backbone.history.start({
          hashChange: true 
        , pushState:  true 
        , root:       subbly.getConfig( 'baseUrl' )
      })

      subbly.on( 'hash::changed', this.closeCurrent, this )

      return this
    }

    // Methods
    // ----------------------------

  , closeCurrent: function()
    {
      if(
             this._currentView 
          && this._currentView.remove 
        )
        this._currentView.remove()
    }

    // Routes
    // ----------------------------

  , default: function()
    {
      console.info('default controller')
    }      

  , login: function()
    {
      // this._currentView = this._viewspointer.login

      this._viewspointer.login.display()
    }

  , logout: function()
    {
      subbly.logout()
    }

    // Methods
    // ----------------------------

  , registerMainNav: function( navItem )
    {
// console.log( navItem )
      this._mainNav.push( navItem )
    }
})

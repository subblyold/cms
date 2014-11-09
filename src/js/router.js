
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
      , '*path':  'notFound'
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

      Backbone.history.start({
          hashChange: true 
        , pushState:  true 
        , root:       subbly.getConfig( 'baseUrl' )
      })

      Backbone.history.on('route', function( router, route, params )
      {
        subbly.trigger( 'hash::changed', route, params  )
      })

      subbly.on( 'hash::change', this.closeCurrent, this )

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
      {
        console.groupCollapsed( 'Close current view' )
          console.log( this._currentView  )
        console.groupEnd()

        this._currentView.remove()
      }
    }

    // Routes
    // ----------------------------

  , default: function()
    {
      console.warn('call default router')
      subbly.trigger( 'hash::change', 'dashboard' )
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

  , notFound: function( route )
    {
      subbly.trigger( 'hash::notFound', route )
    }

    // Methods
    // ----------------------------

  , registerMainNav: function( navItem )
    {
// console.log( navItem )
      this._mainNav.push( navItem )
    }
})

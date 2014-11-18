
var Router = Backbone.Router.extend(
{
    _controllers:  {}
  , _viewspointer: {}
  , _currentCtr:   false // active controller
  , _mainNav:      []

  , routes: {
        '':       'default'
      , 'login':  'login'
      , 'logout': 'logout'
      , '*path':  'notFound'
    }

  , initialize: function() 
    {
      var router = this

      // Load plugins controllers
      _.each( Components, function( vendorComponents, vendor )
      {
        if( !vendorComponents.Controller )
          return

        _.each( vendorComponents.Controller, function( controller, name )
        {
          this._controllers[ vendor + name ] = new controller( { router: this } )
        }, this )

      }, this )

      // Load buil-in controllers
      this._viewspointer.login   = subbly.api( 'Subbly.View.Login' )
      this._viewspointer.mainNav = subbly.api( 'Subbly.View.MainNav', {
          items: this._mainNav
      })

      // Start router
      Backbone.history.start({
          hashChange: true 
        , pushState:  true 
        , root:       subbly.getConfig( 'baseUrl' )
      })


      // Router events callback
      Backbone.history.on('route', function( router, route, params )
      {
        subbly.trigger( 'hash::changed', route, params  )
      })

      // subbly.on( 'hash::change', this.closeCurrent, this )

      return this
    }

    // Controller Methods
    // ----------------------------

    // Remove active controller
  , closeCurrent: function()
    {
      if(
             this._currentCtr 
          && this._currentCtr.remove 
        )
      {
        console.groupCollapsed( 'Close current controller' )
          
          console.log( this._currentCtr  )
          this._currentCtr.remove()

        console.groupEnd()
      }
    }

    // return the active controller
  , getCurrentController: function()
    {
      return this._currentCtr
    }

    // Keep reference to the active controller
  , setCurrentController: function( ctr )
    {
      this._currentCtr = ctr

      if( 
              ctr._mainNavRegister
          &&  ctr._mainNavRegister.defaultUrl
        )
        subbly.trigger( 'mainnav::hashchanged', ctr._mainNavRegister.defaultUrl )

      return this
    }

    // Test if called controller is not the same
    // as the one already displayed
  , controllersAreTheSame: function( controllerName )
    {
      if( !this._currentCtr )
        return false

      var result = ( this._currentCtr._getName() === controllerName )
      
      if( !result )
        this.closeCurrent()

      return result
    }

    // Built-in Routes
    // ----------------------------

  , default: function()
    {
      console.warn('call default router')
      subbly.trigger( 'hash::change', 'dashboard' )
    }      

  , login: function()
    {
      // this._currentCtr = this._viewspointer.login

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

    // Main Nav Methods
    // ----------------------------

  , registerMainNav: function( navItem )
    {
      var defaults = {
        order: 0
      }
      
      if( navItem.name )
      {
        this._mainNav.push(  $.extend( {}, defaults, navItem ) ) 
      }
    }
})

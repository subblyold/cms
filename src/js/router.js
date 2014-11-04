
var Router = Backbone.Router.extend(
{
    _controllers:  {}
  , _viewspointer: {}
  , _currentView:  false

  , routes: {
        '':       'default'
      , 'login':  'login'
      , 'logout': 'logout'
    }

  , initialize: function() 
    {
      var controllers = SubblyPlugins.getList()
        , router      = this

      _.each( Components.Controller, function( controller, name )
      {
// console.log( name, controller )
        this._controllers[ name ] = new Components.Controller[ name ]( { router: this } )
      }, this )

      // new Components.Controller.Customers( { router: this } )

      this._viewspointer.login = subbly.api('View.Login')
  
      Backbone.history.start({
          hashChange: true 
        , pushState:  true 
        , root:       subbly.getConfig( 'baseUrl' )
      })

      subbly.event.on( 'hash::changed', this.closeCurrent, this )

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
})

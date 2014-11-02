
var Router = Backbone.Router.extend(
{
    controllers:  {}
  , viewspointer: {}

  , routes: {
        '':       'default'
      , 'login':  'login'
      , 'logout': 'logout'
    }

  , initialize: function() 
    {
      var controllers = SubblyPlugins.getList()

      _.each( controllers, function( controller )
      {
        this.controllers[ controller ] = new Components.Controller[ controller ]( { router: this } )
      }, this )


      this.viewspointer.login = subbly.api('View.Login')
  
      Backbone.history.start({
          hashChange: true 
        , pushState:  true 
        , root:       subbly.getConfig( 'baseUrl' )
      })

      return this
    }

  , default: function()
    {
      console.info('default controller')
    }      

  , login: function()
    {
      this.viewspointer.login.display()
      // if( App.mApp )
      // {
      //   Pubsub.trigger( 'hash::change', '' )
      //   Pubsub.trigger( 'loader::hide' )
      //   return
      // }
    }

  , logout: function()
    {
      subbly.logout()
    }
})
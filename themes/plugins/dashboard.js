
(function( window )
{
  var dashboard = 
  {
      _controllerName: 'dashboard'
    , _viewsNames:     ['Dashboard']

    , onInitialize: function()
      {
  console.log( 'onInitialize Dashboard')
      }

    , routes: {
          'dashboard': 'list'
      }

      // Routes
      //-------------------------------

    , list: function() 
      {
        this._mainRouter._currentView = this
        // return
  console.info('call dashboard view')

      }
  }

  var DashboardView = 
  {
      _viewName: 'Dashboard'
  }

  Subbly.register({
      name: 'Dashboard'
    , components: {
          Controller: dashboard
        , View: [
              DashboardView
          ]
      }
  })
})( window )


(function( window )
{
  var dashboard = 
  {
      _controllerName: 'dashboard'
    , _viewsNames:     'Subbly.View.DashboardView'

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

    , onClose: function()
      {
  console.info('close dashboard view')
      }
  }

  Subbly.register('Subbly', 'Dashboard', {
      'View:DashboardView': DashboardView
    , 'Controller:dashboard': dashboard
  })

  // Subbly.register({
  //     name: 'Dashboard'
  //   , components: {
  //         Controller: dashboard
  //       , View: [
  //             DashboardView
  //         ]
  //     }
  // })
})( window )

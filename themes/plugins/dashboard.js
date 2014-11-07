
(function( window )
{
  var dashboard = 
  {
      _controllerName: 'dashboard'
    , _viewsNames:     'Subbly.View.DashboardView'
    , _mainNav:
      {
          name:       'Dashboard'
        , order:      100
        , defaultUrl: 'dashboard'
      }

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

})( window )

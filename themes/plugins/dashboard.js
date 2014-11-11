
(function( window )
{
  var dashboard = 
  {
      _controllerName: 'dashboard'
    , _viewsNames:     'Subbly.View.DashboardView'
    , _mainNavRegister:
      {
          name:       'Dashboard'
        , order:      100
        , defaultUrl: 'dashboard'
      }

    , onInitialize: function()
      {
  // console.log( 'onInitialize Dashboard')
      }

    , routes: {
          'dashboard': 'list'
      }

      // Routes
      //-------------------------------

    , list: function() 
      {
        this.getViewByPath( this._viewsNames ).displayTpl()
      }
  }

  var DashboardView = 
  {
      _viewName: 'Dashboard'
    , _viewTpl:  TPL.dashboard.index

    , display: function()
      {
  console.info('display dashboard view')
      }

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

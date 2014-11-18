
(function( window )
{
  // CONTROLLER
  // --------------------------------

  var HelloWord = 
  {
      _tplStructure:   'full'
    , _viewsNames:     'Vendor.View.HelloWord'
    , _controllerName: 'HelloWord'
    , _listDisplayed:  false
    , _mainNavRegister:
      {
          name:       'HelloWord'
        , order:      0
        , defaultUrl: 'helloworld'
      }

    , routes: {
          'helloworld':      'display'
      }


      // Routes
      //-------------------------------

    , display: function( uid ) 
      {
        this.getViewByPath( 'Vendor.View.HelloWord' )
          .displayTpl()
      }
  }


  // VIEWS
  // --------------------------------
  // Product sheet view
  var HelloWordView = 
  {
      _viewName:     'HelloWord'
    , _viewTpl:      TPL.plugins.helloworld
  }

  // REGISTER PLUGIN
  // --------------------------------


  Subbly.register( 'Vendor', 'HelloWord', 
  {
      'View:HelloWord':       HelloWordView
    , 'Controller:HelloWord': HelloWord
  })

})( window )

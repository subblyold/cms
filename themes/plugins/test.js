
(function( window )
{
  // CONTROLLER
  // --------------------------------

  var test = 
  {
      _tplStructure:   'full'
    , _viewsNames:     'Mike.View.test'
    , _controllerName: 'test'
    , _listDisplayed:  false
    , _mainNavRegister:
      {
          name:       'test'
        , order:      0
        , defaultUrl: 'test'
      }

    , routes: {
          'test': 'display'
      }


      // Routes
      //-------------------------------

    , display: function( uid ) 
      {
        this.getViewByPath( 'Mike.View.test' )
          .displayTpl()
      }
  }


  // VIEWS
  // --------------------------------
  // Product sheet view
  var testView = 
  {
      _viewName:     'test'
    , _viewTpl:      TPL.plugins.helloworld
  }

  // REGISTER PLUGIN
  // --------------------------------


  Subbly.register( 'Mike', 'test', 
  {
      'View:test':       testView
    , 'Controller:test': test
  })

})( window )

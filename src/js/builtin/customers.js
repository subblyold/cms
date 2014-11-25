
  // CONTROLLER
  // --------------------------------

  var Customers = 
  {
      _tplStructure:   'half'
    , _viewsNames:     [ 'Subbly.View.Customers', 'Subbly.View.CustomerSheet' ]
    , _controllerName: 'customers'
    , _listDisplayed:  false
    , _mainNavRegister:
      {
          name:       'Customers'
        , order:      180
        , defaultUrl: 'customers'
      }

    , routes: {
          'customers':      'display'
        , 'customers/:uid': 'display'
      }

    , onRemove: function()
      {
        this._listDisplayed = false
      }

      // Routes
      //-------------------------------

    , display: function( uid ) 
      {
        if( !this._listDisplayed )
        {
          var scope = this

          this.displayView( function()
          {
            if( !_.isNull( uid ) )
              scope.sheet( uid )
          })

          return
        }
        
        if( !_.isNull( uid ) )
          this.sheet( uid )
        else
          this.getViewByPath( 'Subbly.View.Customers' )
            .onInitialRender()
      }

    , displayView: function( sheetCB )
      {
        var scope  = this

        subbly.fetch( subbly.api('Subbly.Collection.Users'),
        {
            data:   {
                offset: 0
              , limit:  10
            }
          , success: function( collection, response )
            {
              scope._listDisplayed = true
              scope.getViewByPath( 'Subbly.View.Customers' )
                .setValue( 'collection', collection )
                .displayTpl()

              if( _.isFunction( sheetCB ) )
                sheetCB()
            }
        }, this )
      }

    , sheet: function(  uid ) 
      {
        this.getViewByPath( 'Subbly.View.Customers' ).setActiveRow( uid )

        var scope = this
          , user  = subbly.api('Subbly.Model.User', { uid: uid })

        subbly.fetch( user,
        {
            data: { includes: ['addresses', 'orders'] }
          , success: function( model, response )
            {
              var json = model.toJSON()

              json.displayName = model.displayName()
              json.lastLogin   = moment.utc( model.get('last_login') ).format('llll')

              scope.getViewByPath( 'Subbly.View.CustomerSheet' )
                .setValue( 'model', model )
                .displayTpl( json )
            }
        }, this )
      }
  }


  // VIEWS
  // --------------------------------


  // List's row view (optional)
  // use it if you need to have 
  // full control on model (delete, etc.)
  // var CustomersRow = 
  // {
  //     className: 'cln-lst-rw cust-row js-trigger-goto'
  //   , _viewName: 'CustomerRow'

  //   , onInitialize: function( options )
  //     {
  //       this.tplRow = options.tpl
  //     }

  //   , render: function()
  //     {
  //       var html = this.tplRow({
  //           displayName: this.model.displayName()
  //         , createdDate: moment.utc( this.model.get('created_at') ).fromNow()
  //       })

  //       this.$el.html( html )

  //       this.el.dataset.uid  = this.model.get('uid')

  //       return this
  //     }

  //   , goTo: function( event )
  //     {
  //       this.callController( 'sheet', this.model.get('uid') )
  //     }
  // }

  // Customers List view
  var CustomersList = 
  {
      _viewName:     'Customers'
    , _viewTpl:      TPL.customers.list
    , _classlist:    ['view-half-list']
    , _listSelector: '#customers-list'
    , _tplRow:        TPL.customers.listrow
    // , _viewRow:       'subbly.View.CustomerRow'

      // On view initialize
    , onInitialize: function()
      {
        // add view's event
        this.addEvents( {'click li.js-trigger-goto':  'goTo'} )
      }

      // Call on list first render
    , onInitialRender: function()
      {
        var $firstRow = this.getListEl().children(':first')
          , uid       = $firstRow.attr('data-uid')

        this.callController( 'sheet', uid )
      }

      // Build single list's row
    , displayRow: function( model )
      {
        var html = this._tplRowCompiled({
            displayName: model.displayName()
          , createdDate: moment.utc( model.get('created_at') ).fromNow()
          , uid:         model.get('uid')
        })

        return html
      }

      // Higthligth active row
    , setActiveRow: function( uid )
      {
        var $listRows  = this.getListRows()
          , $activeRow = $listRows.filter('[data-uid="' + uid + '"]')

        $listRows.removeClass('active')
        $activeRow.addClass('active')
      }

      // go to customer profile
    , goTo: function( event )
      {
        var uid = event.currentTarget.dataset.uid

        subbly.trigger( 'hash::change', 'customers/' + uid, true )
        // this.callController( 'sheet', uid )
      }
  }

  // Customers Sheet view
  var CustomerSheet = 
  {
      _viewName:     'CustomerSheet'
    , _viewTpl:      TPL.customers.sheet
  }


  // REGISTER PLUGIN
  // --------------------------------


  subbly.register( 'Subbly', 'Customers', 
  {
      'ViewList:Customers':   CustomersList
    // , 'ViewListRow:CustomerRow':   CustomersRow
    , 'View:CustomerSheet':   CustomerSheet
    , 'Controller:Customers': Customers
  })


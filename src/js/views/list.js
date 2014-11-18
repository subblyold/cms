var SubblyViewList
  , SubblyViewListRow

Components.Subbly.View.Viewlist = SubblyViewList = SubblyView.extend(
{
    _listSelector:   false
  , _inviteTxt:      false // if no entries, show invite text
  , _viewsPointers:  {} // sub views references
  , _viewRow:        false
  , _tplRow:         false
  , _initialDisplay: false
  , _isLoadingMore:  false
  , _$list:          false
  , _$listItems:     false
  , collection:      false

  // , onSubblyInitialize: function()
  , initialize: function( options )
    {
      // Call parent `initialize` method
      SubblyView.prototype.initialize.apply( this, arguments )

      // console.log( 'initialize list view ' + this._viewName )

      this.on( 'view::scrollend', this.nextPage, this )
      subbly.on( 'pagination::fetch', this.loadMore, this )
    }

  , onDisplayTpl: function()
    {
      if( !this._listSelector )
        throw new Error( 'List view "' + this._viewName + '" miss _listSelector param' )

      this._$list = this.$el.find( this._listSelector )

      // Compile row template
      if( this._tplRow )
        this._tplRowCompiled = Handlebars.compile( this._tplRow )

      this.render()

      return this
    }

    // Return jQuery object
    // of the list's holder
  , getListEl: function()
    {
      return this._$list
    }

    // Return jQuery object
    // of the list's rows
  , getListRows: function()
    {
      return this._$listItems
    }

    // Test if there a next page available 
    // Trigger 'pagination::fetch' event
  , nextPage: function()
    {
      console.groupCollapsed('call next page' )

      if( this._isLoadingMore )
      {
        console.info('already loading')
        console.groupEnd()
        return
      }

      if( !this.collection.nextPage() )
      {
        console.info('no more page')
        console.groupEnd()
        return
      }

      subbly.trigger( 'pagination::fetch' )

      console.info('call next page')
      console.groupEnd()
    }

    // Test if there a previous page available 
    // Trigger 'pagination::fetch' event
  , prevPage: function()
    {
      if( !this.collection.prevPage() )
        return

      subbly.trigger( 'pagination::fetch' )
    }

    // Display the `loading` row
    // on pagination
  , loadMore: function()
    {
      if( this._isLoadingMore )
        return

      this._isLoadingMore = true

      var li   = document.createElement('li')
        , span = document.createElement('span')
        , txt  = document.createTextNode( 'loading' )

      span.className = 'f-lrg strong'
      span.appendChild( txt )

      li.className = 'cln-lst-rw cln-lst-load'
      li.id        = 'list-pagination-loader'
      li.appendChild( span )

      this._$list.append( li )

      subbly.fetch( this.collection,
      {
          success: _.bind( this.render, this )
      }, this )
    }

    // Render list's row
  , render: function()
    {
      console.groupCollapsed( 'render collection items' )

      if( !this.collection )
      {
        console.warn('ABORT, no collection items')
        console.groupEnd()
        return
      }

      // fetch flag
      this._isLoadingMore = false
      console.info('remove `_isLoadingMore` flag')

      var $loader = $( document.getElementById( 'list-pagination-loader') )

      if( $loader.length )
      {
        $loader.remove()
        console.info('remove loader')
      }

      // this._viewsPointers = {}

      if( !this.collection.length && this._inviteTxt )
      {
        subbly.trigger( 'loader::hide' )
        this.displayInviteMsg()
        console.warn('Collection empty, display invite message')
        console.groupEnd()
        return
      }

      this._fragment = ( !this._fragment && this._viewRow )
                       ? document.createDocumentFragment()
                       : ''

      this.collection.each( function( model )
      {
        //this does not break. _.each will always run
        //the iterator function for the entire array
        //return value from the iterator is ignored
        if( !this.beforeAddRow( model ) )
          return

        if( this._viewRow )
        {
          var view = this.displayRowView( model )

          this.registerRow( model.cid, view )
        }
        else
        {
          this._fragment += this.displayRow( model )
        }

        this.afterAddRow( model )
      }, this )

      if( this._fragment )
      {
        this._$list.append( this._fragment )
        this._$listItems = this._$list.find('.list-row')

        console.info('append DOM\'s fragment')

        if( !this._initialDisplay )
        {
          this._initialDisplay = true

          console.info('it was the first call to the collection')

          if( this.onInitialRender )
            this.onInitialRender()
        }

        delete this._fragment
      }

      if( this.onAfterRender )
        this.onAfterRender()
      
      subbly.trigger( 'loader::hide' )
      console.groupEnd()
    }

    // TODO: to design
    // Show an default message 
    // if there is no entry
  , displayInviteMsg: function()
    {
      var div       = document.createElement('div')
        , span      = document.createElement('span')
        , breakLine = document.createElement('br')
        , inviteTxt = document.createTextNode( this._inviteTxt )
        , linkTxt   = document.createTextNode( 'invites.btnTxt' )
        , link      = document.createElement('a')

      span.className = 'txt'
      span.appendChild( inviteTxt )

      link.className = 'btn btn-success btn-lg js-tigger-first'
      link.href      = 'javascript:;'
      link.appendChild( linkTxt )

      div.className = 'invite-create'
      div.appendChild( span )

      if( this.inviteTxt )
      {
        div.appendChild( breakLine )
        div.appendChild( link )        
      }

      this.$el.find('.app-content').html( div )
    }

    // Hook to override if need
    // called the first time list 
    // is display
  , onInitialRender: function()
    {

    }

    // Hook to override if needed
    // called after rows have been append
  , onAfterRender: function()
    {

    }

    // Hook to override
    // if conditional logic needed
    // add code here in local function.
    // return false will prevent
    // row to be display
  , beforeAddRow: function( model )
    {
      return true
    }

    // Hook to override
  , afterAddRow: function( model )
    {
      // if conditional logic needed
      // add code here in local function
    }

    // Hook to override if needed
    // append row to the list
  , displayRow: function( model )
    {

    }

    // Register row to `_viewsPointers`
    // allow it to be remove properly
  , registerRow: function( cid, view )
    {
      this._viewsPointers[ cid ] = view

      this._fragment.appendChild( view.render().el )
    }

    // Allow to override method localy
  , displayRowView: function( model )
    {
      return subbly.api( this._viewRow, {
          model:      model
        , parent:     this
        , tpl:        this._tplRowCompiled
        , controller: this._controller
      })
    }

    // Remove a row views 
    // based on model CID
  , removeRow: function( cid )
    {
      this._viewsPointers[ cid ].close()
      delete this._viewsPointers[ cid ]

      subbly.trigger( 'row::deleted' )
    }

    // Remove all rows views
  , cleanRows: function()
    {
      _( this._viewsPointers )
        .forEach( function( view )
        {
          this._viewsPointers[ view.model.cid ].close()
        }, this)      
    }

  , onClose: function()
    {
      if( this.onCloseList )
        this.onCloseList()

      if( this.collection )
        this.collection.resetPagination()

      this._initialDisplay = false

      SubblyView.prototype.onClose.apply( this, arguments )

      // subbly.off( 'pagination::changed',  this.render, this ) 
      // subbly.off( 'row::delete',          this.removeRow, this ) 
      // subbly.off( 'collection::truncate', this.cleanRows, this )
      this.off( 'view::scrollend', this.nextPage, this )
      subbly.off( 'pagination::fetch', this.loadMore, this )

      this.cleanRows()
    }
})

Components.Subbly.View.ViewlistRow = SubblyViewListRow = SubblyView.extend(
{
    _classIdentifier: 'list-row'
  , tagName:          'li'

  , events: _.extend( {}, SubblyView.prototype.events, 
    {
        'click.js-trigger-goto':  'goTo'
      , 'click .js-trigger-goto': 'goTo'
    })

  , goTo: function( event )
    {
console.log('goTo')
    }
})

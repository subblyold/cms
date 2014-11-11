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
  , collection:      false

  ,  onInitialize: function()
    {
console.log( 'initialize list view ' + this._viewName)

      var scope = this

      this.on( 'view::scrollend', this.nextPage, this )

      subbly.on( 'pagination::fetch', function()
      {
// console.log( scope )
// return
        scope._controller.fetch( scope.collection,
        {
            success: function(bbObj, response)
            {
console.log( bbObj)
console.log( bbObj)
            }
        }, this )
      })
    }

  , onDisplayTpl: function()
    {
      if( !this._listSelector )
        throw new Error( 'List view "' + this._viewName + '" miss _listSelector param' )

      this._$list = this.$el.find( this._listSelector )

      // Compile row template
      this._tplRowCompiled = Handlebars.compile( this._tplRow )

      this.render()
    }

  , nextPage: function()
    {
      if( !this.collection.nextPage() )
        return

      subbly.trigger( 'pagination::fetch' )
    }

  , prevPage: function()
    {
      if( !this.collection.prevPage() )
        return

      subbly.trigger( 'pagination::fetch' )
    }

  , render: function()
    {
      this.cleanRows()

      // this._viewsPointers = {}

      if( !this.collection.length && this._inviteTxt )
      {
        subbly.trigger( 'loader::hide' )
        this.displayInviteMsg()
        return
      }

      if( !this.collection )
        return

      this.collection.each( function( model )
      {
        if( !this._fragment )
          this._fragment = document.createDocumentFragment()

        //this does not break. _.each will always run
        //the iterator function for the entire array
        //return value from the iterator is ignored
        if( !this.beforeAddRow( model ) )
          return

        var view = this.addRow( model )

        this.registerRow( model.cid, view )

        this.afterAddRow( model )
      }, this )

      if( this._fragment )
      {
        this._$list.append( this._fragment )

        if( !this._initialDisplay )
        {
          this._initialDisplay = true

          this._$list.children(':first').addClass('active')
        }

        delete this._fragment
      }
      
      subbly.trigger( 'loader::hide' )
    }

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

  , registerRow: function( cid, view )
    {
      this._viewsPointers[ cid ] = view

      this._fragment.appendChild( view.render().el )
    }

    // Allow to override method localy
  , addRow: function( model )
    {
      return subbly.api( this._viewRow, {
          model:  model
        , parent: this
        , tpl:    this._tplRowCompiled
      })
    }

  , removeRow: function( cid )
    {
      this._viewsPointers[ cid ].close()
      delete this._viewsPointers[ cid ]

      subbly.trigger( 'row::deleted' )
    }

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

      subbly.off( 'pagination::changed',  this.render, this ) 
      subbly.off( 'row::delete',          this.removeRow, this ) 
      subbly.off( 'collection::truncate', this.cleanRows, this )

      this.cleanRows()
    }
})

Components.Subbly.View.ViewlistRow = SubblyViewListRow = SubblyView.extend(
{
    events: _.extend( {}, SubblyView.prototype.events, 
    {
        'click.js-trigger-goto':  'goTo'
      , 'click .js-trigger-goto': 'goTo'
    })

  , goTo: function( event )
    {
console.log('goTo')
    }
})

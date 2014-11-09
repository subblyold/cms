
var SubblyController = Backbone.Controller.extend(
{
    _tplStructure:   'full' // full|half|third
  , _tplLenght:      0
  , _tplPointers:    {} // references to the sub views
  , _viewsPointers:  {}
  , _viewsNames:     []
  , _tplDisplayed:   false
  , el:              null
  , $el:             null
  , _parentView:     false
  , _controllerName: null
  , _mainRouter:     false
  , _mainNav:        false

  , initialize: function() 
    {
      this._mainRouter = this.options.router

      this.registerMainView()

      if( this.onInitialize )
        this.onInitialize()
    }

  , onBeforeRoute: function()
    {
      this.displayViews()
      
      // register current view
      console.log('onBeforeRoute current view ' + this._controllerName)

      this._mainRouter._currentView = this
    }

    // Clean DOM and JS memory
  , remove: function() 
    {
// console.info('remove ' + this._controllerName)
      _( this._viewsPointers )
        .forEach( function( v )
        {
          this._viewsPointers[ v._viewId ].close()
        }, this)

      this._parentView    = false
      this._viewsPointers = {}
    }

    // create DOM elements
    // and attach views to
  , displayViews: function()
    {
      this.el  = document.getElementById('main-view')
      this.$el = $( this.el )

      var fragment     = document.createDocumentFragment()
        , parentViewId = this._controllerName + '-parent'
      
      this._parentView = this.buildView( 'full', parentViewId )

      this.$el.html( this._parentView )

      // default full view
      var index  = -1

      switch( this._tplStructure )
      {
        case 'half':
        case 'third':
          this._tplLenght = 2
          break
      }

      // Nested views
      if( this._tplLenght )
      {
        while( ++index < this._tplLenght )
        {
          var viewId = this._controllerName + '-' + index
            , view   = this.buildView( this._tplStructure, viewId )

          this._parentView.appendChild( view )

          this._viewsPointers[ viewId ] = subbly.api( this._viewsNames[ index ], {
              el:     view
            , viewId: viewId
          })
        }
      }
      // Single view
      else
      {
        this._viewsPointers[ parentViewId ] = subbly.api( this._viewsNames, {
            el:     this._parentView
          , viewId: parentViewId
        })
      }

// console.log( this._viewsPointers )
    }

    // return single DOM element
  , buildView: function( className, id )
    {
      var div = document.createElement('div')

      div.className = 'view-' + className
      div.id        = 'view-' + id

      return div
    }

  , registerMainView: function()
    {
      if( this._mainNav )
        this._mainRouter.registerMainNav( this._mainNav )
    }
})

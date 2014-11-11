
var SubblyController = Backbone.Controller.extend(
{
    _tplStructure:   'full' // full|half|third
  , _tplLenght:       0
  , _tplPointers:     {} // references to the sub views
  , _viewsPointers:   {} // views references
  , _reversedPointer: {} // reversed views references
  , _viewsNames:      [] 
  , _tplDisplayed:    false
  , el:               null
  , $el:              null
  , _parentView:      false
  , _controllerName:  null
  , _mainRouter:      false
  , _mainNav:         false
  , _instance:        'Controller'

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

      this._mainRouter._currentView = this

      if( this.onBefore )
        this.onBefore()
    }

  , onAfterRoute: function()
    {
      if( this.onAfter )
        this.onAfter()
    }

    // Clean DOM and JS memory
  , remove: function() 
    {
      //Stop pending fetch
      subbly.cleanXhr()

      // Close views
      _( this._viewsPointers )
        .forEach( function( v )
        {
          this._viewsPointers[ v._viewId ].close()
        }, this)

      // Reset variables
      this._parentView      = false
      this._viewsPointers   = {}
      this._reversedPointer = {}
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
              el:         view
            , viewId:     viewId
            , controller: this
          })

          this._reversedPointer[ this._viewsNames[ index ] ] = this._viewsPointers[ viewId ]
        }
      }
      // Single view
      else
      {
        this._viewsPointers[ parentViewId ] = subbly.api( this._viewsNames, {
            el:         this._parentView
          , viewId:     parentViewId
          , controller: this
        })
        
        this._reversedPointer[ this._viewsNames ] = this._viewsPointers[ parentViewId ]
      }
    }

  , getViewByPath: function( index )
    {
      if( !this._reversedPointer[ index ] )
      {
console.log( this._reversedPointer )
console.log( index )
        throw new Error( 'View index does not exists' )
      }
      
      return this._reversedPointer[ index ]
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
      if( this._mainNavRegister )
        this._mainRouter.registerMainNav( this._mainNavRegister )
    }
})

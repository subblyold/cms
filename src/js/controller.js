
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
      // Do not clean current view
      // if we call it again (e.g. frame view)
      if( this._mainRouter.controllersAreTheSame( this._getName() ) )
        return

      if( this.onBefore )
        this.onBefore()

      this.displayViews()
    }

  , onAfterRoute: function()
    {
      if( this.onAfter )
        this.onAfter()

      // register current controller
      this._mainRouter.setCurrentController( this )

      subbly.trigger( 'loader::progressEnd' )
    }

    // Clean DOM and JS memory
  , remove: function() 
    {
      //Stop pending fetch
      subbly.cleanXhr()

      // 
      if( this.onRemove )
        this.onRemove()

      // Close views
      _( this._viewsPointers )
        .forEach( function( v )
        {
          this._viewsPointers[ v._viewId ].close()

          delete this._viewsPointers[ v._viewId ]
        }, this)

      // Reset variables
      this._parentView      = false
      this._viewsPointers   = {}
      this._reversedPointer = {}
    }

  , _getName: function()
    {
      return this._controllerName
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

    // returns view object by its path
  , getViewByPath: function( index )
    {
      if( !this._reversedPointer[ index ] )
      {
        console.group('View index does not exists')
        console.log( this._reversedPointer )
        console.log( index )
        console.groupEnd()

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

  , getCurrentViewName: function()
    {
      return this._controllerName
    }

  , setCurrentViewName: function( viewName )
    {
      this._controllerName = viewName
      
      return this
    }

})

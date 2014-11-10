
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
  , _fetchXhr:       {}
  , _instance:       'Controller'

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
      //Stop pending fetch
      _( this._fetchXhr )
        .forEach( function( f )
        {
          if( f.readyState > 0 && f.readyState < 4 )
            f.abort()
        }, this)

      // Close views
      _( this._viewsPointers )
        .forEach( function( v )
        {
          this._viewsPointers[ v._viewId ].close()
        }, this)

      // Reset variables
      this._parentView    = false
      this._viewsPointers = {}
      this._fetchXhr      = {}
    }

    // Generic method to fetch model/collection
    // stores the XHR call which allows the abort on route change
    // trigger local loading event
  , fetch: function( obj, options, context )
    {
      var options = options || {}
        , xhrId   = _.uniqueId( 'xhr_' )

      if( context )
        context.trigger( 'fetch::calling' )

      this._fetchXhr[ xhrId ] = obj.fetch({
          data:    options.data || {} 
        , xhrId:   xhrId
        , success: function( bbObj, response, opts )
          {
            if( context )
              context.trigger( 'fetch::responds' )

            if( options.success && _.isFunction( options.success ) )
              options.success( bbObj, response, opts )
          }
        , error: function( bbObj, response, opts )
          {
            if( context )
              context.trigger( 'fetch::responds' )

            if( options.error && _.isFunction( options.error ) )
              options.error( bbObj, response, opts  )
          }
      })
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


// Subbly's Backbone base model

var SubblyView = Backbone.View.extend(
{
    _viewId:     false
  , _viewName:   false
  , _viewTpl:    false
  , _classlist:  []
  , _controller: false
  , _$nano:      false
  , _sticky:     false

  , initialize: function( options )
    {
      this._viewId     = options.viewId
      this._controller = options.controller

      if( this._classIdentifier )
        this._classlist.push( this._classIdentifier )

      // add extra class
      _( this._classlist )
        .forEach( function( c )
        {
          this.el.classList.add( c )
        }, this)

      // Public views `initialize` callback
      // you can use it
      if( this.onInitialize )
        this.onInitialize( options )

      return this
    }

    // view events
  , events: {}

    // Add events hash to the view
  , addEvents: function( events )
    {
      this.events = _.extend( {}, this.events, events )
    }

    // Hook to override if needed
  , onInitialize: function()
    {
      // this.on( 'fetch::calling', ..., ... )
      // this.on( 'fetch::responds', ..., ... 
    }

    // Call controller method from view
  , callController: function( method )
    {
      if( !this._controller[ method ] )
        throw new Error( 'controller "' + this._controllerName + '" does not have  "' + method + '" method' )

      var args = [].slice.call( arguments, 1 )

      return this._controller[ method ].apply( this._controller, args )
    }

  , setValue: function( key, value )
    {
      this[ key ] = value

      return this
    }

  , displayTpl: function( tplData )
    {
      if( !this._viewTpl )
        return

      tplData = tplData || {}

      var tpl  = Handlebars.compile( this._viewTpl )
        , html = tpl( tplData )

      // inject html inton view
      this.$el.html( html )

      // Setup nanoscroller
      this._$nano = this.$el.find('div.nano')
      
      var scope = this

      this._$nano.nanoScroller()
      this._$nano.on( 'scrollend', function( event )
      {
        scope.trigger('view::scrollend')
      })

      if( this.onDisplayTpl )
        this.onDisplayTpl( tplData )

      this._sticky = new scroll2sicky( this.$el )

      return this
    }

  , onClose: function()
    {
      if( this._$nano )
        this._$nano.nanoScroller({ destroy: true })

      if( this._sticky )
        this._sticky.kill()
    }
})

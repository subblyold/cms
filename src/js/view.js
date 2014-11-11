
// Subbly's Backbone base model

var SubblyView = Backbone.View.extend(
{
    _viewId:     false
  , _viewName:   false
  , _viewTpl:    false
  , _classlist:  []
  , _controller: false

  , initialize: function( options )
    {
      this._viewId     = options.viewId
      this._controller = options.controller

      // add extra class
      _( this._classlist )
        .forEach( function( c )
        {
          this.el.classList.add( c )
        }, this)

      if( this.onInitialize )
        this.onInitialize( options )

      return this
    }
    
//     , onInitialize: function()
//       {
//   console.log( 'onInitialize Customers')
//         this.on( 'fetch::calling', function()
//         {
// console.log('fetch::calling')
//         } )

//         this.on( 'fetch::responds', function()
//         {
// console.log('fetch::responds')
//         } )
//       }

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
      var $nano = this.$el.find('div.nano')
        , scope = this

      $nano.nanoScroller()
      $nano.on( 'scrollend', function( event )
      {
        scope.trigger('view::scrollend')
      })

      scroll2sicky.init( this.$el )

      if( this.onDisplayTpl )
        this.onDisplayTpl()

      return this
    }
})

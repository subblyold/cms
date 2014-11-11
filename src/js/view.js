
// Subbly's Backbone base model

var SubblyView = Backbone.View.extend(
{
    _viewId:    false
  , _viewName:  false
  , _viewTpl:   false
  , _classlist: []

  , initialize: function( options )
    {
      this._viewId = options.viewId

      // add extra class
      _( this._classlist )
        .forEach( function( c )
        {
          this.el.classList.add( c )
        }, this)

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
      this.$el.find('div.nano').nanoScroller()

      scroll2sicky.init( this.$el )
    }
})

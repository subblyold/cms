
// Subbly's Backbone base model

var SubblyView = Backbone.View.extend(
{
    _viewId:   false
  , _viewName: false
  , _viewTpl:  false

  , initialize: function( options )
    {
      this._viewId   = options.viewId

      // console.log(' initialize ' + this._viewName, this._viewId, this.el)

      this.setHtml()

      return this
    }

  , setHtml: function()
    {
      if( !this._viewTpl )
        return

      var tpl  = Handlebars.compile( this._viewTpl )
        , html = tpl( {} )

      this.$el.html( html )
    }
})

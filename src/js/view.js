
// Subbly's Backbone base model

var SubblyView = Backbone.View.extend(
{
    _viewId:   false
  , _viewName: false

  , initialize: function( options )
    {
      this._viewId   = options.viewId

      // console.log(' initialize ' + this._viewName, this._viewId, this.el)

      return this
    }
})

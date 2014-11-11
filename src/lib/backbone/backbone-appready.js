/*!
 * backbone.app-ready.js v0.1.0
 *
 * Trigger your App route when you decided it!
 *
 * Copyright 2014, Michael Lefebvre (michael@scenedata.com)
 * backbone-appready.js may be freely distributed under the MIT license.
 */
(function(){
  'use strict';

  _.extend(Backbone.Router.prototype, Backbone.Events,
  {
    instance: false,
    requestedCallback: null,
    requestedArgs: [],
    catchedRouteName: false,

    ready: function( _cb )
    {
      this.instance = true;
      if(this.requestedCallback !== null)
      {
        console.groupCollapsed( 'AppRouter: release requested route' )
          console.log( this.catchedRouteName )
          console.log( this.requestedArgs )
        console.groupEnd()

        if( _.isFunction( _cb ) )
          _cb( this.catchedRouteName )

        this.requestedCallback.apply(this, this.requestedArgs);
        this.requestedCallback = null;
        this.catchedRouteName = false;
        this.requestedArgs = [];
      }
    },

    route: function(route, name, callback) {
      Backbone.history || (Backbone.history = new Backbone.History);
      if (!_.isRegExp(route)) route = this._routeToRegExp(route);
      if (!callback) callback = this[name];
      Backbone.history.route(route, _.bind(function(fragment) {
        var args = this._extractParameters(route, fragment);
        if(this.instance)
        {
          callback && callback.apply(this, args);
        }
        else
        {
          this.catchedRouteName = name;
          this.requestedCallback = callback;
          this.requestedArgs  = args;
          
          console.groupCollapsed( 'AppRouter: store requested route' )
            console.log( this.catchedRouteName )
            console.log( this.requestedArgs )
          console.groupEnd()
        }
        this.trigger.apply(this, ['route:' + name].concat(args));
        Backbone.history.trigger('route', this, name, args);
      }, this));
      return this;
    }

  });

}).call(this);

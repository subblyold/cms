
Components.Subbly.View.MainNav = Backbone.View.extend(
{
    el: '#bo-nav'

  , initialize: function( options )
    {
      this._items = options.items
      this._itemsSorted = _.sortBy( this._items, function( obj )
      {
        return -obj.order 
      })

      this.fragment = document.createDocumentFragment()

      _.each( this._itemsSorted, this.buildItem, this )

      this.$el.html( this.fragment )

      this.$links = this.$el.find('a.js-trigger-go')

      subbly.on( 'hash::changed', this.hashChanged, this )

      this.userNav()

      return this
    }

  , events: {
      'click a.js-trigger-go': 'goTo'
    }

  , userNav : function()
    {
      var nodeId   = 'user-nav-trigger'
        , $trigger = $( document.getElementById( nodeId ) )
        , $parent  = $trigger.parent('div.user-nav-container')

      var hideList = function()
      {
        $parent.removeClass('active')
      }

      var handleClickOutside = function( event )
      {
        event.stopPropagation()

        if( !$( event.target ).parents('#' + nodeId ).length )
          hideList()
      }
      
      $trigger
        .on( 'click', function( event )
        {
          event.stopPropagation()
          event.preventDefault()

          $parent.addClass('active')

          $body.on( 'click', handleClickOutside )
        })

      $( document.getElementById('user-nav-sub') )
        .find('a')
        .on( 'click', hideList )
    }

  , buildItem: function( item )
    {
      var li  = document.createElement('li')
        , a   = document.createElement('a')
        , txt = document.createTextNode( item.name )

      //  href="javascript:;" class="fst-nav js-trigger-go"
      a.href        = 'javascript:;'
      a.className   = 'fst-nav js-trigger-go'
      a.dataset.url = item.defaultUrl

      a.appendChild( txt )
      li.appendChild( a )

      this.fragment.appendChild( li )
    }

  , goTo: function( event )
    {
      subbly.trigger( 'hash::change', event.target.getAttribute('data-url') )
    }

  , hashChanged: function( href )
    {
      var $target = this.$links.filter('a[data-url="' + href + '"]')

      if( !$target.length )
        return

      this.$links.removeClass('active')

      $target.addClass('active')
    }
})

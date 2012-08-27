/* 
 * KICKSTARTER
 *
 *
 *                                     ,-~ |
 *        ________________          o==]___|
 *       |                |            \ \
 *       |________________|            /\ \
 *  __  /  _,-----._      )           |  \ \.
 * |_||/_-~         `.   /()          |  /|]_|_____
 *   |//              \ |              \/ /_-~     ~-_
 *   //________________||              / //___________\
 *  //__|______________| \____________/ //___/-\ \~-_
 * ((_________________/_-o___________/_//___/  /\,\  \
 *  |__/(  ((====)o===--~~                 (  ( (o/)  )
 *       \  ``==' /                         \  `--'  /
 *        `-.__,-'                           `-.__,-'
 *
 */

(function(window, undefined)
{
	// Prepare our Variables
	var History		= window.History,
		$			= window.jQuery,
		document	= window.document;


	// Default object properties
	// will be merge && extends
	// with project needs
	var defaults = {
		$window   : $(window),     // style guide sugestion, prefix jQuery object by $
		$document : $(document),
		env       : 'development', // environement, allow|hide console.log
		RootUrl   : null,
		viewport  : {
			width  : 1920,
			height : 500,
			tablet : false,
			mobile : false
		},
		lang : [],
		ajax : {
			durationFadeIn: 200,
			durationFadeOut: 400,
			loading: 'chargement'
		}
	};

	var ns,            // project namespace
		channels = {}; // mediator channels

	// Toolsbox
	var kickstarter = 
	{
		env: null,

		// mediator

		subscribe: function(channel, subscription)
		{
			if (!channels[channel]) channels[channel] = [];

			var token = Math.guid();

			channels[channel].push({
				func: subscription,
				token: token
			});

			return token;
		},

		unsubscribe: function(channel, token)
		{
			if (!channels[channel]) return;

			if(typeof token === 'undefined')
			{
				delete channels[channel];
				return;
			}

			for (var i = -1, l = channels[channel].length; ++i < l;)
			{
				if(channels[channel][i].token == token)
				{
					delete channels[channel][i];
					return;
				}
			}			
		},

		publish: function(channel)
		{
			if (!channels[channel]) return;
			var args = [].slice.call(arguments, 1);

			for (var i = -1, l = channels[channel].length; ++i < l;)
			{
				channels[channel][i].func.apply(this, args);
			}
		},

		// Markup-based unobtrusive comprehensive DOM-ready execution

		fire: function(func, funcname, args)
		{
			funcname = (typeof funcname === 'undefined') ? 'init' : funcname;

			if (func !== '' && ns[func] && typeof ns[func][funcname] == 'function')
			{
				ns[func][funcname](args);
			} 

		}, 

		loadEvents: function()
		{
			var _body	= document.body,
			_controller	= _body.getAttribute('data-controller'),
			_method		= _body.getAttribute('data-method');

			// hit up common first.

			kickstarter.fire('core');
			kickstarter.fire(_controller);
			kickstarter.fire(_controller, _method);
			kickstarter.fire('core','finalize');
		},

		viewport: function()
		{
			ns.viewport.width  = ns.$window.width();
			ns.viewport.height = ns.$window.height();
		},

		// set our project namespace
		bootstrap: function(namespace, options)
		{
			// save previous version of this object if exists
			var obj = typeof window[namespace] === 'undefined' ? {} : window[namespace];

			// merge defaults options with project options
			// expose project namespace to the global object
			window[namespace] = ns = $.extend({}, obj, defaults, options || {});

			ns.rootUrl = kickstarter.getRootUrl();

			$(kickstarter.loadEvents);

			ns.$window
				.resize(kickstarter.viewport)
				.trigger('resize');
		},

		getUrl: function()
		{
			if( !History.enabled )
			{
				return document.location.href;
			}
			else
			{
				return History.getState().url;	
			}
		},

		// History JS method 
		getRootUrl: function()
		{
			// Create
			var rootUrl = document.location.protocol+'//'+(document.location.hostname||document.location.host);
			if ( document.location.port||false ) {
				rootUrl += ':'+document.location.port;
			}
			rootUrl += '/';

			// Return
			return rootUrl;
		},

		getRelativeUrl: function()
		{
			var url = kickstarter.getUrl();

			return url.replace(ns.rootUrl,'');
		}
	};
	
	window.kickstarter = kickstarter;

	////// FLUX ////////////////////////////////////////////

	// Check to see if History.js is enabled for our Browser
	if( !History.enabled )
	{
		return;
	}

	// Wait for Document
	$(function()
	{
		// Application Generic Variables
		var 
			rootUrl  = History.getRootUrl(),
			$body    = $(document.body),
			$pageTop = $('html, body'),
			$content = $('div[role="main"]');
		
		// Internal Helper
		$.expr[':'].internal = function(obj, index, meta, stack)
		{
			// Prepare
			var _this	= $(obj),
				url		= _this.attr('href') || '';
				
			return url.substring(0,rootUrl.length) === rootUrl || url.indexOf(':') === -1;
		};
	
		// Ajaxify Helper
		$.fn.ajaxify = function()
		{
			// Prepare
			var $this = $(this);

			// Ajaxify
			$this.find('a:internal:not([data-bypass])').click(function(event)
			{
				// Continue as normal for cmd clicks etc
				if ( event.which == 2 || event.metaKey ) { return true; }

				History.pushState(null, ns.ajax.loading, this.href);
				
				return false;
			});
			
			// Chain
			return $this;
		};

		$body.ajaxify();

		// Hook into State Changes
		$(window).bind('statechange', function()
		{			
			// Prepare Variables
			var State		= History.getState(),
				url			= State.url,
				relativeUrl	= url.replace(rootUrl,'');

			// Set Loading
			$body.addClass('loading');
			
			$(this).unbind('scroll');

			// BEFORE
			kickstarter.publish('statechange::before');

			// Start Fade Out
			// Animating to opacity to 0 still keeps the element's height intact
			// Which prevents that annoying pop bang issue when loading in new content
			$content.animate({opacity:0}, ns.ajax.durationFadeIn, function()
			{
				$pageTop.scrollTop(0);

				// Ajax Request the Page as Json
				$.ajax(
				{
					url: url, //+'?ajax',
					dataType: 'json',
					success: function(_json, textStatus, jqXHR)
					{
						$content
							.html(_json.view)
							.ajaxify();
						
						// Trigger specific Controller
						kickstarter.fire(_json.js.controller);

						// if(_json.js.method != '')
						// {
						// 	kickstarter.fire(_json.js.controller, _json.js.method);
						// }

						$body.removeClass('loading');
						$content.animate({opacity: 1}, ns.ajax.durationFadeOut);
						
						document.title = _json.meta.title;
						
						// Inform Google Analytics of the change
						if ( typeof window._gaq !== 'undefined' )
						{
							window._gaq.push(['_trackPageview']);	
						}

						// AFTER
						kickstarter.publish('statechange::after', _json);
					},
					error: function(jqXHR, textStatus, errorThrown)
					{
						document.location.href = url;
						return false;
					}
				}); // end ajax

			}); // end animate

		}); // end onStateChange

	}); // end onDomLoad

})(window); // end closure

/* UTILS
----------------------- */

Math.guid = function()
{
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c)
	{
		var r = Math.random()*16|0, 
			v = c == 'x' ? r : (r&0x3|0x8);
		return v.toString(16);
	}).toUpperCase();
};

// Shorthand jQuery selector cache. 
// Only use on selectors for the DOM that won't change.
var $$ = (function()
{
	var cache = {};
	return function(selector)
	{
		if (!cache[selector])
		{
			cache[selector] = $(selector);
		}
		return cache[selector];
	};
})();


// In case we forget to take out console statements. 
// IE becomes very unhappy when we forget. Let's not make IE unhappy
if(typeof(console) === 'undefined' && kickstarter.env == 'production')
{
	var console = {}
	console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
}


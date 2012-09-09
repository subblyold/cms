
kickstarter.bootstrap('subbly');

subbly.core = (function()
{
	var $global;

	var sidebar = function()
	{

		var $sidebar      = $('#sidebar'),
			$sidebarLinks = $sidebar.find('a');

		if(!categorizr.isTablet())
		{
			var $subnav       = $sidebar.find('ul.sub-nav'),
				$subnavActive = $sidebar.find('ul.sub-nav.open'),
				$subnavLinks  = $subnav.find('a');

			$sidebar.find('a.has-subnav').click(function(event)
			{
				event.preventDefault();
				var $this = $(this);

				$sidebarLinks.removeClass('active');

				$this.addClass('active')

				var $subnav = $this.next('ul');

				if(!$subnav.hasClass('open'))
				{
					var $subnavActiveLinks = $subnavActive.find('li')

					$subnavActiveLinks.removeClass('active');

					$subnavActive.slideUp(200, function()
					{
						var $this = $(this);
						$this.removeClass('open');
						$subnavActiveLinks.removeClass('visible');
					});
					
					$subnavActive = $subnav;

					$subnav.slideDown(200, function()
					{
						$subnav.addClass('open');

						$subnav.find('li').each(function(_index)
						{
							var delay = ((_index +1) * 100),
								$this = $(this);

							window.setTimeout(function()
							{
								$this.addClass('visible');					
							}, delay)
		 
						});

					});
				}
			});

			$('#developper-tool-switch').find('a').click(function()
			{
				$(this).toggleClass('active');
			});

			var $userNav        = $('#user-nav'),
				$userNavTrigger = $userNav.find('h3'),
				$userNavList    = $userNav.find('ul'); 

			$userNav.outside({
				callback: function()
				{
					if($userNavTrigger.attr('data-toggle') == 'open')
					{
						$userNavTrigger.trigger('click');					
					}
				},
				once: false
			});

			var outsideApi = $userNav.data('outside-api');

			$userNavTrigger.click(function()
			{
				var $this      = $(this),
					attr       = $this.attr('data-toggle');

				if(attr == 'close')
					$this.attr('data-toggle', 'open');
				else
					$this.attr('data-toggle', 'close');

				$userNavList.slideToggle(200)
			})
		}
		else // ipad
		{
			var $popover        = $('#subnav-popover'),
				$popoverContent = $popover.find('ul');

			$sidebar.find('a.has-subnav').click(function(event)
			{
				event.preventDefault();

				var $this = $(this);

				$sidebarLinks.removeClass('active');

				$this.addClass('active')

				var $subnav = $this.next('ul'),
					offset = $this.offset().top;

				$popoverContent.html($subnav.html());
				$popover.css('top', offset).fadeIn(200, function()
				{
					$popover.outside(function(e)
					{
						$popover.fadeOut(200);
					});
				});



			});
		}
	}

	var finalize = function()
	{
		$('#global').find('.nano').nanoScroller({
			classPane: 'track',
			contentSelector: 'div.pane-content'
		});
	};

	var init = function()
	{
		sidebar();
		$('#content').find('[rel="tooltip"]').tooltip();
	}

	return {
		'init': init,
		'finalize': finalize
	};
})();

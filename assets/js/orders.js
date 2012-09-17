
subbly.orders = (function()
{
	var $filters,
		$filtersLabel,
		$filtersList,
		$filtersLinks,
		$summaryLinks,
		$tabsLinks,
		$tabs;

	var summaryToggle = function(event)
	{
		event.preventDefault();

		$summaryLinks.removeClass('active');

		$(this).addClass('active');

		// add content reload here
	}

	var filtersClose = function()
	{
		$filters.removeClass('open');
	}

	var filtersOpen = function()
	{
		$filters.addClass('open');

		window.setTimeout(function()
		{
			$filtersList.outside(filtersClose);

			outsideApi = $filtersList.data('outside-api');
		}, 500)
	}

	var filtersToggle = function()
	{
		if($filters.hasClass('open'))
		{
			filtersClose();
		}
		else
		{
			filtersOpen();
		}
	}

	var setFilter = function(event)
	{
		var $target = $(event.target),
			label   = $target.text(),
			value   = $target.attr('data-filter');

		$filtersLabel.text(label);

		// add filters logic here

		filtersClose();
	}

	var init = function()
	{
		// filters
		$filters      = $('#order-filters');
		$filtersLabel = $('#order-filters-label');
		$filtersList  = $('#order-filters-list');
		$filtersLinks = $filtersList.find('li');

		$filtersLabel.click(filtersToggle);

		$filtersLinks.click(setFilter);

		// summary
		$summaryLinks = $('#orders-summary').find('a');

		$summaryLinks.click(summaryToggle)

		// tabs
		var $tabsHolder = $('#customer-tabs');

		$tabsLinks    = $tabsHolder.find('a.tab-trigger');
		$tabs         = $tabsHolder.find('div.customer-tab');

		$tabsLinks.each(function(_index)
		{
			var $this = $(this);

			$this.click(function(event)
			{
				$tabs.removeClass('show');

				$tabsLinks.removeClass('active');

				$this.addClass('active');

				$tabs.eq(_index).addClass('show');
			});
		});
	}

	return {
		'init': init
	};
})()
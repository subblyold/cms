
subbly.dashboard = (function()
{
	var income = function()
	{
		var $slider     = $('#incomes-summary'),
			$slides     = $slider.find('li'),
			$slideWrap  = $('#incomes-summary-wrapper'),
			$slideBlock = $slider.find('ul'),
			$title      = $slider.find('h3'),
			nbSlide     = $slides.size(),
			lastSlide   = (nbSlide - 1),
			duration    = 400,
			animated    = false,
			current     = 0,
			slideWidth,
			width;

		function goTo(_index)
		{
			if(_index != current)
			{
				animated = true;			
				current = _index;
							
				var _left = (_index != -1) ?  (slideWidth * (_index + 1)) : 0;

				$slideWrap.animate({ scrollLeft: _left }, duration, 'easeOut', function()
				{
					if(current == nbSlide)
					{
						$slideWrap.scrollLeft(slideWidth);
						current = 0;
					}
					
					if(current == -1)
					{
						$slideWrap.scrollLeft((slideWidth * nbSlide));
						current = lastSlide;
					}

					var text = $slides.eq( (current + 1) ).attr('data-title');

					$title.text( text )

					animated	= false;
				});
			}
		}
		
		function goNext()
		{
			if(!animated)
			{
				var _next = (current + 1);
				goTo(_next);
			}
		}
		
		function goPrev()
		{
			if(!animated)
			{
				var _prev = (current - 1);
				
				goTo(_prev);
			}
		}

		subbly.$window.resize(function()
		{
			slideWidth = $slider.width(),
			width      = ((slideWidth * nbSlide) + (slideWidth * 2));

			$slides.width( slideWidth );

			$slideBlock.width(width);

			// $slideWrap.scrollLeft( (slideWidth * (current +1)) );

		}).trigger('resize')
		
		// CLONE FIRST AND LAST
		var _cloneFirst	= $slider.find('li:eq(0)').clone().addClass('cloned');
		var _cloneLast	= $slider.find('li:last').clone().addClass('cloned');

		$slideBlock.prepend(_cloneLast);
		$slideBlock.append(_cloneFirst);

		$slides = $slider.find('li');

		$slideWrap.scrollLeft(slideWidth);

		$slider.find('span.nav-prev').click(goPrev)
		$slider.find('span.nav-next').click(goNext)

	};

	var init = function()
	{
		income();
	}

	return {
		'init': init
	}
})();

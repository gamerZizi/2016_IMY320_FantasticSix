( function( $ ) {

	function removeNoJsClass() {
		$( 'html:first' ).removeClass( 'no-js' );
	}

	/* Superfish Menu ---------------------*/
	function superfishSetup() {

		$('.menu').superfish({
			delay: 200,
			animation: {opacity:'show', height:'show'},
			speed: 'fast',
			cssArrows: true,
			autoArrows:  true,
			dropShadows: false
		});

		// Fix Superfish menu if off screen.
		var wapoMainWindowWidth = $(window).width();

		$('ul.menu li').mouseover(function() {

			// Checks if second level menu exist.
			var subMenuExist = $(this).find('.sub-menu').length;
			if ( subMenuExist > 0 ) {
				var subMenuWidth = $(this).find('.sub-menu').width();
				var subMenuOffset = $(this).find('.sub-menu').parent().offset().left + subMenuWidth;

				// If sub menu is off screen, give new position.
				if ( (subMenuOffset + subMenuWidth) > wapoMainWindowWidth ) {
					var newSubMenuPosition = subMenuWidth + 3;
					$(this).find('.sub-menu').css({
						right: 0,
					});
				}
			}
		});

		$('ul.menu li li').mouseover(function() {

			// Checks if third level menu exist.
			var subMenuExist = $(this).find('.sub-menu').length;
			if ( subMenuExist > 0 ) {
				var subMenuWidth = $(this).find('.sub-menu').width();
				var subMenuOffset = $(this).find('.sub-menu').parent().offset().left + subMenuWidth;

				// If sub menu is off screen, give new position.
				if ( (subMenuOffset + subMenuWidth) > wapoMainWindowWidth ) {
					var newSubMenuPosition = subMenuWidth + 3;
					$(this).find('.sub-menu').css({
						left: -newSubMenuPosition,
					});
				}
			}
		});
	}

	/* Disable Superfish on mobile ---------------------*/
	function superfishMobile() {
		var sf, body;
		var breakpoint = 767;
		body = $('body');
		sf = $('ul.menu');
		if ( body.width() >= breakpoint ) {
			// Enable superfish when the page first loads if we're on desktop
			sf.superfish();
		}
		$(window).resize(function() {
			if ( body.width() >= breakpoint && !sf.hasClass('sf-js-enabled') ) {
				// You only want SuperFish to be re-enabled once (sf.hasClass)
				sf.superfish('init');
			} else if ( body.width() < breakpoint ) {
				// Smaller screen, disable SuperFish
				sf.superfish('destroy');
			}
		});
	}

	/* Flexslider ---------------------*/
	function flexSliderSetup() {
		if( ($).flexslider) {
			var slider = $('.flexslider');
			slider.flexslider({
				slideshowSpeed		: '12000',
				animationDuration	: 800,
				animation			: 'fade',
				video				: false,
				useCSS				: false,
				prevText			: '<i class="fa fa-angle-left"></i>',
				nextText			: '<i class="fa fa-angle-right"></i>',
				touch				: false,
				controlNav			: false,
				animationLoop		: true,
				smoothHeight		: true,
				pauseOnAction		: true,
				pauseOnHover		: false,

				start: function(slider) {
					slider.removeClass('loading');
					$( ".preloader" ).hide();
				}
			});
		}
	}

	/* Equal Height Columns Pages ---------------------*/
	function equalHeightPages() {
		var currentTallest 	= 0,
			currentRowStart = 0,
			rowDivs 		= new Array(),
			$el,
			topPosition 	= 0;

		$('.featured-pages .holder .content').each(function() {
			$el = $(this);
			$($el).height('auto')
			topPostion = $el.position().top;

			if (currentRowStart != topPostion) {
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
					rowDivs[currentDiv].height(currentTallest);
				}
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.height();
				rowDivs.push($el);

			} else {
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
			}
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
		});
	}

	function modifyPosts() {

		/* Fit Vids ---------------------*/
		$('.feature-vid, .content').fitVids();

		/* Insert Line Break Before More Links ---------------------*/
		$('<br />').insertBefore('.content .more-link');

		/* Hide Comments When No Comments Activated ---------------------*/
		$('.nocomments').parent().css('display', 'none');

		/* Animate Page Scroll ---------------------*/
		$(".scroll").click(function(event){
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top}, 500);
		});

	}

	$( document )
	.ready( removeNoJsClass )
	.ready( superfishSetup )
	.ready( superfishMobile )
	.ready( modifyPosts )
	.on( 'post-load', modifyPosts );

	$( window )
	.load( flexSliderSetup )
	.load( equalHeightPages )
	.resize( equalHeightPages );

})( jQuery );

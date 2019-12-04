/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
(function( $ ) {
	$.fn.waypoint = function(callback) {
		if ( typeof callback === 'function' ) {
			callback.call( this );
		}
	};
})( jQuery );

(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
var ElementsHandler;

ElementsHandler = function($) {
	var registeredHandlers = {},
		registeredGlobalHandlers = [];

	var runGlobalHandlers = function($scope) {
		$.each(registeredGlobalHandlers, function() {
			this.call($scope, $);
		});
	};

	this.addHandler = function(widgetType, callback) {
		registeredHandlers[ widgetType ] = callback;
	};

	this.addGlobalHandler = function(callback) {
		registeredGlobalHandlers.push(callback);
	};

	this.runReadyTrigger = function($scope) {
		var elementType = $scope.data('element_type');

		if ( ! elementType ) {
			return;
		}

		runGlobalHandlers( $scope );

		if ( ! registeredHandlers[ elementType ] ) {
			return;
		}

		registeredHandlers[ elementType ].call( $scope, $ );
	};
};

module.exports = ElementsHandler;

},{}],2:[function(require,module,exports){
/* global rbFrontendConfig */
( function( $ ) {
	var ElementsHandler = require('rb-frontend/elements-handler'),
	    Utils = require('rb-frontend/utils');

	var rbFrontend = function() {
		var self = this,
			scopeWindow = window;

		var elementsDefaultHandlers = {
			accordion: require('rb-frontend/handlers/accordion'),
			alert: require('rb-frontend/handlers/alert'),
			counter: require('rb-frontend/handlers/counter'),
			'countdown': require('rb-frontend/handlers/countdown'),
			'image-carousel': require('rb-frontend/handlers/image-carousel'),
			instagram: require('rb-frontend/handlers/instagram'),
			testimonial: require('rb-frontend/handlers/testimonial'),
			progress: require('rb-frontend/handlers/progress'),
			section: require('rb-frontend/handlers/section'),
			tabs: require('rb-frontend/handlers/tabs'),
			'prestashop-widget-Blog': require('rb-frontend/handlers/prestashop-blog'),
			'prestashop-widget-ProductsList': require('rb-frontend/handlers/prestashop-productlist'),
			'prestashop-widget-ProductsListTabs': require('rb-frontend/handlers/prestashop-productlisttabs'),
			'prestashop-widget-Brands': require('rb-frontend/handlers/prestashop-brands'),
			toggle: require('rb-frontend/handlers/toggle'),
			video: require('rb-frontend/handlers/video')
		};

		var addGlobalHandlers = function() {
			self.elementsHandler.addGlobalHandler( require( 'rb-frontend/handlers/global' ) );
		};

		var addElementsHandlers = function() {
			$.each( elementsDefaultHandlers, function( elementName ) {
				self.elementsHandler.addHandler( elementName, this );
			} );
		};

		var runElementsHandlers = function() {
			$( '.rb-element' ).each( function() {
				self.elementsHandler.runReadyTrigger( $( this ) );
			} );
		};

		this.config = rbFrontendConfig;

		this.getScopeWindow = function() {
			return scopeWindow;
		};

		this.setScopeWindow = function( window ) {
			scopeWindow = window;
		};

		this.isEditMode = function() {
			return self.config.isEditMode;
		};

		this.elementsHandler = new ElementsHandler( $ );

		this.utils = new Utils( $ );

		this.init = function() {
			addGlobalHandlers();

			addElementsHandlers();


			runElementsHandlers();
		};

		// Based on underscore function
		this.throttle = function( func, wait ) {
			var timeout,
				context,
				args,
				result,
				previous = 0;

			var later = function() {
				previous = Date.now();
				timeout = null;
				result = func.apply( context, args );

				if ( ! timeout ) {
					context = args = null;
				}
			};

			return function() {
				var now = Date.now(),
					remaining = wait - ( now - previous );

				context = this;
				args = arguments;

				if ( remaining <= 0 || remaining > wait ) {
					if ( timeout ) {
						clearTimeout( timeout );
						timeout = null;
					}

					previous = now;
					result = func.apply( context, args );

					if ( ! timeout ) {
						context = args = null;
					}
				} else if ( ! timeout ) {
					timeout = setTimeout( later, remaining );
				}

				return result;
			};
		};
	};

	window.rbFrontend = new rbFrontend();
} )( jQuery );

jQuery( function() {
	if ( ! rbFrontend.isEditMode() ) {
		rbFrontend.init();
	}
} );

},{"rb-frontend/elements-handler":1,"rb-frontend/handlers/accordion":3,"rb-frontend/handlers/alert":4,"rb-frontend/handlers/counter":5,"rb-frontend/handlers/global":6,"rb-frontend/handlers/image-carousel":7,"rb-frontend/handlers/instagram":8,"rb-frontend/handlers/prestashop-blog":9,"rb-frontend/handlers/prestashop-brands":10,"rb-frontend/handlers/prestashop-productlist":11,"rb-frontend/handlers/prestashop-productlisttabs":12,"rb-frontend/handlers/progress":13,"rb-frontend/handlers/section":14,"rb-frontend/handlers/tabs":15,"rb-frontend/handlers/testimonial":16,"rb-frontend/handlers/toggle":17,"rb-frontend/handlers/video":18,"rb-frontend/utils":19,"rb-frontend/handlers/countdown":20}],3:[function(require,module,exports){
var activateSection = function(sectionIndex, $accordionTitles) {
	var $activeTitle = $accordionTitles.filter('.active'),
		$requestedTitle = $accordionTitles.filter('[data-section="' + sectionIndex + '"]'),
		isRequestedActive = $requestedTitle.hasClass('active');

	$activeTitle
		.removeClass('active')
		.next()
		.slideUp();

	$activeTitle.find('.rb-open').html('add');

	if (!isRequestedActive) {
		$requestedTitle
			.addClass('active')
			.next()
			.slideDown();

		$requestedTitle.find('.rb-open').html('remove');
	}
};

module.exports = function($) {
	var $this = $(this),
		$accordionDiv = $this.find('.rb-accordion'),
		defaultActiveSection = $accordionDiv.data('active-section'),
		activeFirst =  $accordionDiv.data('active-first'),
		$accordionTitles = $this.find('.rb-accordion-title');

	if (! defaultActiveSection) {
		defaultActiveSection = 1;
	}

	if (activeFirst){
		activateSection( defaultActiveSection, $accordionTitles );
	}

	$accordionTitles.on('click', function() {
		activateSection(this.dataset.section, $accordionTitles);
	});
};

},{}],4:[function(require,module,exports){
module.exports = function($) {
	$(this).find( '.rb-alert-dismiss' ).on('click', function() {
		$(this).parent().fadeOut();
	});
};

},{}],5:[function(require,module,exports){
module.exports = function($) {
	var $number = $(this).find('.rb-counter-number');

	$number.waypoint(function() {
		$number.numerator({
			duration: $number.data('duration')
		});
	}, {offset: '90%'});
};

},{}],20:[function(require,module,exports){
module.exports = function($) {
	if ($('#index').length> 0) {
		var $this = $(this).find('.rb-countdown');
		var time = $this.find('.rb-clock').data('time');

		$this.find('.rb-clock').lofCountDown({
			TargetDate: time,
			DisplayFormat: '<li class="cd-day">%%D%% <em class="rb-day">'+rb_days+'</em></li><span class="cd-day">:</span><li>%%H%% <em class="rb-hours">'+rb_hours+'</em></li><span>:</span><li>%%M%% <em class="rb_minutes">'+rb_minutes+'</em></li><span>:</span><li>%%S%% <em class="rb_seconds">'+rb_seconds+'</em></li>',
		});	
	}
};

},{}],6:[function(require,module,exports){
module.exports = function() {
	if (rbFrontend.isEditMode()) {
		return;
	}

	var $element = this,
		animation = $element.data('animation');

	if (! animation) {
		return;
	}

	$element.addClass('rb-invisible').removeClass(animation);

	$element.waypoint( function() {
		$element.removeClass('rb-invisible').addClass(animation);
	}, { offset: '90%' });

};

},{}],7:[function(require,module,exports){
module.exports = function( $ ) {
	var $carousel = $( this ).find( '.rb-image-carousel' );
	if ( ! $carousel.length ) {
		return;
	}

	var savedOptions = $carousel.data( 'slider_options' ),
		tabletSlides = 1 === savedOptions.slidesToShow ? 1 : 2,
		defaultOptions = {
			responsive: [
				{
					breakpoint: 767,
					settings: {
						slidesToShow: tabletSlides,
						slidesToScroll: tabletSlides
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
			]
		},

		slickOptions = $.extend( {}, defaultOptions, savedOptions );

	$carousel.slick( slickOptions );
};

},{}],8:[function(require,module,exports){
module.exports = function($) {
	var $instagramWrapper = $(this).find('.rb-instagram');
    var $carousel = $(this).find('.rb-instagram-carousel');

	if ( ! $instagramWrapper.length ) {
		return;
	}

	var options = $instagramWrapper.data('options');

	if (options.token == '' ) {
		return;
	}

	$instagramWrapper.instagramLite({
		accessToken: options.token,
		limit: options.limit,
		urls: options.linked,
		element_class: options.class,
		comments_count: true,
		likes: true,
		videos: false,
		date: false,
		list: true,
		captions: false,
        success: function() {
            if ( ! $carousel.length ) {
                return;
            }

            var savedOptions = $carousel.data('slider_options');
    		$carousel.slick(savedOptions);
        },
	});
};

},{}],9:[function(require,module,exports){
module.exports = function($) {
    var $carousel = $(this).find('.rb-blog-carousel');

    if (!$carousel.length) {
        return;
    }

    var slickOptions = $carousel.data('slider_options');
    $carousel.slick( slickOptions );
};

},{}],10:[function(require,module,exports){
module.exports = function ($) {
    var $carousel = $(this).find('.rb-brands-carousel');
    if (!$carousel.length) {
        return;
    }

    var respondTo = 'window';

    if (rbFrontendConfig.isEditMode) {
        respondTo = 'iframe-window';
    }

    var slickOptions = $carousel.data('slider_options');
    $carousel.slick(slickOptions);

    if (rbFrontendConfig.isEditMode) {
        $(window).on('changedDeviceMode', function () {
            $carousel.slick('getSlick').checkResponsive();
        });
    }
};

},{}],11:[function(require,module,exports){
module.exports = function ($) {
    var $carousel = $(this).find('.rb-products-carousel');

    if (!$carousel.length) {
        if (rbFrontendConfig.isEditMode) {
            $(this).find('img[data-src]').each(function() {
                $(this).attr('src', $(this).data('src'));
            });
        }
        return;
    }

    var savedOptions = $carousel.data('slider_options');
    $carousel.slick(savedOptions);

    if (rbFrontendConfig.isEditMode) {
        $(window).on('changedDeviceMode', function () {
            $carousel.slick('getSlick').checkResponsive();
        });
    }
};

},{}],12:[function(require,module,exports){
module.exports = function ($) {
    var $carousel = $(this).find('.rb-products-carousel');

    if (!$carousel.length) {
        if (rbFrontendConfig.isEditMode) {
            $(this).find('img[data-src]').each(function() {
                $(this).attr('src', $(this).data('src'));
            });
        }
        return;
    }

    var savedOptions = $carousel.data('slider_options');
    $carousel.slick(savedOptions);

    if (rbFrontendConfig.isEditMode) {
        $(window).on('changedDeviceMode', function () {
            $carousel.slick('getSlick').checkResponsive();
        });
    }
};

},{}],13:[function(require,module,exports){
module.exports = function( $ ) {
	var $progressbar = $( this ).find( '.rb-progress-bar' );

	$progressbar.waypoint( function() {
		$progressbar.css( 'width', $progressbar.data( 'max' ) + '%' )
	}, { offset: '90%' } );
};

},{}],14:[function(require,module,exports){
var BackgroundVideo = function( $, $backgroundVideoContainer ) {
	var player,
		elements = {},
		isYTVideo = false;

	var calcVideosSize = function() {
		var containerWidth = $backgroundVideoContainer.outerWidth(),
			containerHeight = $backgroundVideoContainer.outerHeight(),
			aspectRatioSetting = '16:9', //TEMP
			aspectRatioArray = aspectRatioSetting.split( ':' ),
			aspectRatio = aspectRatioArray[ 0 ] / aspectRatioArray[ 1 ],
			ratioWidth = containerWidth / aspectRatio,
			ratioHeight = containerHeight * aspectRatio,
			isWidthFixed = containerWidth / containerHeight > aspectRatio;

		return {
			width: isWidthFixed ? containerWidth : ratioHeight,
			height: isWidthFixed ? ratioWidth : containerHeight
		};
	};

	var changeVideoSize = function() {
		var $video = isYTVideo ? $( player.getIframe() ) : elements.$backgroundVideo,
			size = calcVideosSize();

		$video.width( size.width ).height( size.height );
	};

	var prepareYTVideo = function( YT, videoID ) {
		player = new YT.Player( elements.$backgroundVideo[ 0 ], {
			videoId: videoID,
			events: {
				onReady: function() {
					player.mute();

					changeVideoSize();

					player.playVideo();
				},
				onStateChange: function( event ) {
					if ( event.data === YT.PlayerState.ENDED ) {
						player.seekTo( 0 );
					}
				}
			},
			playerVars: {
				controls: 0,
				showinfo: 0
			}
		} );

		$( rbFrontend.getScopeWindow() ).on( 'resize', changeVideoSize );
	};

	var initElements = function() {
		elements.$backgroundVideo = $backgroundVideoContainer.children( '.rb-background-video' );
	};

	var run = function() {
		var videoID = elements.$backgroundVideo.data( 'video-id' );

		if ( videoID ) {
			isYTVideo = true;

			rbFrontend.utils.onYoutubeApiReady( function( YT ) {
				setTimeout( function() {
					prepareYTVideo( YT, videoID );
				}, 1 );
			} );
		} else {
			elements.$backgroundVideo.one( 'canplay', changeVideoSize );
		}
	};

	var init = function() {
		initElements();
		run();
	};

	init();
};

module.exports = function( $ ) {
	var $backgroundVideoContainer = this.find( '.rb-background-video-container' );

	if ( $backgroundVideoContainer ) {
		new BackgroundVideo( $, $backgroundVideoContainer );
	}
};

},{}],15:[function(require,module,exports){
module.exports = function( $ ) {
	var $this = $( this ),
		defaultActiveTab = $this.find( '.rb-tabs' ).data( 'active-tab' ),
		$tabsTitles = $this.find( '.rb-tab-title' ),
		$tabs = $this.find( '.rb-tab-content' ),
		$active,
		$content;

	if ( ! defaultActiveTab ) {
		defaultActiveTab = 1;
	}

	var activateTab = function( tabIndex ) {
		if ( $active ) {
			$active.removeClass( 'active' );

			$content.removeClass( 'active' );
		}

		$active = $tabsTitles.filter( '[data-tab="' + tabIndex + '"]' );
		$active.addClass( 'active' );
		$content = $tabs.filter( '[data-tab="' + tabIndex + '"]' );
		$content.addClass( 'active' );
	};

	activateTab( defaultActiveTab );

	$tabsTitles.on( 'click', function() {
		activateTab( this.dataset.tab );
	} );
};

},{}],16:[function(require,module,exports){
module.exports = function( $ ) {
	var $carousel = $( this ).find( '.rb-testimonial-carousel' );
	if ( ! $carousel.length ) {
		return;
	}

	var savedOptions = $carousel.data( 'slider_options' ),
		tabletSlides = 1 === savedOptions.slidesToShow ? 1 : 2,
		defaultOptions = {
			responsive: [
				{
					breakpoint: 767,
					settings: {
						slidesToShow: tabletSlides,
						slidesToScroll: tabletSlides
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
			]
		},

		slickOptions = $.extend( {}, defaultOptions, $carousel.data( 'slider_options' ) );

	$carousel.slick( slickOptions );
};

},{}],17:[function(require,module,exports){
module.exports = function( $ ) {
	var $toggleTitles = $( this ).find( '.rb-toggle-title' );

	$toggleTitles.on( 'click', function() {
		var $active = $( this ),
			$content = $active.next();

		if ( $active.hasClass( 'active' ) ) {
			$active.removeClass( 'active' );
			$content.slideUp();
		} else {
			$active.addClass( 'active' );
			$content.slideDown();
		}
	} );
};

},{}],18:[function(require,module,exports){
module.exports = function( $ ) {
	var $this = $( this ),
		$imageOverlay = $this.find( '.rb-custom-embed-image-overlay' ),
		$videoModalBtn = $this.find( '.rb-video-open-modal' ).first(),
		$videoModal = $this.find( '.rb-video-modal' ).first(),
		$videoFrame = $this.find( 'iframe' );

	if ( $imageOverlay.length ) {

		$imageOverlay.on( 'click', function() {
			$imageOverlay.remove();
			var newSourceUrl = $videoFrame[0].src;
			// Remove old autoplay if exists
			newSourceUrl = newSourceUrl.replace( 'autoplay=0', 'autoplay=1' );
			$videoFrame[0].src = newSourceUrl;
		} );
	}

	if ( ! $videoModalBtn.length ) {
		return;
	}


	$videoModalBtn.on( 'click', function() {
		var newSourceUrl = $videoFrame[0].src;
		// Remove old autoplay if exists
		newSourceUrl = newSourceUrl.replace( 'autoplay=0', 'autoplay=1' );
		$videoFrame[0].src = newSourceUrl;
	} );


	$videoModal.on('hide.bs.modal', function () {
		var newSourceUrl = $videoFrame[0].src;
		// Remove old autoplay if exists
		newSourceUrl = newSourceUrl.replace( 'autoplay=1', 'autoplay=0' );
		$videoFrame[0].src = newSourceUrl;
	});


};

},{}],19:[function(require,module,exports){
var Utils;

Utils = function( $ ) {
	var self = this;
	var isYTInserted = false;

	this.onYoutubeApiReady = function( callback ) {

		if ( ! isYTInserted ) {
			insertYTApi();
		}

		if ( window.YT && YT.loaded ) {
			callback( YT );
		} else {
			// If not ready check again by timeout..
			setTimeout( function() {
				self.onYoutubeApiReady( callback );
			}, 350 );
		}
	};

	var insertYTApi = function() {
		isYTInserted = true;

		$( 'script:first' ).before(  $( '<script>', { src: '' } ) );
	};
};

module.exports = Utils;

},{}]},{},[2])
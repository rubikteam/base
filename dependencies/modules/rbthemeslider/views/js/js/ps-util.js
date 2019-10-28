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

window.ps = window.ps || {};

(function ($) {
	var settings = typeof _psUtilSettings === 'undefined' ? {} : _psUtilSettings;
	ps.template = _.memoize(function ( id ) {
		var compiled,
			options = {
				evaluate:    /<#([\s\S]+?)#>/g,
				interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
				escape:      /\{\{([^\}]+?)\}\}(?!\})/g,
				variable:    'data'
			};

		return function ( data ) {
			compiled = compiled || _.template( $( '#tmpl-' + id ).html(), null, options );
			return compiled( data );
		};
	});

	ps.ajax = {
		settings: settings.ajax || {},

		post: function( action, data ) {
			return ps.ajax.send({
				data: _.isObject( action ) ? action : _.extend( data || {}, { action: action })
			});
		},

		send: function( action, options ) {
			var promise, deferred;
			if ( _.isObject( action ) ) {
				options = action;
			} else {
				options = options || {};
				options.data = _.extend( options.data || {}, { action: action });
			}

			options = _.defaults( options || {}, {
				type:    'POST',
				url:     ps.ajax.settings.url,
				context: this
			});

			deferred = $.Deferred( function( deferred ) {
				// Transfer success/error callbacks.
				if ( options.success )
					deferred.done( options.success );
				if ( options.error )
					deferred.fail( options.error );

				delete options.success;
				delete options.error;
				deferred.jqXHR = $.ajax( options ).done( function( response ) {
					if ( response === '1' || response === 1 )
						response = { success: true };

					if ( _.isObject( response ) && ! _.isUndefined( response.success ) )
						deferred[ response.success ? 'resolveWith' : 'rejectWith' ]( this, [response.data] );
					else
						deferred.rejectWith( this, [response] );
				}).fail( function() {
					deferred.rejectWith( this, arguments );
				});
			});

			promise = deferred.promise();
			promise.abort = function() {
				deferred.jqXHR.abort();
				return this;
			};

			return promise;
		}
	};

}(jQuery));
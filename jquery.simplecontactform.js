/*!
 * jQuery General Plugin for ratiopharm and others
 * Author: Tabea David | tabea.david@kf-interactive.com
 *
 * Requirements: loaded and working Modernizr and jQuery
 */

/*JSHINT globals*/
/*global Modernizr: true*/
/*global console: true*/

;(function($, window, document, undefined) {

	$.simplecontactform = function($element, options)  {

		// some default vars and self reference for scope issues
		var plugin = {};
		plugin.options = $.extend({}, $.simplecontactform.defaults, options);
		plugin.$form = $element;

		// all plugin methods
		plugin = $.extend(plugin, {

			load: function() {
				$('.' + plugin.options.form).on('submit', function(e) {
					e.preventDefault();

					$.post(e.target.action, $(this).serialize(), function(data) {
						$('.' + plugin.options.form).parent().replaceWith($(data));
						plugin.load();
					});
				});
			}


		});

		// run the plugin
		// ======================================================================
		plugin.load();
		// ======================================================================
	};


	// define the plugin defaults here
	$.simplecontactform.defaults = {
		version: '0.1',
		form: 'js-simplecontactform'
	};


	// jquery wrapper function
	$.fn.simplecontactform = function(options) {
		return this.each(function() {
			var simplecontactform = $.simplecontactform(this, options);
		});
	};

}(jQuery, window, document));

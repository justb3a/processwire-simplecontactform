/*!
* jQuery Plugin
* Author: Tabea David | tabea.david@kf-interactive.com
*/

;(function($, window, document, undefined) {

  $.simplecontactform = function($element, options)  {

    // some default vars and self reference for scope issues
    var plugin = {};
    plugin.options = $.extend({}, $.simplecontactform.defaults, options);
    plugin.$forms = $element;

    // all plugin methods
    plugin = $.extend(plugin, {

      load: function() {
        if (plugin.$forms.length) {
          // reinit the form-ajax submission
          plugin.$forms.off('submit.simplecontactform');
          plugin.$forms.on('submit.simplecontactform', function (e) {
            var $form = $(this);
            e.preventDefault();

            $.post(e.target.action, $form.serialize() + '&submit=submit', function (data) {
              $form.parent().replaceWith($(data));
              plugin.load();
            });
          });
        }
      }


    });

    // run the plugin
    // ======================================================================
    plugin.load();
    // ======================================================================
  };


  // define the plugin defaults here
  $.simplecontactform.defaults = {
    form: 'js-simplecontactform'
  };


  // jquery wrapper function
  $.fn.simplecontactform = function(options) {
    return this.each(function() {
      var simplecontactform = $.simplecontactform(this, options);
    });
  };

}(jQuery, window, document));

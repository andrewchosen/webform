/**
 * @file
 * JavaScript behaviors for options (admin) elements.
 */

(function ($, Drupal) {

  'use strict';


  /**
   * Attach handlers to options (admin) element.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.webformOptionsAdmin = {
    attach: function (context) {
      $(context).find('.js-form-type-webform-options .js-webform-options-value').once('webform-options-value').each(function() {
        // Target input name and not id because the id will be changing via
        // Ajax callbacks.
        var name = this.name;

        var $value = $(this);
        var $text = $('input[name="' + name.replace(/\[value\]$/, '[text]') + '"]');

        // On focus, determine if option value and option text are in-sync.
        $value.on('focus', function () {
          $value.data('webform_options_sync', $value.val() === $text.val());
        });

        // On keyup, if option value and option text are in-sync then set
        // option text to option value.
        $value.on('keyup', function () {
          if ($value.data('webform_options_sync')) {
            $text.val($value.val());
          }
        });

      })
    }
  };


})(jQuery, Drupal);
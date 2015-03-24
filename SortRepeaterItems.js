/* global console: true */

$(function(){
  var $content = $('.Inputfield_repeater_scfmessages .InputfieldContent').first();
  var $style = 'padding-right: 32px; float: right; margin-top: -32px; display: block;';
  $content.prepend('<a href="#" class="scf-change-order" style="' + $style + '">⇧ Sort Inverse</a>');

  $('.scf-change-order').on('click', function() {
    var $ul = $('.Inputfield_repeater_scfmessages ul.Inputfields').first();
    $ul.children().each(function(i, li){
      if (!$(li).hasClass('InputfieldRepeaterReady') && !$(li).hasClass('InputfieldRepeaterNewItem')) {
        $ul.prepend(li);
      }
    });
  });

});

import $ from 'jquery';

const contact = () => {

  // set a global jquery function for submitting the form
  $.simplecontactform = () => {
    const $forms = $('.js-simplecontactform');

    if ($forms.length) {
      // reinit the form-ajax submission
      $forms.off('submit.simplecontactform');
      $forms.on('submit.simplecontactform', (e) => {
        const $form = $(e.currentTarget);

        e.preventDefault();
        $.post(e.target.action, `${$form.serialize()}&submit=submit`, (data) => {
          $form.replaceWith($(data));
          $.simplecontactform();
        });
      });
    }
  };

  // and run it once
  $.simplecontactform();

};

export default contact;

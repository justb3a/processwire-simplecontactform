<?php namespace ProcessWire;

/**
 * Class SimpleContactFormConfig
 */
class SimpleContactFormConfig extends ModuleConfig {

  /**
   * array Default config values
   */
  public function getDefaults() {
    $saveMessagesTemplate = $this->templates->get('simple_contact_form_messages');

    return array(
      'sendEmails' => true,
      'emailTo' => '',
      'emailSubject' => 'New Web Contact Form Submission',
      'emailMessage' => '',
      'emailServer' => 'noreply@server.com',
      'emailReplyTo' => '',
      'allFields' => array(),
      'redirectPage' => '',
      'redirectSamePage' => true,
      'saveMessages' => false,
      'saveMessagesParent' => false,
      'saveMessagesTemplate' => $saveMessagesTemplate ? $saveMessagesTemplate->id : null,
      'saveMessagesScheme' => '',
      'antiSpamTimeMin' => '1',
      'antiSpamTimeMax' => '300',
      'antiSpamPerDay' => '3',
      'antiSpamExcludeIps' => '127.0.0.1',
      'cleanup' => 0
    );
  }

  /**
   * Retrieves the list of config input fields
   * Implementation of the ConfigurableModule interface
   *
   * @return InputfieldWrapper
   */
  public function getInputfields() {
    $allFields = isset($this->data['allFields']) ? $this->data['allFields'] : array();
    if (!is_array($allFields)) $allFields = explode(',', $this->data['allFields']); // @todo: deprecated

    // add prefix if necessary
    // @todo: deprecated
    foreach ($allFields as $key => $f) {
      if (!$this->fields->get($f) || $f === 'email') $allFields[$key] = 'scf_' . $f;
    }

    // get inputfields
    $inputfields = parent::getInputfields();

    // fieldset general
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = __('Email');

    // field send emails
    $field = $this->modules->get('InputfieldCheckbox');
    $field->name = 'sendEmails';
    $field->label = __('Send Emails?');
    $field->description = __('Should Emails be sent?');
    $field->value = 1;
    $field->columnWidth = 50;
    $fieldset->add($field);

    // field email subject
    $field = $this->modules->get('InputfieldText');
    $field->name = 'emailSubject';
    $field->label = __('Email: Subject');
    $field->columnWidth = 50;
    $field->required = 1;
    $field->requiredIf = 'sendEmails=1';
    $field->showIf = 'sendEmails=1';
    $fieldset->add($field);

    // field email to
    $field = $this->modules->get('InputfieldText');
    $field->name = 'emailTo';
    $field->label = __('Email: "To"-Address');
    $field->notes = __('Scheme: "Example <info@mail.com>, Name <noreply@bar.de>"');
    $field->columnWidth = 33;
    $field->required = 1;
    $field->requiredIf = 'sendEmails=1';
    $field->showIf = 'sendEmails=1';
    $fieldset->add($field);

    // field email server
    $field = $this->modules->get('InputfieldText');
    $field->name = 'emailServer';
    $field->label = __('Email: "From"-Address');
    $field->notes = __('Scheme: "FromName <noreply@mail.com>"');
    $field->columnWidth = 33;
    $field->required = 1;
    $field->requiredIf = 'sendEmails=1';
    $field->showIf = 'sendEmails=1';
    $fieldset->add($field);

    // field email reply to
    $field = $this->modules->get('InputfieldText');
    $field->name = 'emailReplyTo';
    $field->label = __('Email: "Reply-To"-Address');
    $field->notes = __('Optional; Scheme: "ReplyToName <reply@mail.com>"');
    $field->columnWidth = 34;
    $field->requiredIf = 'sendEmails=1';
    $field->showIf = 'sendEmails=1';
    $fieldset->add($field);
    $inputfields->add($fieldset);

    // fieldset general
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = __('Save Messages');

    // save messages field
    $field = $this->modules->get('InputfieldCheckbox');
    $field->name = 'saveMessages';
    $field->label = __('Save Messages');
    $field->description = __('Should the messages be saved?');
    $field->value = 1;
    $field->columnWidth = 50;
    $fieldset->add($field);

    // save messages parent field
    $field = $this->modules->get('InputfieldPageListSelect');
    $field->name = 'saveMessagesParent';
    $field->label = __('Select a parent for items');
    $field->description = __('All items created and managed will live under the parent you select here.');
    $field->notes = __('If no parent is selected, items will be placed as children of the root page (not recommended).');
    $field->required = 1;
    $field->requiredIf = 'saveMessages=1';
    $field->showIf = 'saveMessages=1';
    $field->columnWidth = 50;
    $fieldset->add($field);

    // save messages template field
    $field = $this->modules->get('InputfieldSelect');
    $field->name = 'saveMessagesTemplate';
    $field->label = __('Save messages template');
    $field->description = __('Choose the template, that is used to save messages.');
    foreach ($this->templates->getAll() as $key => $template) {
      if ($template->flags && Template::flagSystem) continue;
      $field->addOption($key, $template);
    }
    $field->required = 1;
    $field->requiredIf = 'saveMessages=1';
    $field->showIf = 'saveMessages=1';
    $field->columnWidth = 50;
    $fieldset->add($field);

    // save messages name scheme
    $field = $this->modules->get('InputfieldAsmSelect');
    $field->description = __('Add all fields which should be used as part of the page name. Choose from existing ones, you may have to add them first below and save.');
    $field->notes = __('The page name starts with a timestamp. All fields added above will be appended.');
    $field->addOption('', '');
    $field->label = __('Select page name fields');
    $field->attr('name', 'saveMessagesScheme');
    $field->showIf = 'saveMessages=1';
    $field->columnWidth = 50;
    foreach ($allFields as $aField) {
      if ($f = $this->fields->get($aField)) {
        $field->addOption($f->name, $f->name);
      }
    }
    // $field->attr('value', $allFields);
    $fieldset->add($field);

    $inputfields->add($fieldset);

    // fieldset fields
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = __('Fields');

    // allFields field
    $field = $this->modules->get('InputfieldAsmSelect');
    $field->description = __('Add all fields (choose from existing ones) which should be attached to the form.');
    $field->addOption('', '');
    $field->label = __('Select form fields');
    $field->attr('name', 'allFields');
    $field->required = true;
    $field->columnWidth = 50;
    foreach ($this->fields as $f) {
      // skip system fields
      if ($f->flags & Field::flagSystem || $f->flags & Field::flagPermanent) continue;
      $field->addOption($f->name, $f->name);
    }
    $field->attr('value', $allFields);
    $fieldset->add($field);

    // field addFields
    $field = $this->modules->get('InputfieldText');
    $field->name = 'addFields';
    $field->label = __('Create and add fields');
    $field->description = __('If you want to add non-existing fields, add them here as a comma-separated list.');
    $field->columnWidth = 50;
    $fieldset->add($field);

    $inputfields->add($fieldset);

    // redirect to the same page to get rid of POST vars
    $field = $this->modules->get('InputfieldCheckbox');
    $field->name = 'redirectSamePage';
    $field->label = __('Redirect to the same page after successfull submission');
    $field->description = __('OPTIONAL: If you prefer to stay on the same page without adding url parameter, just leave this field empty. Redirecting to the same page prevents form resubmission.');
    $field->value = 1;
    $field->columnWidth = 50;
    $fieldset->add($field);

    // redirect to a specific URL after successfull submission
    $field = $this->modules->get('InputfieldPageListSelect');
    $field->name = 'redirectPage';
    $field->label = __('Redirect to a specific page after successfull submission ');
    $field->description = __('OPTIONAL: If you prefer to stay on the same page, just leave this field empty.');
    $field->columnWidth = 50;
    $fieldset->add($field);

    // fieldset messages
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = __('Messages');
    $fieldset->showIf = 'sendEmails=1';
    $fieldset->collapsed = Inputfield::collapsedYes;

    // field email message
    $field = $this->modules->get('InputfieldTextarea');
    $field->name = 'emailMessage';
    $field->label = __('Email Message');
    $field->description = __('Email message (optional - overwrites basic mail template).');
    $field->notes = __('Use %fieldName% as placeholder, for example %fullName%.');
    $field->rows = 5;
    $fieldset->add($field);

    $inputfields->add($fieldset);

    // fieldset spam
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = __('Spam');
    $fieldset->collapsed = Inputfield::collapsedYes;

    // antiSpamTimeMin
    $field = $this->modules->get('InputfieldInteger');
    $field->name = 'antiSpamTimeMin';
    $field->label = __('Minimum Time');
    $field->description = __('It parses the time the user needs to fill out the form. If the time is below a minimum time, the submission is treated as Spam.');
    $field->notes = __('- in seconds -');
    $field->columnWidth = 50;
    $fieldset->add($field);

    // antiSpamTimeMax
    $field = $this->modules->get('InputfieldInteger');
    $field->name = 'antiSpamTimeMax';
    $field->label = __('Maximum Time');
    $field->description = __('It parses the time the user needs to fill out the form. If the time is over a maximum time, the submission is treated as Spam.');
    $field->notes = __('- in seconds -');
    $field->columnWidth = 50;
    $fieldset->add($field);

    // antiSpamPerDay
    $field = $this->modules->get('InputfieldInteger');
    $field->name = 'antiSpamPerDay';
    $field->label = __('Restrict Submissions');
    $field->description = __('How often the form is allowed to be submitted by a single IP address in the last 24 hours.');
    $field->columnWidth = 50;
    $fieldset->add($field);

    // antiSpamPerDay
    $field = $this->modules->get('InputfieldText');
    $field->name = 'antiSpamExcludeIps';
    $field->label = __('Exclude IPs');
    $field->description = __('Comma-Seperated list of IP addresses to be excluded from IP filtering.');
    $field->columnWidth = 50;
    $fieldset->add($field);

    $inputfields->add($fieldset);

    return $inputfields;
  }

}

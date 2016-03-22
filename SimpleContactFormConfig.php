<?php namespace ProcessWire;

/**
 * Class SimpleContactFormConfig
 */
class SimpleContactFormConfig extends ModuleConfig {

  /**
   * array Default config values
   */
  public function getDefaults() {
    return array(
      'emailTo' => '',
      'emailSubject' => 'New Web Contact Form Submission',
      'emailMessage' => '',
      'emailServer' => 'noreply@server.com',
      'allFields' => '',
      'saveMessages' => false,
      'saveMessagesParent' => false,
      'saveMessagesScheme' => '',
      'antiSpamTimeMin' => '1',
      'antiSpamTimeMax' => '90',
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
    // get submitted data
    $saveMessages = isset($this->data['saveMessages']) ? $this->data['saveMessages'] : false;
    $uninstall = isset($this->data['uninstall']) ? $this->data['uninstall'] : false;
    $allFields = isset($this->data['allFields']) ? $this->data['allFields'] : array();

    if (!is_array($allFields)) $allFields = explode(',', $this->data['allFields']); // @todo: deprecated

    // add prefix if necessary
    // @todo: deprecated
    foreach ($allFields as $key => $f) {
      if (!$this->fields->get($f) || $f === 'email') $allFields[$key] = 'scf_' . $f;
    }

    // get inputfields
    $inputfields = parent::getInputfields();

    // help instructions
    $help = $this->modules->get('InputfieldMarkup');
    $helpContent = <<<EOD
<h2>Instructions:</h2>
<ul>
<li>Complete the form below and submit it.</li>
<li>Create a template for your contact page (if you don't already have one) and add the following line:
<pre>echo \$modules->get('SimpleContactForm')->render();</pre></li>
</ul>
<p><a  target="_blank" href="https://github.com/justonestep/processwire-simplecontactform">Read more</a></p>
EOD;
    $help->value = $helpContent;
    $inputfields->append($help);

    // fieldset general
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = __('General');

    // field email subject
    $field = $this->modules->get('InputfieldText');
    $field->name = 'emailSubject';
    $field->label = __('Email Subject');
    $field->columnWidth = 50;
    $fieldset->add($field);

    // field email to
    $field = $this->modules->get('InputfieldText');
    $field->name = 'emailTo';
    $field->label = __('Email To Address');
    $field->description = __('Email address(es) of the recipient(s).');
    $field->notes = __('Scheme: "Example <info@mail.com>, Name <noreply@bar.de>"');
    $field->columnWidth = 50;
    $fieldset->add($field);

    // field email server
    $field = $this->modules->get('InputfieldText');
    $field->name = 'emailServer';
    $field->label = __('Email From Address');
    $field->description = __('Server address.');
    $field->notes = __('Scheme: "FromName <noreply@mail.com>"');
    $field->columnWidth = 50;
    $fieldset->add($field);

    // save messages field
    $field = $this->modules->get('InputfieldCheckbox');
    $field->name = 'saveMessages';
    $field->label = __('Save Messages');
    $field->description = __('Should the messages be saved?');
    $field->value = 1;
    $field->attr('checked', $saveMessages ? 'checked' : '');
    $field->columnWidth = 50;
    $fieldset->add($field);

    // save messages parent field
    $field = $this->modules->get('InputfieldPageListSelect');
    $field->name = 'saveMessagesParent';
    $field->label = __('Select a parent for items');
    $field->description = __('All items created and managed will live under the parent you select here.');
    $field->notes = __('If no parent is selected, then items will be placed as children of the root page (not recommended).');
    $field->required = 1;
    $field->requiredIf = 'saveMessages=1';
    $field->showIf = 'saveMessages=1';
    $field->columnWidth = 50;
    $fieldset->add($field);

    // save messages name scheme
    $field = $this->modules->get('InputfieldAsmSelect');
    $field->description = __('Add all fields (choose from existing ones, you may have to add them first below and save) which should be used as part of the page name.');
    $field->addOption('', '');
    $field->label = __('Select name fields');
    $field->attr('name', 'saveMessagesScheme');
    $field->showIf = 'saveMessages=1';
    $field->columnWidth = 50;
    foreach ($allFields as $aField) {
      $f = $this->fields->get($aField);
      $field->addOption($f->name, $f->name);
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

    // fieldset messages
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = __('Messages');
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
    $field->columnWidth = 50;
    $fieldset->add($field);

    // antiSpamTimeMax
    $field = $this->modules->get('InputfieldInteger');
    $field->name = 'antiSpamTimeMax';
    $field->label = __('Maximum Time');
    $field->description = __('It parses the time the user needs to fill out the form. If the time is over a maximum time, the submission is treated as Spam.');
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

    // fieldset uninstall
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = __('Uninstall');
    $fieldset->collapsed = Inputfield::collapsedYes;
    $fieldset->icon = 'times-circle';

    // field cleanup
    $field = $this->modules->get('InputfieldCheckbox');
    $field->name = 'saveMessages';
    $field->label = __('Save Messages');
    $field->description = __('If you check the following box all data containing files as well as database content will be permanently erased with no chance for recovery. It\'s recommended to make a backup before uninstalling this module.');
    $field->value = 1;
    $field->attr('checked', $uninstall ? 'checked' : '');
    $field->icon = 'trash';
    $fieldset->add($field);

    $inputfields->add($fieldset);

    return $inputfields;
  }

}

# SimpleContactForm for ProcessWire

Just a simple contact form including spam protection.
See [ProcessWire Forums - Support Board](https://processwire.com/talk/topic/8254-simple-contact-form-optional-twig-support/).

**TL;TR:**  
Install the module, fill in module settings, execute the module: `echo $modules->get('SimpleContactForm')->render();`.

## Module Settings

Fill in module settings, add all fields you want to attach to the form. You could either use existing fields or create new ones.
All new fields will be prefixed with `scf_`.

If you want to change the field settings, edit the field and change all settings there (e.g. fieldtype, required, length).

## Basic Usage

In the template add just one line to include the form:

```php
echo $modules->get('SimpleContactForm')->render();
```

If you want to send the form via ajax, include  _/site/modules/SimpleContactForm/resources/jquery.simplecontactform.js_ and call it:

```javascript
if ($('.js-simplecontactform').length) {
  $.simplecontactform($('.js-simplecontactform'));
}
```

To get just the necessary part, modify your template like this:

```php
<?php
if ($config->ajax) {
  $modules->get('SimpleContactForm')->render();
} else {
  // html, header, nav etc.
  $modules->get('SimpleContactForm')->render();
  // html, footer etc.
}
```

## Example Email Message

```
Hi!

You have received a new message:

Name: %fullName%,
Mail: %email%,
Phone Number: %phone%,
Message: %message%,

Date: %date%

ByeBye
```

## Logging

This module creates a log file named **simplecontactform-log.txt**.

In this file every action is logged and marked with ```[SUCCESS]``` or ```[FAILURE]``` 

If a message is treated as spam an entry in the spam log file will be added containing the reason why.

    I2016-02-09 11:02:15 [SUCCESS] Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36, 127.0.0.1, email@test.com
    2016-03-23 10:55:05 [FAILURE] This IP address was already marked as spam. IP: 127.0.0.1
    2016-03-21 12:35:05 [FAILURE] This IP address submitted this form too often. IP: 111.0.5.1

## Mark messages as spam

If the save messages setting is turned on you have the possibility to mark received messages as spam by adding the IP address to a blacklist.

To mark a message as spam edit the belonging page. Each page has a checkbox to matk the IP address as spam.



## Render multiple instances

You can pass options as an array which overwrites the defaults set up in module settings.
You don't have to pass all available keys, if you skip one, the input from module settings will be used (for example emailServer stays the same).

Here is an example:

```php
$scf = $modules->get('SimpleContactForm');

$options = array(
  'emailSubject' => 'Test Subject',
  'emailAdd' => true,
  'emailAddSubject' => 'hi there',
  'emailAddMessage' => 'Hi %scf_fullName%',
  'emailAddTo' => $input->scf_email
);

echo $scf->render($options);
```

### Available Keys

| key                | type    | description                                                                                                                            |
| ---                | ----    | -----------                                                                                                                            |
| allFields          | string  | comma-separated list of all fields                                                                                                     |
| submitName         | string  | if you use more than one form at one page, you have to pass the submit button name. That means, you have to use different submit names |
| emailMessage       | string  | email message                                                                                                                          |
| emailAddMessage    | string  | email message                                                                                                                          |
| emailSubject       | string  | email subject                                                                                                                          |
| emailTo            | string  | email address of recipient                                                                                                             |
| emailServer        | string  | server address                                                                                                                         |
| emailAdd           | boolean | set this to true if you want to send more than one email                                                                               |
| emailAddSubject    | string  | email subject                                                                                                                          |
| emailAddTo         | string  | email to                                                                                                                               |
| saveMessages       | boolean | whether to save received messages                                                                                                      |
| saveMessagesParent | integer | All items created and managed will live under the parent you select here                                                               |
| markup             | array   | overwrite markup                                                                                                                       |
| classes            | array   | overwrite classes                                                                                                                      |
| prependMarkup      | string  | prepend some markup/content                                                                                                            |
| appendMarkup       | string  | append some markup/content                                                                                                             |

To get an overview of what's possible, have a look at [How to overwrite classes and markup](https://github.com/justonestep/processwire-newslettersubscription/tree/develop#how-to-overwrite-classes-and-markup)


### More options for email templates, emailTo, ..

You have the full ProcessWire API available. Feel free to add any value you want to!

```php
$options['emailTo'] = $input->email;
$options['emailSubject'] = $page->title;
$options['emailMessage'] = $message;
```

`$message` could be a single partial including the whole message.

### More than one contact form on a page

If you need more than one contact form on a single page you have to pass the `$options['submitName']` option.
That means, you have to create a unique name for each submit button.

### Send more than one mail

Sometimes you need to send different emails to different people.
Here is an example how this works:

```php
$options['emailAdd'] = true;
$options['emailAddSubject'] = 'hi there';
$options['emailAddMessage'] = $anotherMessage;
$options['emailAddTo'] = $input->email_recommend;
```

It's important to set `$options['emailAdd] = true;`. Otherwise it won't send an additional email.
If you want to use the same email subject (for example), you can skip this part and it will use the default value.

## Add custom validation

You could add additional custom validation after the form was processed. This allows custom/extra validation and field manipulation.

```php
$this->addHookBefore('SimpleContactForm::processValidation', function(HookEvent $event) {
  $form = $event->arguments(0);
  $email = $form->get('scf_email');

  // add error if email address already exists
  if (count($this->users->find("email={$email->value}")) > 0) { // attach an error to the field
    $email->error(__('This email address is already registered.')); // it will be displayed along the field
  }
});
```

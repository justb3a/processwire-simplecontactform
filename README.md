# SimpleContactForm for ProcessWire

Compatibility: ProcessWire 3.x

Just a simple contact form including spam protection.
See [ProcessWire Forums - Support Board](https://processwire.com/talk/topic/8254-simple-contact-form-optional-twig-support/).

**Too long to read:**  
Install the module, fill in module settings, execute the module: `echo $modules->get('SimpleContactForm')->render();`.

### FAQ

- [How to overwrite classes and markup][1]
- [How to translate the spam message][2]
- [How to add a custom success message][3]

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

## Spam Protection: Hide honeypot field using CSS

Spam bots fill in automatically all form fields. By adding an invisible field you're able to trick the bots. The key to the honeypot technique is that the form only can be sent when the honeypot field remains empty otherwise it will be treated as spam.

The honeypot technique doesn't interfere with the user experience. It demands nothing extra of them like a captcha does. In fact, user won't even notice you're using it.

All that's required is a visually hidden form field. This form adds such a field named `scf-website` by default but you have to make sure to add a **display: none;** CSS rule on it.

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

> Admin > Setup > Logs > Simplecontactform-log

In this file every action is logged and marked with ```[SUCCESS]``` or ```[FAILURE]``` 

If a message is treated as spam an entry in the spam log file will be added containing the reason why.

    2016-02-09 11:02:15 [SUCCESS] Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36, 127.0.0.1, email@test.com
    2016-03-23 10:55:05 [FAILURE] This IP address was already marked as spam. IP: 127.0.0.1
    2016-03-21 12:35:05 [FAILURE] This IP address submitted this form too often. IP: 111.0.5.1

## Mark messages as spam

If the save messages setting is turned on you have the possibility to mark received messages as spam by adding the IP address to a blacklist.

To mark a message as spam edit the belonging page. Each page has a checkbox to matk the IP address as spam.

## How to translate

All phrases like email subjects are translatable. 
Add the module file to the language translator and start translating.
Phrases which don't exist in this file belong to the ProcessWire core.
For example the messages *Please enter a valid e-mail address* or *Missing required value*.

Relevant Files:

- site/modules/SimpleContactForm/lib/SpamProtection.php
- wire/modules/Inputfield/InputfieldEmail.module
- wire/core/InputfieldWrapper.php

Depending on the fields you added to the form there might be some other files.

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
  'emailAddReplyTo' => $input->scf_email,
  'emailAddTo' => $input->scf_email
);

echo $scf->render($options);
```

### Available Keys

| key                  | type    | description                                                                                                                             |
|----------------------|---------|---------------------------------------------------------------------------------------------------------------------------------------- |
| allFields            | string  | comma-separated list of all fields                                                                                                      |
| submitName           | string  | if you use more than one form at one page, you have to pass the submit button name. That means, you have to use different submit names  |
| btnClass             | string  | add custom submit button class(es), defaults to `button`                                                                                |
| btnText              | string  | add custom submit button text, defaults to `Send`                                                                                       |
| action               | string  | set specific form action, defaults to same page './'                                                                                    |
| sendEmails           | boolean | whether Emails should be sent                                                                                                           |
| redirectSamePage     | boolean | Redirect to the same page after successfull submission to prevent form resubmission. OPTIONAL.                                          |
| redirectPage         | integer | Redirect to a specific page after successfull submission. OPTIONAL: If you prefer to stay on the same page, just leave this field empty.|
| emailMessage         | string  | email message                                                                                                                           |
| successMessage       | string  | success message                                                                                                                         |
| errorMessage         | string  | error message                                                                                                                           |
| emailAddMessage      | string  | email message                                                                                                                           |
| emailSubject         | string  | email subject                                                                                                                           |
| emailTo              | string  | email address of recipient                                                                                                              |
| emailServer          | string  | server address                                                                                                                          |
| emailAdd             | boolean | set this to true if you want to send more than one email                                                                                |
| emailAddSubject      | string  | email subject                                                                                                                           |
| emailAddTo           | string  | email to                                                                                                                                |
| emailAddReplyTo      | string  | email reply to                                                                                                                          |
| saveMessages         | boolean | whether to save received messages                                                                                                       |
| saveMessagesParent   | integer | All items created and managed will live under the parent you select here                                                                |
| saveMessagesTemplate | integer | Template for received messages                                                                                                          |
| markup               | array   | overwrite markup                                                                                                                        |
| classes              | array   | overwrite classes                                                                                                                       |
| prependMarkup        | string  | prepend some markup/content                                                                                                             |
| appendMarkup         | string  | append some markup/content                                                                                                              |

To get an overview of what's possible, have a look at [How to overwrite classes and markup][1]

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

[1]: https://github.com/justb3a/processwire-simplecontactform/blob/master/doc/overwrite-classes-and-markup.md 'How to overwrite classes and markup'
[2]: https://github.com/justb3a/processwire-simplecontactform/blob/master/doc/spam-translate.md 'How to translate the spam message'
[3]: https://github.com/justb3a/processwire-simplecontactform/blob/master/doc/success-message.md 'How to add a custom success message'

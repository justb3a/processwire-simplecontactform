#### Navigation
- [Installation](installation.md)
- [Module Settings](settings.md)
- [Spam Protection](spam.md)
- [Basic Usage](usage-basic.md)
- [➻ Advanced Usage](usage-advanced.md)
- [Logging](logging.md)
- [Upgrade Notes](upgrade.md)

# Advanced Usage

Since version 0.2.0 it's possible to render multiple contact form instances.

Here is an example:

```php
$options = array(
  'templateName' => 'some-other-template',
  'emailSubject' => 'just another subject',
  'successMessage' => 'Thank you..'
);

echo $modules->get('SimpleContactForm')->render($options);
```

As you see, you can pass options as an array which overwrites the defaults set up in module settings.
You don't have to pass all available keys, if you skip one, the input from module settings will be used (for example emailServer stays the same).

## Available Keys

For a detailed description have a look at [Module Settings Documentation](settings.md).

| key | type | description |
| --- | ---- | ----------- |
| allFields | string | comma-separated list of all fields  | 
| requiredFields | string | comma-separated list of required fields | 
| emailFields | string | fields, which should be validated as email | 
| templateName | string | provide another template, you have to create it by your own (hint: duplicate generated template and adapt it) | 
| pageName | string | where to save messages - page | 
| repeaterName | string | where to save messages - repeater | 
| successMessage | string | success message | 
| errorMessage | string | error message | 
| emailMessage | string | email message | 
| emailSubject | string | email subject | 
| emailTo | string | email address of recipient | 
| emailServer | string | server address | 
| saveMessages | boolean | whether to save received messages | 
| useTwig | boolean | check if you use twig as template engine | 
| emailAdd | boolean | set this to true if you want to send more than one email | 
| emailAddMessage | string | email message | 
| emailAddSubject | string | email subject | 
| emailAddTo | string | email to | 
| submitName | string | if you use more than one form at one page, you have to pass the submit button name. That means, you have to use different submit names | 

## More options for email templates, emailTo, ..

You have the full ProcessWire API available. Feel free to add any value you want to!

```php
$options['emailTo'] = $input->email;
$options['emailSubject'] = $page->title;
$options['emailMessage'] = $message;
```

`$message` could be a single partial including the whole message.

## More than one contact form on a page

If you need more than one contact form on a single page you have to pass the `$options['submitName']` option.
That means, you have to create a unique name for each submit button.

## Send more than one mail

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

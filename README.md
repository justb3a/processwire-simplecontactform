# ProcessWire SimpleContactForm

## Overview:

Just a simple contact form using Twig (TemplateTwigReplace). Not more and not less.
See the instructions here: [TemplateTwigReplace](http://modules.processwire.com/modules/template-twig-replace)

Designed for use with ProcessWire 2.4
[http://processwire.com](http://processwire.com)

## Installation and Usage:

1. Clone the module and place SimpleContactForm in your site/modules/ directory. 

```
git clone https://github.com/justonestep/processwire-simplecontactform.git your/path/site/modules/SimpleContactForm
```

2. Login to ProcessWire admin and click Modules. 
3. Click "Check for new modules".
4. Click "install" next to the new SimpleContactForm module. 
5. Enter settings similar to the example below:

```
fullName: Firstname Lastname
emailTo: xxx@xxx.xx
emailSubject: New Web Contact Form Submission
successMessage: Thank you, your submission has been sent
errorMessage: Please verify the data you have entered
emailServer: noreply@xxx.xx
allFields: fullName,email,message
requiredFields: fullName,email,message
emailField: email
```

These fields will be rendered automatically.
Therefore a new template called `simple_contact_form.twig` will be created in your site/templates directory.
All fields in this new template will be simple inputs temporarily.
Once created you can/should modify the template as well as the fields to your own needs, 
just make sure to maintain the names of the fields.

6. Create a template for your contact form page (if you don't already have one).
7. Add the fields you want to use to this template as you are used to.
8. In the template add just one line to include the form:

```
{{modules.get('SimpleContactForm').render()}}
```

9. If you want to send the form via ajax, include jquery.simplecontactform.js and call it:

```
if ($('.js-simplecontactform').length) {
	$.simplecontactform($('.js-simplecontactform'));
}
```

To get just the necessary part, modify yout template like this:

{% if config.ajax %}
	{{modules.get('SimpleContactForm').render()}}
{% else %}
	{# normal stuff ... #}
{% endif %}

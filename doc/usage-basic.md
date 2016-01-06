#### Navigation
- [Installation](installation.md)
- [Module Settings](settings.md)
- [Spam Protection](spam.md)
- [➻ Basic Usage](usage-basic.md)
- [Advanced Usage](usage-advanced.md)
- [Logging](logging.md)
- [Upgrade Notes](upgrade.md)

# Basic Usage

1. Create a template for your contact form page (if you don't already have one).
2. Add the fields you want to use to this template as you are used to.
3. In the template add just one line to include the form:

	```php
	{{modules.get('SimpleContactForm').render()}} // twig
	$content = $modules->get('SimpleContactForm')->render(); //php
	```

4. If you want to send the form via ajax, include jquery.simplecontactform.js and call it:

	```javascript
	if ($('.js-simplecontactform').length) {
		$.simplecontactform($('.js-simplecontactform'));
	}
	```

	To get just the necessary part, modify yout template like this:

	```html
	{% if config.ajax %}
		{{modules.get('SimpleContactForm').render()}}
	{% else %}
		{# normal stuff ... #}
	{% endif %}
	```
	
**Note:** You have to include the module in your own template file.

**If the form was submitted successfully**

 * a success message is shown 
 * an email was sent
 * a log entry was created
 * if save messages setting is turned on the message got saved

 
**If not**

 * **either:** 
  * an error message is shown
 * **or:** 
  * the message was marked as spam
  * redirect to root page
  * a log entry was created   
	
## New Template "simple_contact_form"

* change type="input" due to your own needs
* hide following fields using css: scf-website, submitted
* make sure to maintain the names of the fields

tl;dr: These fields will be rendered automatically.
Therefore a new template called `simple_contact_form.[twig|php]` will be created in your `site/templates` directory.
All fields in this new template will be simple inputs temporarily.
Once created you can/should modify the template as well as the fields to your own needs, 
just make sure to maintain the names of the fields. 

## saveMessages enabled? 

* new page **scf-messages** (your-url.xx/scf-messages)
* new template **simple_contact_form_messages**

tl;dr: If `saveMessages` is enabled, a new page `scf-messages` will be created.
Also a new repeater field containing all set up fields is added.
For each received message a new repeater element is stored.
The new template `simple_contact_form_messages.[twig|php]` which is created for this page checks 
whether the current user is logged into the backend or not.
If that is the case all received messages will be listed.
Otherwise the user will be redirected to the root page.
Failing that the user will be redirected to the root page.
You can modify that templates for your own needs.

![Save Messages](https://github.com/justonestep/processwire-simplecontactform/blob/master/screens/received-messages.png)

## Add and remove fields

**(A) add fields**

* fields get added to database and template
* because I do not know your html structure the fields will be placed in a separate list at the beginning of your template
* make sure to adapt your html structure

**(B) remove fields**

* fields are not removed from database
* fields are removed from templates
* again I do not know your html structure so only the `label` and `input` entrys are removed
* maybe there will be an empty list entry, you have to remove it manually

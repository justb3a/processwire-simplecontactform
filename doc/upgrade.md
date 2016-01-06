#### Navigation
- [Installation](installation.md)
- [Module Settings](settings.md)
- [Spam Protection](spam.md)
- [Basic Usage](usage-basic.md)
- [Advanced Usage](usage-advanced.md)
- [Logging](logging.md)
- [➻ Upgrade Notes](upgrade.md)

# Upgrade Notes

## Upgrading from <= 0.0.9

If you upgrade an existing installation from 0.0.9 and below to the current version, there are some steps to be taken.

1. Upgrade the module source.
2. Visit the contact page in the frontend to receive all necessary database updates.
3. Edit page **scfmessages**, go to tab **Settings** and uncheck `Status Locked: Not editable` to be able to mark messages as spam.
4. Edit template file `simple_contact_form`. Due to the implemented CSRF validation change:

	```php
	- <input type="hidden" name="TOKEN819808161X1427202408" value="D/iICidOcpcXHyd0lKdDs84qEtNnK..41" class="_post_token">
	+ <input type='hidden' name='<?= $input->tokenName; ?>' value='<?= $input->tokenValue; ?>' class='_post_token' /> // php
	+ <input type='hidden' name='{{input->tokenName}}' value='{{input->tokenValue}}' class='_post_token' /> // twig
	```

5. Edit template file `simple_contact_form_messages` and change the following to receive all messages except the ones marked as spam:
 
	```php
	- <?php foreach ($currentPage->repeater_scfmessages->sort('-scf-date') as $message) { ?> // php
	+ <?php foreach ($currentPage->repeater_scfmessages->find("scf_spamIp=,scf_spamMail=")->sort('-scf-date') as $message) { ?> // php
	- {% for message in currentPage.repeater_scfmessages.sort('-scf_date') %} // twig
	+ {% for message in currentPage.repeater_scfmessages.find('scf_spamIp=,scf_spamMail=').sort('-scf_date') %} // twig
	```

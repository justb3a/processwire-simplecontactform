#### Navigation
- [Installation](installation.md)
- [Module Settings](settings.md)
- [➻ Spam Protection](spam.md)
- [Basic Usage](usage-basic.md)
- [Advanced Usage](usage-advanced.md)
- [Logging](logging.md)
- [Upgrade Notes](upgrade.md)

# Spam Protection

## Hints

If a message is treated as spam the user will be redirected directly to the root page and an entry in the spam log file will be added containing the reason why.

> ▸ site ▸ assets ▸ logs ▸ simplecontactform-spam-log.txt

	2015-04-09 12:40:53	Honeypot field was filled.

## Mark messages as spam

If the save messages setting is turned on you have the possibility to mark received messages as spam.
There are two options:

* mark as spam by mail address
* mark as spam by ip address

To mark a message as spam go to `Pages Tree > scfmessages > edit`. Each message has two checkboxes to mark, either the email address as spam or the ip address.

![Mark message as Spam](https://github.com/justonestep/processwire-simplecontactform/blob/master/screens/blacklist.png)

In order to get the latest message to the beginning of the list, click the **⇧ Sort Inverse** at the right corner of the list.

![Sort inverse](https://github.com/justonestep/processwire-simplecontactform/blob/master/screens/sort-inverse.png)

## CSRF token validation

This module uses CSRF token validation, if you don't know what it's all about, have a look [at the ProcessWire Forum](https://processwire.com/talk/topic/3779-use-csrf-in-your-own-forms/).

If you're upgrading from version 0.0.9 and below please follow the [instructions here](upgrade.md).

## Settings

| setting                         | description                                                                                                                                                                                                     | default                                  |
| ------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------- |
| antiSpamTimeMin                 | It parses the time the user needs to fill out the form.  If the time is below a minimum time, the submission is treated as Spam.                                                                                | `5`                                        |
| antiSpamTimeMax                 | It parses the time the user needs to fill out the form.  If the time is over a maximum time, the submission is treated as Spam.                                                                                 | `90`                                       |
| antiSpamPerDay                  | How often the form is allowed to be submitted by a single IP address in the last 24 hours.                                                                                                                      | `3`                                        |
| antiSpamExcludeIps              | Comma-Seperated list of IP addresses to be excluded from IP filtering.                                                                                                                                          | `127.0.0.1`                                |
| antiSpamCountAdditionalInputs   | Number of additional inputs. Spam bots often send more than the number of available fields. Default 5 (scf-date + scf-website + submitted + token + submit). AllFields will be added automatically.             | `5`                                        |



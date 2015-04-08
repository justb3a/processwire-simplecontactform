#### Navigation
- [Installation](installation.md)
- [Module Settings](settings.md)
- [➻ Spam Protection](spam.md)
- [Usage](usage.md)
- [Logging](logging.md)
- [Upgrade Notes](upgrade.md)
- [Clean Up](cleanup.md)


# SimpleContactForm for ProcessWire: Spam Protection

## Mark messages as spam

If the save messages setting is turned on you have the possibility to mark received messages as spam.
There are two options:

* mark as spam by mail address
* mark as spam by ip address

To mark a message as spam go to `Pages Tree > scfmessages > edit`. Each message has two checkboxes to mark, either the email address as spam or the ip address.

In order to get the latest message to the beginning of the list, click the **⇧ Sort Inverse** at the right corner of the list.

## CSRF token validation

This module uses CSRF token validation, if you don't know what it's all about, have a look [at the ProcessWire Forum](https://processwire.com/talk/topic/3779-use-csrf-in-your-own-forms/).

## Settings

| setting                         | description                                                                                                                                                                                                     | default                                  |
| ------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------- |
| antiSpamTimeMin                 | It parses the time the user needs to fill out the form.  If the time is below a minimum time, the submission is treated as Spam.                                                                                | 5                                        |
| antiSpamTimeMax                 | It parses the time the user needs to fill out the form.  If the time is over a maximum time, the submission is treated as Spam.                                                                                 | 90                                       |
| antiSpamPerDay                  | How often the form is allowed to be submitted by a single IP address in the last 24 hours.                                                                                                                      | 3                                        |
| antiSpamExcludeIps              | Comma-Seperated list of IP addresses to be excluded from IP filtering.                                                                                                                                          | 127.0.0.1                                |
| antiSpamCountAdditionalInputs   | Number of additional inputs. Spam bots often send more than the number of available fields. Default 5 (scf-date + scf-website + submitted + token + submit). AllFields will be added automatically.             | 5                                        |



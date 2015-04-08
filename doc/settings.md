#### Navigation
- [Installation](installation.md)
- [➻ Module Settings](settings.md)
- [Spam Protection](spam.md)
- [Usage](usage.md)
- [Logging](logging.md)
- [Upgrade Notes](upgrade.md)
- [Clean Up](cleanup.md)


# SimpleContactForm for ProcessWire: Module Settings

| setting                         | description                                                                                                                                                                                                     | default                                  |
| ------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------- |
| GENERAL                         | &#9662;                                                                                                                                                                                                         | -                                        |
| saveMessages                    | whether to save received messages                                                                                                                                                                               | true                                     |
| useTwig                         | Check if you use Twig as template engine                                                                                                                                                                        | false                                    |
| EMAIL                           | &#9662;                                                                                                                                                                                                         | -                                        |
| fullName                        | Full name of the recipient                                                                                                                                                                                      | Firstname Lastname                       |
| emailTo                         | E-mail address of the recipient                                                                                                                                                                                 | xxx@xxx.xx                               |
| emailSubject                    | E-Mail subject                                                                                                                                                                                                  | New Web Contact Form Submission          |
| emailServer                     | Server address                                                                                                                                                                                                  | noreply@server.com                       |
| FIELDS                          | &#9662;                                                                                                                                                                                                         | -                                        |
| allFields                       | Comma-separated list of all fields                                                                                                                                                                              | fullName,email,message                   |
| requiredFields                  | Comma-seperated list of required fields                                                                                                                                                                         | fullName,email,message                   |
| emailField                      | field, which should be validated as an E-mail address                                                                                                                                                           | email                                    |
| MESSAGES                        | &#9662;                                                                                                                                                                                                         | -                                        |
| successMessage                  | Success message                                                                                                                                                                                                 | Thank you, your submission has been sent |
| errorMessage                    | Error Message                                                                                                                                                                                                   | Please verify the data you have entered  |
| SPAM                            | &#9662;                                                                                                                                                                                                         | _                                        |
| antiSpamTimeMin                 | It parses the time the user needs to fill out the form.  If the time is below a minimum time, the submission is treated as Spam.                                                                                | 5                                        |
| antiSpamTimeMax                 | It parses the time the user needs to fill out the form.  If the time is over a maximum time, the submission is treated as Spam.                                                                                 | 90                                       |
| antiSpamPerDay                  | How often the form is allowed to be submitted by a single IP address in the last 24 hours.                                                                                                                      | 3                                        |
| antiSpamExcludeIps              | Comma-Seperated list of IP addresses to be excluded from IP filtering.                                                                                                                                          | 127.0.0.1                                |
| antiSpamCountAdditionalInputs   | Number of additional inputs. Spam bots often send more than the number of available fields. Default 5 (scf-date + scf-website + submitted + token + submit). AllFields will be added automatically.             | 5                                        |
| UNINSTALL                       | &#9662;                                                                                                                                                                                                         | -                                        |
| cleanup                         | If you check the following box all data containing files as well as database content will be permanently erased with no chance for recovery. It's recommended to make a backup before uninstalling this module. | false                                    |
| uninstall                       | Uninstall this module? After uninstalling, you may remove the modules files from the server if it is not in use by any other modules.                                                                           | false                                    |


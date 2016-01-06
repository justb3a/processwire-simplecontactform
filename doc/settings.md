#### Navigation
- [Installation](installation.md)
- [➻ Module Settings](settings.md)
- [Spam Protection](spam.md)
- [Basic Usage](usage-basic.md)
- [Advanced Usage](usage-advanced.md)
- [Logging](logging.md)
- [Upgrade Notes](upgrade.md)

# Module Settings

| setting                         | description                                                                                                                                                                                                     | default/example                                  |
| ------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------- |
| &#9662; **GENERAL**             | &nbsp;                                                                                                                                                                                                          | &nbsp;
| saveMessages                    | whether to save received messages                                                                                                                                                                               | true                                     |
| useTwig                         | Check if you use Twig as template engine                                                                                                                                                                        | false                                    |
| &nbsp;                          | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| &#9662; **EMAIL**               | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| emailTo                         | E-mail address of the recipient                                                                                                                                                                                 | Name \<xxx@xxx.xx>, Name2 \<email@another,.com>                               |
| fullName                        | Full name of the recipient                                                                                                                                                                                      | Firstname Lastname                       |
| emailSubject                    | E-Mail subject                                                                                                                                                                                                  | New Web Contact Form Submission          |
| emailServer                     | Server address                                                                                                                                                                                                  | Name \<noreply@server.com>                       |
| &nbsp;                          | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| &#9662; **FIELDS**              | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| allFields                       | Comma-separated list of all fields                                                                                                                                                                              | fullName,email,message                   |
| requiredFields                  | Comma-seperated list of required fields                                                                                                                                                                         | fullName,email,message                   |
| emailField                      | fields, which should be validated as an E-mail address                                                                                                                                                           | email                                    |
| &nbsp;                          | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| &#9662; **MESSAGES**            | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| successMessage                  | Success message                                                                                                                                                                                                 | Thank you, your submission has been sent |
| errorMessage                    | Error Message                                                                                                                                                                                                   | Please verify the data you have entered  |
| emailMessage                    | Email Message                                                                                                                                                                                                   | Name: %fullName%, mail address: %email%, date: %date%  |
| &nbsp;                          | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| &#9662; **SPAM**                | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| antiSpamTimeMin                 | It parses the time the user needs to fill out the form.  If the time is below a minimum time, the submission is treated as Spam.                                                                                | 5                                        |
| antiSpamTimeMax                 | It parses the time the user needs to fill out the form.  If the time is over a maximum time, the submission is treated as Spam.                                                                                 | 90                                       |
| antiSpamPerDay                  | How often the form is allowed to be submitted by a single IP address in the last 24 hours.                                                                                                                      | 3                                        |
| antiSpamExcludeIps              | Comma-Seperated list of IP addresses to be excluded from IP filtering.                                                                                                                                          | 127.0.0.1                                |
| antiSpamCountAdditionalInputs   | Number of additional inputs. Spam bots often send more than the number of available fields. Default 5 (scf-date + scf-website + submitted + token + submit). AllFields will be added automatically.             | 5                                        |
| &nbsp;                          | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| &#9662; **UNINSTALL**           | &nbsp;                                                                                                                                                                                                          | &nbsp;                                   |
| cleanup                         | If you check the following box all data containing files as well as database content will be permanently erased with no chance for recovery. It's recommended to make a backup before uninstalling this module. | false                                    |
| uninstall                       | Uninstall this module? After uninstalling, you may remove the modules files from the server if it is not in use by any other modules.                                                                           | false                                    |

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

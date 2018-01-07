# Changelog

### 1.0.8 (2018-01-07)

- add option to redirect to same page to prevent form resubmission

### 1.0.7 (2017-11-29)

- exclude email and password confirmation fields from spam count

### 1.0.6 (2017-11-01)

- add possibility to specify a template in which the form data should be saved

### 1.0.5 (2017-09-26)

- exclude checkbox fields (if unchecked) from spam count
  - because it's a standard browser behaviour that the value of a checkbox is only sent if the checkbox was checked
  - this leads to a mismatch while counting fields
  - the number of submitted fields does not match the number of fields which are present in the form

### 1.0.4 (2017-09-14)

- extend option `classes`
- allows customization of classes for form error and success

### 1.0.3 (2017-05-14)

- corrects typo in success message: **The translation string has been changed, some may need to translate it again!** // thanks @szabesz
- hides date field label // thanks @binarious
- makes send btn text translatable

### 1.0.2 (2017-03-18)

- adds option to specify a redirect page

### 1.0.1 (2016-12-16)

- adds setting `sendEmails`, define whether Emails should be sent

### 1.0.0 (2016-03-23)

- adds ProcessWire 3.x compatibility. Choose branch `2.x` if you want to use it with a version below 3.x
- outsources Mailer and SpamProtection
- adds namespaces support
- adds a Reply-To-Header (optional)

### 0.2.1 (2016-01-10)

- data will be stored, regardless whether a mail has been sent or not
- save corresponding log entry

### 0.2.0 (2016-01-06)

- supports multiple instances
- adds usage of full ProcessWire API in options
- allows to render more than one contact form on a page
- allows to send more than one email
- makes validation hookable

### 0.1.2 (2015-08-17)

- fixes CSRF Token validation
- allows to overwrite mail template
- allows multiple email recipients

### 0.1.1 (2015-04-09)

- updates template handling

### 0.1.0 (2015-03-24)

- additional spam protection and logging

### 0.0.9 (2015-03-09)

- little bugfixes

### 0.0.8 (2015-01-22)

- uses `wireMail` instead of php mail function

### 0.0.7 (2014-11-18)

- adding spam protection comparing post fields

### 0.0.6 (2014-11-11)

- adds php template support

### 0.0.5 (2014-10-05)

- extends anti spam functionality

### 0.0.4 (2014-09-30)

- adds spam protection, honeypot as well as timestamp comparison

### 0.0.3 (2014-08-18)

- save received messages

### 0.0.1 (2014-07-11)

- initial module

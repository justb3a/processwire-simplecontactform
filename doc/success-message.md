# How to add a custom success message

## Option 1

Simple set it as option parameter:

``` php
$modules->get('SimpleContactForm')->render(array(
  'emailMessage' => 'Custom Message'
));
```

## Option 2

Make it translatable:

``` php
$modules->get('SimpleContactForm')->render(array(
  'emailMessage' => __('Custom Message')
));
```

## Option 3

Set it as variable before:

``` php
$emailMessage = $page->title . ' test';
$content .= $modules->get('SimpleContactForm')->render(array('emailMessage' => $emailMessage));
```

## Option 4

Include the content from another file:

``` php
include('./message.php');
$content .= $modules->get('SimpleContactForm')->render(array('emailMessage' => $emailMessage));

// message.php
<?php namespace ProcessWire;

$emailMessage = $page->title . ' some content ' . $input->post->fieldname;
```

## Option 5

Include the content from another file using output buffering:
 
``` php
ob_start();
include('./message.php');
$emailMessage = ob_get_clean();
$content .= $modules->get('SimpleContactForm')->render(array('emailMessage' => $emailMessage));

// message.php
<?php namespace ProcessWire;
echo $page->title . ' test3 ' . $input->post->headline;
```

## Using twig

```html
{% set emailMessage %}{% include 'mails/recommend.twig' %}{% endset %}
{% set options = {
  'action': './#recommend',
  'emailMessage': emailMessage
} %}
{{modules.get('SimpleContactForm').render(options)}}

{# recommend.twig #}
...
{{ estate.title }}
{{ page.httpUrl }}
{% if input.scf_salutation == 1 %}xx{% elseif input.scf_salutation == 2 %}xx{% else %}xx{% endif %}
...
```

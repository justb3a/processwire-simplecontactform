# How to overwrite classes and markup

Using the `markup` and `classes` option you'll be able to overwrite the default markup.

**Example**

```php
$scf = $modules->get('SimpleContactForm');

$options = array(
  'markup' => array(
    'list' => "<div {attrs}>{out}</div>",
    'item' => "<p {attrs}>{out}</p>"
  ),
  'classes' => array(
    'form' => 'form form__whatever',
    'list' => 'list-item'
  )
);

echo $scf->render($options);
```

If you need additional markup before or after the form, you can use `prependMarkup` and `appendMarkup`.

Below is the list of all available customization options copied from [ProcessWire master][1].

```php
/**
 * Markup used during the render() method
 *
 */
static protected $defaultMarkup = array(
  'list' => "{out}\n",
  'item' => "\n\t<div {attrs}>\n{out}\n\t</div>",
  'item_label' => "\n\t\t<label class='form__item--label' for='{for}'>{out}</label>",
  'item_label_hidden' => "\n\t\t<label class='field__header field__header--hidden {class}'>{out}</label>",
  'item_content' => "{out}",
  'item_error' => "\n<p class='field--error--message'>{out}</p>",
  'item_description' => "\n<p class='field__description'>{out}</p>",
  'item_head' => "\n<h2>{out}</h2>",
  'item_notes' => "\n<p class='field__notes'>{out}</p>",
  'item_icon' => "",
  'item_toggle' => "",
  // ALSO: 
  // InputfieldAnything => array( any of the properties above to override on a per-Inputifeld basis)
);

/**
 * Classes used during the render() method
 *
 */
static protected $defaultClasses = array(
  'form' => 'form  js-simplecontactform', // additional clases for inputfieldform (optional)
  'form_error' => 'form--error--message',
  'form_success' => 'form--success--message',
  'list' => 'fields',
  'list_clearfix' => 'clearfix',
  'item' => 'form__item form__item--{name}',
  'item_label' => '', // additional classes for inputfieldheader (optional)
  'item_content' => '',  // additional classes for inputfieldcontent (optional)
  'item_required' => 'field--required', // class is for inputfield
  'item_error' => 'field--error', // note: not the same as markup[item_error], class is for inputfield
  'item_collapsed' => 'field--collapsed',
  'item_column_width' => 'field__column',
  'item_column_width_first' => 'field__column--first',
  'item_show_if' => 'field--show-if',
  'item_required_if' => 'field--required-if'
  // ALSO: 
  // InputfieldAnything => array( any of the properties above to override on a per-Inputifeld basis)
);
```

## Trouble Shooting

Normally you're able to override the markup on a per-Intputfield basis like mentioned above:

```php
'markup' => array(
   // @see: https://github.com/processwire/ProcessWire/blob/master/wire/core/InputfieldWrapper.php#L44
  'InputfieldSubmit' => array(
    // any of the properties above to override on a per-Inputifeld basis
  )
),
```

Example:

```php
$scf = $modules->get('SimpleContactForm');

$options = array(
  'btnClass' => 'btn btn-blue btn-effect',
  'btnText' => 'Send',
  'classes' => array(
    'item' => 'input-field'
  )
);

$content .= $scf->render($options);
```

However this doesn't seem to work in some cases (using InputfielSubmit or InputfieldButton).  
But you can override the `render` function of the specific class, in this example `InputfieldSubmit` (for example in `init.php`):

```php
$this->addHook('InputfieldSubmit::render', function(HookEvent $event) {
  if ($this->page->template->name === 'contact') { // adapt template name to compare with
    $parent = (object)$event->object;
    $attrs  = $parent->getAttributesString();
    $value = $parent->entityEncode($parent->attr('value'));
    $out = "<button $attrs><span><span>$value</span></span></button>";
    $event->return = $out; 
  }
});
```

One more example:

```php
$this->addHookBefore('Inputfield::render', function(HookEvent $event) {
  if ($this->page->template->name === 'contact') { // adapt template name to compare with
    $inputfield = $event->object;
    $inputfield->addClass('col-sm-8');
    $event->return = $inputfield;
  }
});
```

[1]: https://github.com/processwire/ProcessWire/blob/master/wire/core/InputfieldWrapper.php#L44 'ProcessWire master'

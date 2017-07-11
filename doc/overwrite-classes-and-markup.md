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
  'list' => "<ul {attrs}>{out}</ul>",
  'item' => "<li {attrs}>{out}</li>", 
  'item_label' => "<label class='InputfieldHeader ui-widget-header{class}' for='{for}'>{out}</label>",
  'item_label_hidden' => "<label class='InputfieldHeader InputfieldHeaderHidden ui-widget-header{class}'><span>{out}</span></label>",
  'item_content' => "<div class='InputfieldContent ui-widget-content{class}'>{out}</div>", 
  'item_error' => "<p class='InputfieldError ui-state-error'><i class='fa fa-fw fa-flash'></i><span>{out}</span></p>",
  'item_description' => "<p class='description'>{out}</p>", 
  'item_head' => "<h2>{out}</h2>", 
  'item_notes' => "<p class='notes'>{out}</p>",
  'item_icon' => "<i class='fa fa-fw fa-{name}'></i> ",
  'item_toggle' => "<i class='toggle-icon fa fa-fw fa-angle-down' data-to='fa-angle-down fa-angle-right'></i>", 
  // ALSO: 
  // InputfieldAnything => array( any of the properties above to override on a per-Inputifeld basis)
);

/**
 * Classes used during the render() method
 *
 */
static protected $defaultClasses = array(
  'form' => '', // additional clases for InputfieldForm (optional)
  'list' => 'Inputfields',
  'list_clearfix' => 'ui-helper-clearfix', 
  'item' => 'Inputfield {class} Inputfield_{name} ui-widget',
  'item_label' => '', // additional classes for InputfieldHeader (optional)
  'item_content' => '',  // additional classes for InputfieldContent (optional)
  'item_required' => 'InputfieldStateRequired', // class is for Inputfield
  'item_error' => 'ui-state-error InputfieldStateError', // note: not the same as markup[item_error], class is for Inputfield
  'item_collapsed' => 'InputfieldStateCollapsed',
  'item_column_width' => 'InputfieldColumnWidth',
  'item_column_width_first' => 'InputfieldColumnWidthFirst',
  'item_show_if' => 'InputfieldStateShowIf',
  'item_required_if' => 'InputfieldStateRequiredIf'
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

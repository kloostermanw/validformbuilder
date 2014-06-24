<?php
/**
 * ValidForm Builder - build valid and secure web forms quickly
 *
 * Copyright (c) 2009-2014 Neverwoods Internet Technology - http://neverwoods.com
 *
 * Felix Langfeldt <felix@neverwoods.com>
 * Robin van Baalen <robin@neverwoods.com>
 *
 * All rights reserved.
 *
 * This software is released under the GNU GPL v2 License <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 *
 * @package ValidForm
 * @author Felix Langfeldt <felix@neverwoods.com>, Robin van Baalen <robin@neverwoods.com>
 * @copyright 2009-2014 Neverwoods Internet Technology - http://neverwoods.com
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU GPL v2
 * @link http://validformbuilder.org
 * @version 3.0.0
 */

namespace ValidFormBuilder;

/**
 * ValidForm Builder main class - all the magic starts here.
 *
 * Create a new ValidForm Builder instance like this:
 * ```php
 * $objForm = new ValidForm("cool_new_form", "Please fill out my cool form", "/awesome-submits");
 * ```
 *
 * If the ValidForm class is extended, it will try to initialize a custom javascript class with the same name as well
 * If that javascript class is not available / does not exist, it will gracefully fallback on initializing
 * the standard ValidForm javascript class.
 * This enables high flexibility when extending ValidForm with custom functionality. Non real world example:
 *
 * ```
 * // On the server side
 * class FancyForm extends ValidForm
 * {
 *     public�function __construct()
 *     {
 *         return parent::__construct("fancyform");
 *     }
 * }
 * ```
 *
 * Corresponding client-side example
 * ```
 * // On the client side
 * function FancyForm () {
 *     alert('New fancyform');
 *
 *     return new ValidForm('fancyform', 'Fancy main alert');
 * }
 * ```
 *
 * The client-side 'class' FancyForm will be initiated since the server-side class name is FancyForm as well.�
 *
 * @package ValidForm
 * @author Felix Langfeldt <felix@neverwoods.com>
 * @author Robin van Baalen <robin@neverwoods.com>
 * @version 3.0.0
 *
 * @method string getDescription() getDescription() Returns the value of `$__description`
 * @method void setDescription() setDescription($strDescription) Overwrites the value of `$__description`
 * @method array getMeta() getMeta() Returns the value of `$__meta`
 * @method void setMeta() setMeta($arrMeta) Overwrites the value of `$__meta`
 * @method array getDefaults() getDefaults() Returns the value of `$__defaults`
 * @method string getAction() getAction() Returns the value of `$__action`
 * @method void setAction() setAction($strFormAction) Overwrites the value of `$__action`
 * @method string getSubmitLabel() getSubmitLabel() Returns the value of `$__submitlabel`
 * @method void setSubmitLabel() setSubmitLabel($strSubmitLabel) Overwrites the value of `$__submitlabel`
 * @method array getJsEvents() getJsEvents() Returns the value of `$__jsevents`
 * @method void setJsEvents() setJsEvents($arrJsEvents) Overwrites the value of `$__jsevents`.
 *     **Not recommended** use {@link ValidForm::addJsEvent()} instead.
 * @method \ValidFormBuilder\Collection getElements() getElements() Returns the internal elements collection
 * @method void setElements() setElements(Collection $objCollection) Overwrites the internal elements collection.
 * @method string getName() getName() Returns the name of this ValidForm instance
 * @method void setName() setName($strName) Overwrites the name of this ValidForm instance
 * @method string getMainAlert() getMainAlert() Returns the main alertof this ValidForm instance
 * @method void setMainAlert() setMainAlert($strMainAlert) Overwrites the main alert of this ValidForm instance
 * @method string getRequiredStyle() getRequiredStyle() Returns the value of `$__requiredstyle`
 * @method void setRequiredStyle() setRequiredStyle($strName) Overwrites the value of `$__requiredstyle`.
 * @method string getNoValuesMessage() getNoValuesMessage() Returns the value of `$__novaluesmessage`
 * @method void setNoValuesMessage() setNoValuesMessage($strName) Overwrites the value of `$__novaluesmessage`.
 * @method void setCachedFields() setCachedFields(Collection $objCollection) Overwrites the value of `$__cachedfields`.
 *     **Not recommended for API use**
 * @method void setUniqueId() setUniqueId($strName) Overwrites the value of `$__uniqueid`.
 */
class ValidForm extends ClassDynamic
{
    /**
     * Input type[text] with standard string validation
     * @var number
     */
    const VFORM_STRING = 1;
    /**
     * Textarea element type
     * @var number
     */
    const VFORM_TEXT = 2;
    /**
     * Input type[text] with numeric validation
     * @var number
     */
    const VFORM_NUMERIC = 3;
    /**
     * Input type[text] with integer validation
     * @var number
     */
    const VFORM_INTEGER = 4;
    /**
     * Input type[text] with single word validation
     * @var number
     */
    const VFORM_WORD = 5;
    /**
     * Input type[text] with email validation
     * @var number
     */
    const VFORM_EMAIL = 6;
    /**
     * Input type[password]
     * @var number
     */
    const VFORM_PASSWORD = 7;
    /**
     * Input type[text] with basic URL validation
     * @var number
     */
    const VFORM_SIMPLEURL = 8;
    /**
     * Input type[file]
     * @var number
     */
    const VFORM_FILE = 9;
    /**
     * Input type[radio]
     * @var number
     */
    const VFORM_BOOLEAN = 10;
    /**
     * Input type[text] with numeric validation
     * @deprecated Don't use VFORM_CAPTCHA. Captcha support is deprecated
     * @var number
     */
    const VFORM_CAPTCHA = 11;
    /**
     * Group element. Each added element is an input[type=radio]
     * @var number
     */
    const VFORM_RADIO_LIST = 12;
    /**
     * Group element. Each added element is an input[type=checkbox]
     * @var number
     */
    const VFORM_CHECK_LIST = 13;
    /**
     * Group element. Each added element is an option element
     * @var number
     */
    const VFORM_SELECT_LIST = 14;
    /**
     * Not an element. This creates a paragraph in between form fields.
     * @var number
     */
    const VFORM_PARAGRAPH = 15;
    /**
     * Input element
     * @var number
     */
    const VFORM_CURRENCY = 16;
    /**
     * Input type[text] with European date validation (dd/mm/yyyy)
     * @var number
     */
    const VFORM_DATE = 17;
    /**
     * Input type[text] with custom regular expression validation
     * @var number
     */
    const VFORM_CUSTOM = 18;
    /**
     * Textarea with custom regular expression validation
     * @var number
     */
    const VFORM_CUSTOM_TEXT = 19;
    /**
     * Textarea with basic input validation + HTML tags allowed
     * @var number
     */
    const VFORM_HTML = 20;
    /**
     * Input type[text] with url validation
     * @var number
     */
    const VFORM_URL = 21;
    /**
     * Input type[hidden]
     * @var number
     */
    const VFORM_HIDDEN = 22;

    /**
     * Check if this value is equal (case insensitive)
     * @var string
     */
    const VFORM_COMPARISON_EQUAL = "equal";
    /**
     * Check if this value is **not** equal (case insensitive)
     * @var string
     */
    const VFORM_COMPARISON_NOT_EQUAL = "notequal";
    /**
     * Check if this value is empty
     * @var stringq
     */
    const VFORM_COMPARISON_EMPTY = "empty";
    /**
     * Check if this value is **not** empty
     * @var string
     */
    const VFORM_COMPARISON_NOT_EMPTY = "notempty";
    /**
     * Check if this value is less than
     * @var string
     */
    const VFORM_COMPARISON_LESS_THAN = "lessthan";
    /**
     * Check if this value is greater than
     * @var string
     */
    const VFORM_COMPARISON_GREATER_THAN = "greaterthan";
    /**
     * Check if this value is less than or equal
     * @var string
     */
    const VFORM_COMPARISON_LESS_THAN_OR_EQUAL = "lessthanorequal";
    /**
     * Check if this value is greater than or equal
     * @var string
     */
    const VFORM_COMPARISON_GREATER_THAN_OR_EQUAL = "greaterthanorequal";
    /**
     * Check if the value contains this string (case insensitive)
     * @var string
     */
    const VFORM_COMPARISON_CONTAINS = "contains";
    /**
     * Check if the value **starts** with this string
     * @var string
     */
    const VFORM_COMPARISON_STARTS_WITH = "startswith";
    /**
     * Check if the value **ends** with this string
     * @var string
     */
    const VFORM_COMPARISON_ENDS_WITH = "endswith";
    /**
     * Check if the value matches your own custom regular expression
     * @var string
     */
    const VFORM_COMPARISON_REGEX = "regex";

    /**
     * ValidForm Condition match
     *
     * Match **all** of the defined conditions
     * @var string
     */
    const VFORM_MATCH_ALL = "all";
    /**
     * ValidForm Condition match
     *
     * Match **any** of the defined conditions
     * @var string
     */
    const VFORM_MATCH_ANY = "any";

    /**
     * The form's description paragraph content
     * @var string
     */
    protected $__description;
    /**
     * Form's custom meta like style, classes etc.
     * @var array
     */
    protected $__meta;
    /**
     * Default values array
     * @var array
     */
    protected $__defaults = array();
    /**
     * The HTML <form>-tag's 'action' attribute value
     * @var string
     */
    protected $__action;
    /**
     * The submit button's label
     * @var string
     */
    protected $__submitlabel;
    /**
     * An array of custom javascript events to include in javascript parsing
     * @var unknown
     */
    protected $__jsevents = array();
    /**
     * The main elements Collection
     * @var \ValidFormBuilder\Collection
     */
    protected $__elements;
    /**
     * The form's name
     * @var string
     */
    protected $__name;
    /**
     * The main alert to be shown when any alert has happened after trying to submit.
     * @var string
     */
    protected $__mainalert;
    /**
     * Define the field required style. Note: **This value will be passed to `sprintf`** so be sure to throw in an %s.
     *
     * Example:
     * ```php
     * $objForm->setRequiredStyle("%s *");
     *
     * //*** Now when a required field is parsed, it's output will be 'Label *' where the * is the 'required style'.
     * ```
     * @var string
     */
    protected $__requiredstyle;
    /**
     * This message is shown in `valuesAsHtml()` output when for
     * example an area or fieldset don't contain any submitted values.
     * @var string
     */
    protected $__novaluesmessage;
    /**
     * The collection of cached fields.
     * @var \ValidFormBuilder\Collection
     */
    private $__cachedfields = null;
    /**
     * A uniquely generated string to identify the form with.
     * @var string
     */
    private $__uniqueid;

    /**
     * Create a new ValidForm Builder instance
     *
     * @param string $name The form's name. This will also be the value of the name attribute in the generated HTML.
     * **Note**: At this moment, it is mandatory to enter a name even though the API states that it is optional. Check
     * [issue 8](https://github.com/neverwoods/validformbuilder/issues/8) for more details.
     * @param string $description Optional. A descriptive text shown above the form fields.
     * @param string $action The generated form element's `action` attribute.
     * @param array $meta Custom form meta array
     *
     * @return \ValidFormBuilder\ValidForm
     */
    public function __construct($name = null, $description = null, $action = null, $meta = array())
    {
        $this->__name = (is_null($name)) ? $this->__generateName() : $name;
        $this->__description = $description;
        $this->__submitlabel = "Submit";
        $this->__meta = $meta;
        $this->__uniqueid = (isset($meta["uniqueId"])) ? $meta["uniqueId"] : $this->generateId();

        $this->__elements = new Collection();

        if (is_null($action)) {
            $this->__action = $_SERVER["PHP_SELF"];
            if (isset($_SERVER["REQUEST_URI"])) {
                $this->__action = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
            }
        } else {
            $this->__action = $action;
        }
    }

    /**
     * Use an array to set default values on all the forms children.
     * The array's keys should be the form name to set the default value of, the value is the actual value
     * or values to set.
     *
     * Example 1 - Basic defaults:
     * ```php
     * //*** The form
     * $objCheck = $objForm->addField("cool", "Coolest PHP Library", ValidForm::VFORM_STRING);
     *
     * //*** Set field 'cool' default value to "ValidForm Builder"
     * $objForm->setDefaults([
     *     "cool" => "ValidForm Builder"
     * ]);
     * ```
     *
     * Example 2 - An array of defaults:
     * ```php
     * //*** The form
     * $objCheck = $objForm->addField("cool", "Cool checklist", ValidForm::VFORM_CHECK_LIST);
     * $objCheck->addField("Option 1", "option1");
     * $objCheck->addField("Option 2", "option2");
     * $objCheck->addField("Option 3", "option3");
     *
     * //*** Check options 2 and 3 by default using setDefaults()
     * $objForm->setDefaults([
     *     "cool" => ["option2", "option3"]
     * ]);
     * ```
     *
     * @param array $arrDefaults The array of default values. Keys are field names, values strings or arrays
     * @throws \InvalidArgumentException
     */
    public function setDefaults($arrDefaults = array())
    {
        if (is_array($arrDefaults)) {
            $this->__defaults = $arrDefaults;
        } else {
            throw new \InvalidArgumentException(
                "Invalid argument passed in to ValidForm->setDefaults(). Expected array got " .
                gettype($arrDefaults),
                E_ERROR
            );
        }
    }

    /**
     * Use addHtml to inject custom HTML code in the form.
     * @param string $html The HTML code to inject
     * @return \ValidFormBuilder\String
     */
    public function addHtml($html)
    {
        $objString = new String($html);
        $this->__elements->addObject($objString);

        return $objString;
    }

    /**
     * Add 'navigation' to the form. By navigation we mean a 'navigation div' at the buttom of the form containing
     * the submit button. This method is optional for customization purposes -- navigation is created automatically.
     * @param array $meta Array with meta data. Only the "style" attribute is supported as for now.
     * @return \ValidFormBuilder\Navigation
     */
    public function addNavigation($meta = array())
    {
        $objNavigation = new Navigation($meta);
        $this->__elements->addObject($objNavigation);

        return $objNavigation;
    }

    /**
     * Add a fieldset to the form field collection
     *
     * Example:
     * ```php
     * $objForm->addFieldset("Header for fieldset", "Note", "Cool fields contained by fieldset.");
     * ```
     * @param string $header The header for this fieldset
     * @param string $noteHeader An optional header for the 'note' block on the side of this fieldset
     * @param string $noteBody The optional body for the 'note block on the side of this fieldset
     * @param array $meta The meta array
     *
     * @return \ValidFormBuilder\Fieldset
     */
    public function addFieldset($header = null, $noteHeader = null, $noteBody = null, $meta = array())
    {
        $objFieldSet = new Fieldset($header, $noteHeader, $noteBody, $meta);
        $this->__elements->addObject($objFieldSet);

        return $objFieldSet;
    }

    /**
     * Add a hidden input field to the form collection.
     *
     * Hidden fields can be used for example to inject custom values in your post data and still have
     * them validated using ValidForm Builder.
     *
     * @param string $name The hidden field's `name` attribute
     * @param string $type Define a validation type using one of the ValidForm::VFORM_ constants. This does **not**
     * influence the fact that you're creating a hidden field. This is only used for validation of the hidden field's
     * content.
     * @param array $meta Optional meta array
     * @param boolean $blnJustRender If true, only create a `\ValidFormBuilder\Hidden` instance and return it. When
     * false, this `\ValidFormBuilder\Hidden` instance is added to the internal `elements` collection and will be
     * parsed when `toHtml()` is called.
     *
     * @return \ValidFormBuilder\Hidden
     */
    public function addHiddenField($name, $type, $meta = array(), $blnJustRender = false)
    {
        $objField = new Hidden($name, $type, $meta);

        if (! $blnJustRender) {
            // *** Fieldset already defined?
            $objFieldset = $this->__elements->getLast("ValidFormBuilder\\Fieldset");
            if ($this->__elements->count() == 0 || ! is_object($objFieldset)) {
                $objFieldset = $this->addFieldset();
            }

            $objField->setMeta("parent", $objFieldset, true);

            // *** Add field to the fieldset.
            $objFieldset->addField($objField);
        }

        return $objField;
    }

    /**
     * Use this utility method to only render \ValidFormBuilder\Element instances of the defined types.
     *
     * Elements rendered with this method aren't added to the internal elements collection.
     * @param string $name The element's name
     * @param string $label The element's label
     * @param number $type The element's validation type
     * @param array $validationRules Optional.Custom validation rules array
     * @param array $errorHandlers Custom error handling array
     * @param array $meta Optional. Meta data array
     *
     * @return \ValidFormBuilder\Element Returns null when no valid type is defined
     */
    public static function renderField($name, $label, $type, $validationRules, $errorHandlers, $meta)
    {
        $objField = null;
        switch ($type) {
            case static::VFORM_STRING:
            case static::VFORM_WORD:
            case static::VFORM_EMAIL:
            case static::VFORM_URL:
            case static::VFORM_SIMPLEURL:
            case static::VFORM_CUSTOM:
            case static::VFORM_CURRENCY:
            case static::VFORM_DATE:
            case static::VFORM_NUMERIC:
            case static::VFORM_INTEGER:
                $objField = new Text($name, $type, $label, $validationRules, $errorHandlers, $meta);
                break;
            case static::VFORM_PASSWORD:
                $objField = new Password($name, $type, $label, $validationRules, $errorHandlers, $meta);
                break;
            case static::VFORM_CAPTCHA:
                $objField = new Captcha($name, $type, $label, $validationRules, $errorHandlers, $meta);
                break;
            case static::VFORM_HTML:
            case static::VFORM_CUSTOM_TEXT:
            case static::VFORM_TEXT:
                $objField = new Textarea($name, $type, $label, $validationRules, $errorHandlers, $meta);
                break;
            case static::VFORM_FILE:
                $objField = new File($name, $type, $label, $validationRules, $errorHandlers, $meta);
                break;
            case static::VFORM_BOOLEAN:
                $objField = new Checkbox($name, $type, $label, $validationRules, $errorHandlers, $meta);
                break;
            case static::VFORM_RADIO_LIST:
            case static::VFORM_CHECK_LIST:
                $objField = new Group($name, $type, $label, $validationRules, $errorHandlers, $meta);
                break;
            case static::VFORM_SELECT_LIST:
                $objField = new Select($name, $type, $label, $validationRules, $errorHandlers, $meta);
                break;
            case static::VFORM_HIDDEN:
                $objField = new Hidden($name, $type, $meta);
                break;
            default:
                $objField = new Element($name, $type, $label, $validationRules, $errorHandlers, $meta);
                break;
        }

        return $objField;
    }

    /**
     * Add a new element to the internal elements collection
     *
     * *Example; add a text field*:
     * ```php
     * $objForm->addField(
     *     "first-name",
     *     "First name",
     *     ValidForm::VFORM_STRING,
     *     array(
     *         // Make this field required
     *         "required" => true
     *     ),
     *     array(
     *         // Show this error to indicate this is an required field if no value is submitted
     *         "required" => "This field is required"
     *     )
     * );
     * ```
     *
     * @param string $name The element's name
     * @param string $label The element's label
     * @param number $type The element's validation type
     * @param array $validationRules Optional.Custom validation rules array
     * @param array $errorHandlers Custom error handling array
     * @param array $meta Optional. Meta data array
     * @param boolean $blnJustRender When true, the element is not added to the internal elements collection.
     * `addField()` with `$blnJustRender` set to true is exactly the same as calling `ValidForm::renderField()`
     *
     * @return \ValidFormBuilder\Element Returns null when no valid type is defined
     */
    public function addField(
        $name,
        $label,
        $type,
        $validationRules = array(),
        $errorHandlers = array(),
        $meta = array(),
        $blnJustRender = false
    ) {
        $objField = static::renderField($name, $label, $type, $validationRules, $errorHandlers, $meta);

        $objField->setRequiredStyle($this->__requiredstyle);

        if (! $blnJustRender) {
            // *** Fieldset already defined?
            $objFieldset = $this->__elements->getLast("ValidFormBuilder\\Fieldset");
            if ($this->__elements->count() == 0 || ! is_object($objFieldset)) {
                $objFieldset = $this->addFieldset();
            }

            $objField->setMeta("parent", $objFieldset, true);

            // *** Add field to the fieldset.
            $objFieldset->addField($objField);
        }

        return $objField;
    }

    /**
     * Adds a \ValidFormBuilder\Paragraph object to the internal elements collection.
     *
     * This renders a paragraph inside the form. Formfields can be added before and after the paragraph.
     * **Example:**
     *
     * ```php
     * $objForm->addField("name", "Your Name", ValidForm::VFORM_STRING);
     * $objForm->addParagraph("Next, you should enter your last name.", "Enter your name!");
     * $objForm->addField("last-name", "Last Name", ValidForm::VFORM_STRING);
     * ```
     *
     * @param string $strBody Paragraph body
     * @param string $strHeader Optional header above the paragraph
     * @param array $meta Custom meta array
     * @return \ValidFormBuilder\Paragraph
     */
    public function addParagraph($strBody, $strHeader = "", $meta = array())
    {
        $objParagraph = new Paragraph($strHeader, $strBody, $meta);

        // *** Fieldset already defined?
        $objFieldset = $this->__elements->getLast("ValidFormBuilder\\Fieldset");
        if ($this->__elements->count() == 0 || ! is_object($objFieldset)) {
            $objFieldset = $this->addFieldset();
        }

        $objParagraph->setMeta("parent", $objFieldset, true);

        // *** Add field to the fieldset.
        $objFieldset->addField($objParagraph);

        return $objParagraph;
    }

    /**
     * Adds a <button> element to the internal fields collection.
     *
     * This generates a <button> element. You can customize this button using the meta array.
     * For example, you can add a custom class property to the button like this:
     *
     * ```php
     * $objForm->addButton(
     *     "Button label",
     *     array(
     *         // Set for example a Twitter Bootstrap class on this button
     *         "fieldclass" => "btn btn-large"
     *     )
     * );
     * ```
     *
     * @param string $strLabel The button's label
     * @param array $arrMeta The meta array
     * @return \ValidFormBuilder\Button
     */
    public function addButton($strLabel, $arrMeta = array())
    {
        $objButton = new Button($strLabel, $arrMeta);

        // *** Fieldset already defined?
        $objFieldset = $this->__elements->getLast("ValidFormBuilder\\Fieldset");
        if ($this->__elements->count() == 0 || ! is_object($objFieldset)) {
            $objFieldset = $this->addFieldset();
        }

        $objButton->setMeta("parent", $objFieldset, true);

        // *** Add field to the fieldset.
        $objFieldset->addField($objButton);

        return $objButton;
    }

    /**
     * Add an area to the internal elements collection.
     *
     * An area is about the same as a fieldset but an Area has more interactive options like the 'active'
     * property or even the 'dynamic' meta. Using the 'active' property, the area gets a label and checkbox
     * which activates or disables all the fields inside this area.
     *
     * *Example 1: Active area*
     * ```php
     * $objArea = $objForm->addArea("Disable fields", true, "fields-disabled");
     * $objArea->addField(
     *     "first-name",
     *     "First name",
     *     ValidForm::VFORM_STRING,
     *     array(
     *         // Make this field required
     *         "required" => true
     *     ),
     *     array(
     *         // Show this error to indicate this is an required field if no value is submitted
     *         "required" => "This field is required"
     *     )
     * );
     * $objArea->addField(
     *     "last-name",
     *     "Last name",
     *     ValidForm::VFORM_STRING,
     *     array(
     *         // Make this field required
     *         "required" => true
     *     ),
     *     array(
     *         // Show this error to indicate this is an required field if no value is submitted
     *         "required" => "This field is required"
     *     )
     * );
     * ```
     *
     * @param string $label The title of this area
     * @param string $active If true, the title has a checkbox which can enable or disable all child elements
     * @param string $name The ID of this area
     * @param string $checked Use in combination with $active; if true, the checkbox will be checked by default
     * @param array $meta The meta array
     *
     * @return \ValidFormBuilder\Area
     */
    public function addArea($label = null, $active = false, $name = null, $checked = false, $meta = array())
    {
        $objArea = new Area($label, $active, $name, $checked, $meta);

        $objArea->setRequiredStyle($this->__requiredstyle);

        // *** Fieldset already defined?
        $objFieldset = $this->__elements->getLast("ValidFormBuilder\\Fieldset");
        if ($this->__elements->count() == 0 || ! is_object($objFieldset)) {
            // No fieldset found in the elements collection, add a fieldset.
            $objFieldset = $this->addFieldset();
        }

        $objArea->setMeta("parent", $objFieldset, true);

        // *** Add field to the fieldset.
        $objFieldset->addField($objArea);

        return $objArea;
    }

    /**
     * Create a Multifield element
     *
     * Multifield elements allow you to combine multiple fields horizontally with one label.
     * For example, create a first name + last name field with label "Full name"
     *
     * ```php
     * $objMulti = $objForm->addMultifield("Full name");
     * // Note: when using addField on a multifield, we don't set a label!
     * $objMulti->addField(
     *     "first-name",
     *     ValidForm::VFORM_STRING,
     *     array(),
     *     array(),
     *     // Keep it short, this is just a first name field
     *     array("style" => "width: 50px")
     * );
     * $objMulti->addField("last-name", ValidForm::VFORM_STRING);
     * ```
     *
     * You can also combine select elements to create a date picker:
     *
     * ```php
     * $objMulti = $objForm->addMultiField("Birthdate");
     * $objMulti->addField(
     *     "year",
     *     ValidForm::VFORM_SELECT_LIST,
     *     array(),
     *     array(),
     *     array(
     *         "start" => 1920,
     *         "end" => 2014,
     *         // 'fieldstyle' gets applied on the <select>
     *         // regular 'style' applies on the wrapping <div>
     *         "fieldstyle" => "width: 75px"
     *     )
     * );
     * $objMulti->addField(
     *     "month",
     *     ValidForm::VFORM_SELECT_LIST,
     *     array(),
     *     array(),
     *     array(
     *         "start" => 01,
     *         "end" => 12,
     *         "fieldstyle" => "width: 75px"
     *     )
     * );
     * $objMulti->addField(
     *     "day",
     *     ValidForm::VFORM_SELECT_LIST,
     *     array(),
     *     array(),
     *     array(
     *         "start" => 1,
     *         "end" => 31,
     *         "fieldstyle" => "width: 75px"
     *     )
     * );
     * ```
     *
     * @param string $label
     * @param array $meta The meta array
     * @return \ValidFormBuilder\MultiField
     */
    public function addMultiField($label = null, $meta = array())
    {
        $objField = new MultiField($label, $meta);

        $objField->setRequiredStyle($this->__requiredstyle);

        // *** Fieldset already defined?
        $objFieldset = $this->__elements->getLast("ValidFormBuilder\\Fieldset");
        if ($this->__elements->count() == 0 || ! is_object($objFieldset)) {
            $objFieldset = $this->addFieldset();
        }

        $objField->setMeta("parent", $objFieldset, true);

        // *** Add field to the fieldset.
        $objFieldset->addField($objField);

        return $objField;
    }

    /**
     * Add a custom javascript event with corresponding callback function
     *
     * With this method you can either register a custom callback function on one of the predefined custom events
     * or you can register the callback function on a jQuery bindable event (e.g. jQuery().bind(eventName, callback)).
     *
     * These are predefined event hooks in the ValidForm Builder client-side library:
     *
     *  - beforeSubmit
     *  - beforeNextPage
     *  - afterNextPage
     *  - beforePreviousPage
     *  - afterPreviousPage
     *  - beforeAddPreviousButton
     *  - afterAddPreviousButton
     *  - beforeShowPage
     *  - afterShowPage
     *  - beforeAddPageNavigation
     *  - afterAddPageNavigation
     *  - beforeDynamicChange
     *  - afterDynamicChange
     *  - afterValidate
     *
     *
     * @param string $strEvent The event name
     * @param string $strMethod The name of the callback function
     */
    public function addJSEvent($strEvent, $strMethod)
    {
        $this->__jsevents[$strEvent] = $strMethod;
    }

    /**
     * Generate HTML output - build form
     *
     * @param string $blnClientSide Render javascript code or not, defaults to true
     * @param string $blnForceSubmitted This forces the form rendering as if the fields are submitted
     * @param string $strCustomJs Inject custom javascript to be executed while
     * initializing ValidForm Builder client-side.
     *
     * @return string Generated HTML output
     */
    public function toHtml($blnClientSide = true, $blnForceSubmitted = false, $strCustomJs = "")
    {
        $strOutput = "";

        if ($blnClientSide) {
            $strOutput .= $this->__toJS($strCustomJs);
        }

        $strClass = "validform vf__cf";

        if (is_array($this->__meta)) {
            if (isset($this->__meta["class"])) {
                $strClass .= " " . $this->__meta["class"];
            }
        }

        $strOutput .= "<form " .
        $strOutput .= "id=\"{$this->__name}\" " .
        $strOutput .= "method=\"post\" " .
        $strOutput .= "enctype=\"multipart/form-data\" " .
        $strOutput .= "action=\"{$this->__action}\" " .
        $strOutput .= "class=\"{$strClass}\"{$this->__metaToData()}>\n";

        // *** Main error.
        if ($this->isSubmitted() && ! empty($this->__mainalert)) {
            $strOutput .= "<div class=\"vf__main_error\"><p>{$this->__mainalert}</p></div>\n";
        }

        if (! empty($this->__description)) {
            $strOutput .= "<div class=\"vf__description\"><p>{$this->__description}</p></div>\n";
        }

        $blnNavigation = false;
        $strOutput .= $this->fieldsToHtml($blnForceSubmitted, $blnNavigation);

        if (! $blnNavigation) {
            $strOutput .= "<div class=\"vf__navigation vf__cf\">\n";
            $strOutput .= "<input type=\"submit\" value=\"{$this->__submitlabel}\" class=\"vf__button\" />\n";
            $strOutput .= "</div>\n";
        }

        $strOutput .= "<input type=\"hidden\" name=\"vf__dispatch\" value=\"{$this->__name}\" />\n";
        $strOutput .= "</form>";

        return $strOutput;
    }

    /**
     * This method generates HTML output for the current internal elements collection.
     *
     * This method is mostly used internally in the library and it's therefore not recommended to use this except
     * for these rare occasions when you only want the rendered fields an not all the meta surrounding the fields
     * like the form tag, description element and form error message.
     *
     * @param string $blnForceSubmitted This forces the form rendering as if the fields are submitted
     * @param string $blnNavigation This is a reference returning true if the form contains a navigation element
     * @return string Generated HTML output
     */
    public function fieldsToHtml($blnForceSubmitted = false, &$blnNavigation = false)
    {
        $strReturn = "";

        if (is_array($this->__defaults) && count($this->__defaults) > 0) {
            $objFields = $this->getCachedFields();
            foreach ($objFields as $objField) {
                $strName = $objField->getName(true); // true strips the [] off a checkbox's name

                if (array_key_exists($strName, $this->__defaults)) {
                    $varValue = $this->__defaults[$strName];

                    $blnDynamic = $objField->isDynamic();
                    if (! $blnDynamic) {
                        $objParent = $objField->getMeta("parent", null);
                        if (is_object($objParent)) {
                            $blnDynamic = $objParent->isDynamic();
                        }
                    }

                    if (is_array($varValue)
                        && !array_key_exists($strName . "_dynamic", $this->__defaults)
                        && $blnDynamic
                    ) {
                        $intDynamicCount = 0;
                        if (count($varValue) > 0) {
                            $intDynamicCount = count($varValue) - 1; // convert to zero-based
                        }

                        $this->__defaults[$strName . "_dynamic"] = $intDynamicCount;
                    }

                    $objField->setDefault($varValue);
                }
            }
        }

        foreach ($this->__elements as $element) {
            $strReturn .= $element->toHtml($this->isSubmitted($blnForceSubmitted), false, true, $blnForceSubmitted);

            if (get_class($element) == "ValidFormBuilder\\Navigation") {
                $blnNavigation = true;
            }
        }

        return $strReturn;
    }

    /**
     * Generate the Javascript output only.
     *
     * This is particulary useful when using ValidForm Builder in combination with AJAX form handling. In that
     * case you don't want to output the HTML together with the javascript.
     *
     * @param string $strCustomJs Inject custom javascript to be executed while
     * initializing ValidForm Builder client-side.
     * @return string
     */
    public function toJs($strCustomJs = "")
    {
        return $this->__toJS($strCustomJs, array(), true);
    }

    /**
     * Serialize, compress and encode the entire form including it's values
     *
     * @param boolean $blnSubmittedValues
     *            Whether or not to include submitted values or only serialize default values.
     * @return String Base64 encoded, gzcompressed, serialized form.
     */
    public function serialize($blnSubmittedValues = true)
    {
        // Validate & cache all values
        $this->valuesAsHtml($blnSubmittedValues); // Especially dynamic counters need this!

        return base64_encode(gzcompress(serialize($this)));
    }

    /**
     * Unserialize previously serialized ValidForm object
     *
     * @param string $strSerialized
     *            Serialized ValidForm object
     * @return ValidForm
     */
    public static function unserialize($strSerialized)
    {
        return unserialize(gzuncompress(base64_decode($strSerialized)));
    }

    /**
     * Check if the form is submitted by validating the value of the hidden
     * vf__dispatch field.
     *
     * @param boolean $blnForce
     *            Fake isSubmitted to true to force field values.
     * @return boolean [description]
     */
    public function isSubmitted($blnForce = false)
    {
        if (ValidForm::get("vf__dispatch") == $this->__name || $blnForce) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Fetch a cached flat collection of form fields instead of making
     * an expensive getFields() call and looping through all elements
     *
     * @return Collection
     */
    public function getCachedFields()
    {
        $objReturn = $this->__cachedfields;

        if (is_null($objReturn)) {
            $objReturn = $this->getFields();
        }

        return $objReturn;
    }

    public function getFields()
    {
        $objFields = new Collection();

        foreach ($this->__elements as $objFieldset) {
            if ($objFieldset->hasFields()) {
                foreach ($objFieldset->getFields() as $objField) {
                    if (is_object($objField)) {
                        if ($objField->hasFields()) {
                            if (get_class($objField) == "ValidFormBuilder\\Area" && $objField->isActive()) {
                                $objFields->addObject($objField);
                            }

                            foreach ($objField->getFields() as $objSubField) {
                                if (is_object($objSubField)) {
                                    if ($objSubField->hasFields()) {
                                        if (get_class($objSubField) == "ValidFormBuilder\\Area"
                                            && $objSubField->isActive()
                                        ) {
                                            $objFields->addObject($objSubField);
                                        }

                                        foreach ($objSubField->getFields() as $objSubSubField) {
                                            if (is_object($objSubSubField)) {
                                                $objFields->addObject($objSubSubField);
                                            }
                                        }
                                    } else {
                                        $objFields->addObject($objSubField);
                                    }
                                }
                            }
                        } else {
                            $objFields->addObject($objField);
                        }
                    }
                }
            } else {
                $objFields->addObject($objFieldset);
            }
        }

        $this->__cachedfields = $objFields;

        return $objFields;
    }

    public function getValidField($id)
    {
        $objReturn = null;

        $objFields = $this->getFields();
        foreach ($objFields as $objField) {
            if ($objField->getId() == $id) {
                $objReturn = $objField;
                break;
            }
        }

        if (is_null($objReturn)) {
            foreach ($objFields as $objField) {
                if ($objField->getName() == $id) {
                    $objReturn = $objField;
                    break;
                }
            }
        }

        return $objReturn;
    }

    public function getInvalidFields()
    {
        $objFields = $this->getFields();
        $arrReturn = array();

        foreach ($objFields as $objField) {
            $arrTemp = array();
            if (! $objField->isValid()) {
                $arrTemp[$objField->getName()] = $objField->getValidator()->getError();
                array_push($arrReturn, $arrTemp);
            }
        }

        return $arrReturn;
    }

    public function isValid()
    {
        return $this->__validate();
    }

    public function valuesAsHtml($hideEmpty = false, $collection = null)
    {
        $strTable = "\t<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"validform\">\n";
        $strTableOutput = "";
        $collection = (! is_null($collection)) ? $collection : $this->__elements;

        foreach ($collection as $objFieldset) {
            $strSet = "";
            $strTableOutput .= $this->fieldsetAsHtml($objFieldset, $strSet, $hideEmpty);
        }

        if (! empty($strTableOutput)) {
            return $strTable . $strTableOutput . "</table>";
        } else {
            if (! empty($this->__novaluesmessage)) {
                return $strTable . "<tr><td colspan=\"3\">{$this->__novaluesmessage}</td></tr></table>";
            } else {
                return "";
            }
        }
    }

    public function fieldsetAsHtml($objFieldset, &$strSet, $hideEmpty = false)
    {
        $strTableOutput = "";

        foreach ($objFieldset->getFields() as $objField) {
            if (is_object($objField) && get_class($objField) !== "ValidFormBuilder\\Hidden") {
                //*** Get the string value. If it's an array, implode with ','
                $strValue = $objField->getValue();
                if (is_array($strValue)) {
                    $strValue = implode(", ", $strValue);
                }

                if ((! empty($strValue) && $hideEmpty) || (! $hideEmpty && ! is_null($strValue))) {
                    if ($objField->hasFields()) {
                        switch (get_class($objField)) {
                            case "ValidFormBuilder\\MultiField":
                                $strSet .= $this->multiFieldAsHtml($objField, $hideEmpty);

                                break;
                            default:
                                $strSet .= $this->areaAsHtml($objField, $hideEmpty);
                        }
                    } else {
                        $strSet .= $this->fieldAsHtml($objField, $hideEmpty);
                    }
                }

                if ($objField->isDynamic()) {
                    $intDynamicCount = $objField->getDynamicCount();

                    if ($intDynamicCount > 0) {
                        for ($intCount = 1; $intCount <= $intDynamicCount; $intCount ++) {
                            switch (get_class($objField)) {
                                case "ValidFormBuilder\\MultiField":
                                    $strSet .= $this->multiFieldAsHtml($objField, $hideEmpty, $intCount);

                                    break;

                                case "ValidFormBuilder\\Area":
                                    $strSet .= $this->areaAsHtml($objField, $hideEmpty, $intCount);

                                    break;

                                default:
                                    $strSet .= $this->fieldAsHtml($objField, $hideEmpty, $intCount);
                            }
                        }
                    }
                }
            }
        }

        $strHeader = $objFieldset->getHeader();
        if (! empty($strHeader) && ! empty($strSet)) {
            $strTableOutput .= "<tr>";
            $strTableOutput .= "<td colspan=\"3\">&nbsp;</td>\n";
            $strTableOutput .= "</tr>";
            $strTableOutput .= "<tr>";
            $strTableOutput .= "<td colspan=\"3\"><b>{$strHeader}</b></td>\n";
            $strTableOutput .= "</tr>";
        }

        if (! empty($strSet)) {
            $strTableOutput .= $strSet;
        }

        return $strTableOutput;
    }

    private function areaAsHtml($objField, $hideEmpty = false, $intDynamicCount = 0)
    {
        $strReturn = "";
        $strSet = "";

        if ($objField->hasContent($intDynamicCount)) {
            foreach ($objField->getFields() as $objSubField) {
                if (get_class($objSubField) !== "ValidFormBuilder\\Paragraph") {
                    switch (get_class($objSubField)) {
                        case "ValidFormBuilder\\MultiField":
                            $strSet .= $this->multiFieldAsHtml($objSubField, $hideEmpty, $intDynamicCount);

                            break;
                        default:
                            $strSet .= $this->fieldAsHtml($objSubField, $hideEmpty, $intDynamicCount);

                            // Support nested dynamic fields.
                            if ($objSubField->isDynamic()) {
                                $intDynamicCount = $objSubField->getDynamicCount();
                                for ($intCount = 1; $intCount <= $intDynamicCount; $intCount ++) {
                                    $strSet .= $this->fieldAsHtml($objSubField, $hideEmpty, $intCount);
                                }
                            }
                    }
                }
            }
        }

        $strLabel = $objField->getShortLabel();
        if (! empty($strSet)) {

            if (! empty($strLabel)) {
                $strReturn = "<tr>";
                $strReturn .= "<td colspan=\"3\" style=\"white-space:nowrap\" class=\"vf__area_header\">" .
                $strReturn .= "<h3>{$strLabel}</h3>" .
                $strReturn .= "</td>\n";
                $strReturn .= "</tr>";
            }

            $strReturn .= $strSet;
        } else {
            if (! empty($this->__novaluesmessage) && $objField->isActive()) {
                $strReturn = "<tr>";
                $strReturn .= "<td colspan=\"3\" style=\"white-space:nowrap\" class=\"vf__area_header\">" .
                $strReturn .= "<h3>{$strLabel}</h3>" .
                $strReturn .= "</td>\n";
                $strReturn .= "</tr>";

                return $strReturn . "<tr><td colspan=\"3\">{$this->__novaluesmessage}</td></tr>";
            } else {
                return "";
            }
        }

        return $strReturn;
    }

    private function multiFieldAsHtml($objField, $hideEmpty = false, $intDynamicCount = 0)
    {
        $strReturn = "";

        if ($objField->hasContent($intDynamicCount)) {
            if ($objField->hasFields()) {
                $strValue = "";
                $objSubFields = $objField->getFields();

                $intCount = 0;
                foreach ($objSubFields as $objSubField) {
                    $intCount ++;

                    if (get_class($objSubField) == "ValidFormBuilder\\Hidden" && $objSubField->isDynamicCounter()) {
                        continue;
                    }

                    $varValue = $objSubField->getValue($intDynamicCount);
                    $strValue .= (is_array($varValue)) ? implode(", ", $varValue) : $varValue;
                    $strValue .= ($objSubFields->count() > $intCount) ? " " : "";
                }

                $strValue = trim($strValue);
                $strLabel = $objField->getShortLabel();

                if ((! empty($strValue) && $hideEmpty) || (! $hideEmpty && ! empty($strValue))) {
                    $strValue = nl2br($strValue);
                    $strValue = htmlspecialchars($strValue, ENT_QUOTES);

                    $strReturn .= "<tr class=\"vf__field_value\">";
                    $strReturn .= "<td valign=\"top\" " .
                    $strReturn .= "style=\"white-space:nowrap; padding-right: 20px\" " .
                    $strReturn .= "class=\"vf__field\">" .
                    $strReturn .= $strLabel .
                    $strReturn .= "</td>" .
                    $strReturn .= "<td valign=\"top\" class=\"vf__value\">" .
                    $strReturn .= "<strong>" . $strValue . "</strong>" .
                    $strReturn .= "</td>\n";
                    $strReturn .= "</tr>";
                }
            }
        }

        return $strReturn;
    }

    private function fieldAsHtml($objField, $hideEmpty = false, $intDynamicCount = 0)
    {
        $strReturn = "";

        $strFieldName = $objField->getName();
        $strLabel = $objField->getShortLabel(); // Passing 'true' gets the short label if available.
        $varValue = ($intDynamicCount > 0) ? $objField->getValue($intDynamicCount) : $objField->getValue();
        $strValue = (is_array($varValue)) ? implode(", ", $varValue) : $varValue;

        if ((! empty($strValue) && $hideEmpty) || (! $hideEmpty && ! is_null($strValue))) {
            if ((get_class($objField) == "ValidFormBuilder\\Hidden") && $objField->isDynamicCounter()) {
                return $strReturn;
            } else {
                switch ($objField->getType()) {
                    case static::VFORM_BOOLEAN:
                        $strValue = ($strValue == 1) ? "yes" : "no";
                        break;
                }

                if (empty($strLabel) && empty($strValue)) {
                    // *** Skip the field.
                } else {
                    $strValue = nl2br($strValue);
                    $strValue = htmlspecialchars($strValue, ENT_QUOTES);

                    $strReturn .= "<tr class=\"vf__field_value\">";
                    $strReturn .= "<td valign=\"top\" style=\"padding-right: 20px\" class=\"vf__field\">" .
                    $strReturn .= $strLabel .
                    $strReturn .= "</td>" .
                    $strReturn .= "<td valign=\"top\" class=\"vf__value\">" .
                    $strReturn .= "<strong>" . $strValue . "</strong>" .
                    $strReturn .= "</td>\n";
                    $strReturn .= "</tr>";
                }
            }
        }

        return $strReturn;
    }

    /**
     * Generate a unique ID
     * @param number $intLength ID length
     * @return string Generated ID
     */
    public function generateId($intLength = 8)
    {
        $strChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $strReturn = '';

        srand((double) microtime() * 1000000);

        for ($i = 1; $i <= $intLength; $i ++) {
            $intNum = rand() % (strlen($strChars) - 1);
            $strTmp = substr($strChars, $intNum, 1);
            $strReturn .= $strTmp;
        }

        return $strReturn;
    }

    /**
     * Returns the auto-generated unique ID of this form instance.
     * @return string
     */
    public function getUniqueId()
    {
        return $this->__uniqueid;
    }

    /**
     * Use this to set a (custom) unqiue ID for the form.
     *
     * This sets the internal $__uniqueid parameter. Used internally.
     *
     * @param string $strId Optional unique ID. If not set, a unique ID will be
     * generated with {@link ValidForm::generateId()}
     */
    protected function __setUniqueId($strId = "")
    {
        $this->__uniqueid = (empty($strId)) ? $this->generateId() : $strId;
    }

    /**
     * Read parameters from the `$_REQUEST` array with an optional fallback value
     *
     * @param string $param The parameter to read
     * @param string $replaceEmpty Optional replace value when parameter is not available or empty
     * @return Ambigous <string, array>
     */
    public static function get($param, $replaceEmpty = "")
    {
        $strReturn = (isset($_REQUEST[$param])) ? $_REQUEST[$param] : "";

        if (empty($strReturn) && ! is_numeric($strReturn) && $strReturn !== 0) {
            $strReturn = $replaceEmpty;
        }

        return $strReturn;
    }

    protected function __toJS($strCustomJs = "", $arrInitArguments = array(), $blnRawJs = false)
    {
        $strReturn = "";
        $strJs = "";

        // *** Loop through all form elements and get their javascript code.
        foreach ($this->__elements as $element) {
            $strJs .= $element->toJS();
        }

        // *** Form Events.
        foreach ($this->__jsevents as $event => $method) {
            $strJs .= "\tobjForm.addEvent(\"{$event}\", {$method});\n";
        }

        // Indent javascript
        $strJs = str_replace("\n", "\n\t", $strJs);

        if (! $blnRawJs) {
            $strReturn .= "<script type=\"text/javascript\">\n";
            $strReturn .= "// <![CDATA[\n";
        }

        $strReturn .= "function {$this->__name}_init() {\n";

        $strCalledClass = static::getStrippedClassName(get_called_class());
        $strArguments = "\"{$this->__name}\", \"{$this->__mainalert}\"";
        if (count($arrInitArguments) > 0) {
            $strArguments = "\"{$this->__name}\", \"{$this->__mainalert}\", " . json_encode($arrInitArguments);
        }

        /**
         * If the ValidForm class is extended, try to initialize a custom javascript class with the same name as well
         * If that javascript class is not available / does not exist, continue initializing ValidForm as usual.
         */
        if ($strCalledClass !== "ValidForm") {
            $strReturn .= "\tvar objForm = (typeof {$strCalledClass} !== \"undefined\") ? " .
            $strReturn .= "new {$strCalledClass}({$strArguments}) : " .
            $strReturn .= "new ValidForm(\"{$this->__name}\", \"{$this->__mainalert}\");\n";
        } else {
            $strReturn .= "\tvar objForm = new ValidForm(\"{$this->__name}\", \"{$this->__mainalert}\");\n";
        }

        $strReturn .= $strJs;
        if (! empty($strCustomJs)) {
            $strReturn .= $strCustomJs;
        }

        $strReturn .= "\tobjForm.initialize();\n";
        $strReturn .= "\t$(\"#{$this->__name}\").data(\"vf__formElement\", objForm);";
        $strReturn .= "};\n";
        $strReturn .= "\n";
        $strReturn .= "try {\n";
        $strReturn .= "\tjQuery(function(){\n";
        $strReturn .= "\t\t{$this->__name}_init();\n";
        $strReturn .= "\t});\n";
        $strReturn .= "} catch (e) {\n";
        $strReturn .= "\talert('Exception caught while initiating ValidForm:\\n\\n' + e.message);\n";
        $strReturn .= "}\n";

        if (! $blnRawJs) {
            $strReturn .= "// ]]>\n";
            $strReturn .= "</script>\n";
        }

        return $strReturn;
    }

    /**
     * Generate a random name for the form.
     *
     * @return string the random name
     */
    protected function __generateName()
    {
        return strtolower(static::getStrippedClassName(get_class($this))) . "_" . mt_rand();
    }

    /**
     * Generate a random number between 10000000 and 90000000.
     *
     * @return int the generated random number
     */
    private function __random()
    {
        return rand(10000000, 90000000);
    }

    private function __validate()
    {
        $blnReturn = true;

        foreach ($this->__elements as $element) {
            if (! $element->isValid()) {
                $blnReturn = false;
                break;
            }
        }

        return $blnReturn;
    }

    private function __metaToData()
    {
        $strReturn = "";

        if (isset($this->__meta["data"]) && is_array($this->__meta["data"])) {
            foreach ($this->meta["data"] as $strKey => $strValue) {
                $strReturn .= "data-" . strtolower($strKey) . "=\"" . $strValue . "\" ";
            }
        }

        return $strReturn;
    }

    public static function getStrippedClassName($classname)
    {
        $pos = strrpos($classname, '\\');
        if ($pos) {
            return substr($classname, $pos + 1);
        }

        return $pos;
    }
}

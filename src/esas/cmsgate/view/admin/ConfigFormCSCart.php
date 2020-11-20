<?php
/*
* @info     Платёжный модуль Hutkigrosh для JoomShopping
* @package  hutkigrosh
* @author   esas.by
* @license  GNU/GPL
*/

namespace esas\cmsgate\view\admin;

use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\view\admin\fields\ConfigField;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldPassword;
use esas\cmsgate\view\admin\fields\ConfigFieldRichtext;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\fields\ListOption;

class ConfigFormCSCart extends ConfigFormHtml
{
    private $orderStatuses;

    /**
     * ConfigFormCSCart constructor.
     */
    public function __construct($formKey, $managedFields)
    {
        parent::__construct($managedFields, $formKey, null, null);
        foreach (fn_get_statuses(STATUSES_ORDER) as $orderStatus) {
            $this->orderStatuses[] = new ListOption($orderStatus['status'], $orderStatus['description']);
        }
    }

    /**
     * @return ListOption[]
     */
    public function createStatusListOptions()
    {
        return $this->orderStatuses;
    }

    private static function attributeName(ConfigField $configField)
    {
        return attribute::name("payment_data[processor_params][" . $configField->getKey() . "]");
    }

    private static function elementLabel(ConfigField $configField)
    {
        return element::label(
            attribute::clazz("control-label" . ($configField->isRequired() ? " cm-required" : "")),
            attribute::forr($configField->getKey()),
            element::content(
                $configField->getName(),
                element::a(
                    attribute::clazz("cm-tooltip"),
                    attribute::title($configField->getDescription()),
                    element::i(
                        attribute::clazz("icon-question-sign")
                    )
                ),
                " :")
        );
    }

    private static function elementInput(ConfigField $configField, $type, $class)
    {
        return element::input(
            attribute::clazz($class),
            attribute::type($type),
            attribute::id($configField->getKey()),
            self::attributeName($configField),
            attribute::value($configField->getValue())
        );
    }


    function generateTextField(ConfigField $configField)
    {
        return element::div(
            attribute::clazz("control-group"),
            self::elementLabel($configField),
            element::div(
                attribute::clazz("controls"),
                self::elementInput($configField, "text", "input-text")
            )
        );
    }

    function generateTextAreaField(ConfigFieldTextarea $configField)
    {
        return element::div(
            attribute::clazz("control-group"),
            self::elementLabel($configField),
            element::div(
                attribute::clazz("controls"),
                element::textarea(
                    attribute::id($configField->getKey()),
                    self::attributeName($configField),
                    attribute::clazz("input-large"),
                    attribute::rows($configField->getRows()),
                    attribute::cols($configField->getCols()),
                    attribute::placeholder($configField->getName()),
                    element::content($configField->getValue()) //todo check
                )
            )
        );
    }

    function generateRichtextField(ConfigFieldRichtext $configField)
    {
        return element::div(
            attribute::clazz("control-group"),
            self::elementLabel($configField),
            element::div(
                attribute::clazz("controls"),
                element::textarea(
                    attribute::id($configField->getKey()),
                    self::attributeName($configField),
                    attribute::clazz("cm-wysiwyg input-large"),
                    attribute::rows($configField->getRows()),
                    attribute::cols($configField->getCols()),
                    attribute::placeholder($configField->getName()),
                    element::content($configField->getValue()) //todo check
                )
            )
        );
    }


    public function generatePasswordField(ConfigFieldPassword $configField)
    {
        return element::div(
            attribute::clazz("control-group"),
            self::elementLabel($configField),
            element::div(
                attribute::clazz("controls"),
                self::elementInput($configField, "password", "input-text")
            )
        );
    }


    function generateCheckboxField(ConfigFieldCheckbox $configField)
    {
        return element::div(
            attribute::clazz("control-group"),
            self::elementLabel($configField),
            element::div(
                attribute::clazz("controls"),
                element::input(
                    attribute::type("checkbox"),
                    self::attributeName($configField),
                    attribute::id($configField->getKey()),
                    attribute::value("Y"),
                    attribute::checked($configField->isChecked())
                )
            )
        );
    }

    function generateListField(ConfigFieldList $configField)
    {
        return element::div(
            attribute::clazz("control-group"),
            self::elementLabel($configField),
            element::div(
                attribute::clazz("controls"),
                element::select(
                    self::attributeName($configField),
                    attribute::id($configField->getKey()),
                    parent::elementOptions($configField)
                )
            )
        );
    }
}
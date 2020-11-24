<?php


namespace esas\cmsgate\cscart;


use esas\cmsgate\Registry;
use Tygh\Enum\YesNo;

class CSCartInstallHelper
{
    public static function uninstallDb()
    {
        $addon = Registry::getRegistry()->getModuleDescriptor()->getModuleMachineName();
        $payment_ids = db_get_fields(
            'SELECT payment_id'
            . ' FROM ?:payments AS payments'
            . ' LEFT JOIN ?:payment_processors AS payment_processors'
            . ' ON payments.processor_id = payment_processors.processor_id'
            . ' WHERE payment_processors.addon = ?s',
            $addon
        );

        foreach ($payment_ids as $payment_id) {
            fn_delete_payment($payment_id);
        }
        db_query('DELETE FROM ?:payment_processors WHERE addon = ?s', $addon);
    }

    /**
     * Имитируем загрузку иконки по URL
     * @param $logoName
     */
    public static function prepareLogoUploadData($logoName) {
        $_REQUEST["file_payment_image_image_icon"][0] = \Tygh\Registry::get('config.current_location')
            . fn_get_theme_path('/[relative]/media/images/addons/' . Registry::getRegistry()->getModuleDescriptor()->getModuleMachineName() . '/' . $logoName);
        $_REQUEST["type_payment_image_image_icon"][0] = 'url';
        $_REQUEST['payment_image_image_data'][0] = array(
            'pair_id' => '',
            'type' => 'M',
            'image_alt' => '',
            'is_new' => YesNo::YES,
        );
    }

    /**
     * @param $paymentProcessor CSCartPaymentProcessor
     */
    public static function addPaymentProcessor(&$paymentProcessor) {
        $processorData = array(
            'processor' => $paymentProcessor->getProcessorName(),
            'processor_script' => $paymentProcessor->getScript(),
            'processor_template' => $paymentProcessor->getTemplate(),
            'admin_template' => $paymentProcessor->getAdminTemplate(),
            'callback' => $paymentProcessor->getCallback(),
            'type' => $paymentProcessor->getType(),
            'addon' => $paymentProcessor->getAddon()
        );
        $processorId = db_query("INSERT INTO ?:payment_processors ?e", $processorData);
        $paymentProcessor->setId($processorId);
    }

    /**
     * @param $paymentMethod CSCartPaymentMethod
     */
    public static function addPaymentMethod(&$paymentMethod) {
        if ($paymentMethod->getProcessor() != null && empty($paymentMethod->getProcessor()->getId()))
            self::addPaymentProcessor($paymentMethod->getProcessor());
        $payment_data = array(
            'company_id' => $paymentMethod->getCompanyId(),
            'position' => $paymentMethod->getPosition(),
            'processor_id' => $paymentMethod->getProcessor()->getId(),
            'tax_ids' => '',
            'localization' => '',
            'payment_category' => $paymentMethod->getPaymentCategory(),
            'payment' => $paymentMethod->getName(),
            'description' => $paymentMethod->getDescription(),
            'surcharge_title' => ''
        );
        if (!empty($paymentMethod->getLogo()))
            self::prepareLogoUploadData($paymentMethod->getLogo());
        $paymentMethodId = fn_update_payment($payment_data, 0);
        $paymentMethod->setId($paymentMethodId);
    }

}
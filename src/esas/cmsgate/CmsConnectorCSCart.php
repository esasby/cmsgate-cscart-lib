<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 13.04.2020
 * Time: 12:23
 */

namespace esas\cmsgate;


use esas\cmsgate\cscart\CSCartPaymentMethod;
use esas\cmsgate\cscart\CSCartPaymentProcessor;
use esas\cmsgate\descriptors\CmsConnectorDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\lang\LocaleLoaderCSCart;
use esas\cmsgate\wrappers\OrderWrapper;
use esas\cmsgate\wrappers\OrderWrapperCSCart;

class CmsConnectorCSCart extends CmsConnector
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Для удобства работы в IDE и подсветки синтаксиса.
     * @return $this
     */
    public static function getInstance()
    {
        return Registry::getRegistry()->getCmsConnector();
    }

    public function createCommonConfigForm($managedFields)
    {
        return null; //not implemented
    }

    public function createSystemSettingsWrapper()
    {
        return null; // not implemented
    }

    /**
     * По локальному id заказа возвращает wrapper
     * @param $orderId
     * @return OrderWrapper
     */
    public function createOrderWrapperByOrderId($orderId)
    {
        return new OrderWrapperCSCart($orderId);
    }

    /**
     * Возвращает OrderWrapper для текущего заказа текущего пользователя
     * @return OrderWrapper
     */
    public function createOrderWrapperForCurrentUser()
    {
        $orderId = $this->session->data['order_id']; //todo check
        return $this->createOrderWrapperByOrderId($orderId);
    }

    /**
     * По номеру транзакции внешней система возвращает wrapper
     * @param $extId
     * @return OrderWrapper
     */
    public function createOrderWrapperByExtId($extId)
    {
        $orderId = db_get_field("SELECT order_id FROM ?:order_data WHERE data = ?i AND type = 'H'", $extId);
        return $this->createOrderWrapperByOrderId($orderId);
    }

    public function createConfigStorage()
    {
        return new ConfigStorageCSCart();
    }

    public function createLocaleLoader()
    {
        return new LocaleLoaderCSCart();
    }

    public function getMainPaymentMethod()
    {
        $processor_name = Registry::getRegistry()->getModuleDescriptor()->getModuleMachineName();
        $processor_data = db_get_row("SELECT * FROM ?:payment_processors WHERE processor = ?s AND processor_script = ?s", ucfirst($processor_name), strtolower($processor_name) . ".php");
        if (empty($processor_data))
            return null;
        $paymentProceccor = (new CSCartPaymentProcessor())
            ->setProcessorName($processor_data['processor'])
            ->setScript($processor_data['processor_script'])
            ->setTemplate($processor_data['processor_template'])
            ->setAdminTemplate($processor_data['admin_template'])
            ->setId($processor_data['processor_id']);
        $paymentMethod_date = db_get_row("SELECT ?:payment_descriptions.*, ?:payments.* FROM ?:payments LEFT JOIN ?:payment_descriptions ON ?:payments.payment_id = ?:payment_descriptions.payment_id WHERE processor_id = ?i", $paymentProceccor->getId());
        if (empty($paymentMethod_date))
            return null;
        $paymentMethod = (new CSCartPaymentMethod())
            ->setProcessor($paymentProceccor)
            ->setId($paymentMethod_date['payment_id'])
            ->setName($paymentMethod_date['payment'])
            ->setDescription($paymentMethod_date['instructions'])
            ->setPosition($paymentMethod_date['position'])
            ->setPaymentCategory($paymentMethod_date['payment_category'])
            ->setProcessorParams(unserialize($paymentMethod_date['processor_params']))
            ->setCompanyId($paymentMethod_date['company_id']);
        return $paymentMethod;
    }


    public function getConstantConfigValue($key)
    {
        switch ($key) {
            case ConfigFields::useOrderNumber():
                return true;
            default:
                return parent::getConstantConfigValue($key);
        }
    }

    public function createCmsConnectorDescriptor()
    {
        return new CmsConnectorDescriptor(
            "cmsgate-cscart-lib",
            new VersionDescriptor(
                "v1.2.1",
                "2020-11-25"
            ),
            "Cmsgate CS-Cart connector",
            "https://bitbucket.esas.by/projects/CG/repos/cmsgate-cscart-lib/browse",
            VendorDescriptor::esas(),
            "cscart"
        );
    }
}
<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 13.04.2020
 * Time: 12:23
 */

namespace esas\cmsgate;


use esas\cmsgate\descriptors\CmsConnectorDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\lang\LocaleLoaderCSCart;
use esas\cmsgate\opencart\ModelExtensionPayment;
use esas\cmsgate\view\admin\AdminViewFields;
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
    public static function getInstance() {
        return Registry::getRegistry()->getCmsConnector();
    }

    public function createCommonConfigForm($managedFields)
    {
        $configForm  = new ConfigFormOpencart(
            $managedFields,
            AdminViewFields::CONFIG_FORM_COMMON,
            SystemSettingsWrapperOpencart::getInstance()->linkAdminExtensionSettings("commonConfigFormAction"),
            null,
            $this->opencartRegistry);
        $configForm->addSubmitButton(AdminViewFields::CONFIG_FORM_BUTTON_SAVE);
        $configForm->addSubmitButton(AdminViewFields::CONFIG_FORM_BUTTON_DOWNLOAD_LOG);
        $configForm->addSubmitButton(AdminViewFields::CONFIG_FORM_BUTTON_CANCEL);
//        $configForm->addSubmitButton(RequestParams::SAVE_AND_EXIT_BUTTON);
        $configForm->addCmsManagedFields();
        return $configForm;
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
        $opencartCmsgateModel = new ModelExtensionPayment($this->opencartRegistry);
        $orderId = $opencartCmsgateModel->getOrderIdByExtId($extId);
        if ($orderId == null || $orderId == '0')
            return null;
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

    public function createCmsConnectorDescriptor()
    {
        return new CmsConnectorDescriptor(
            "cmsgate-cscart-lib",
            new VersionDescriptor(
                "v1.0.0",
                "2020-11-10"
            ),
            "Cmsgate CS-Cart connector",
            "https://bitbucket.esas.by/projects/CG/repos/cmsgate-cscart-lib/browse",
            VendorDescriptor::esas(),
            "cscart"
        );
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
}
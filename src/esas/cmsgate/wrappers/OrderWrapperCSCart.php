<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 13:08
 */

namespace esas\cmsgate\wrappers;

use Throwable;

class OrderWrapperCSCart extends OrderSafeWrapper
{
    private $orderInfo;

    /**
     * OrderWrapperCSCart constructor.
     */
    public function __construct($orderInfo)
    {
        parent::__construct();
        if (is_scalar($orderInfo)) // передан идентификатор заказа
            $this->orderInfo = fn_get_order_info($orderInfo);
        else // передан массив с информацией о заказе
            $this->orderInfo = $orderInfo;
    }


    /**
     * Уникальный номер заказ в рамках CMS
     * @return string
     * @throws Throwable
     */
    public function getOrderIdUnsafe()
    {
        return $this->orderInfo['order_id'];
    }

    /**
     * Полное имя покупателя
     * @throws Throwable
     * @return string
     */
    public function getFullNameUnsafe()
    {
        return $this->orderInfo['b_firstname'] . " " . $this->orderInfo['b_lastname'];
    }

    /**
     * Мобильный номер покупателя для sms-оповещения
     * (если включено администратором)
     * @throws Throwable
     * @return string
     */
    public function getMobilePhoneUnsafe()
    {
        return $this->orderInfo['b_phone'];
    }

    /**
     * Email покупателя для email-оповещения
     * (если включено администратором)
     * @throws Throwable
     * @return string
     */
    public function getEmailUnsafe()
    {
        return $this->orderInfo['email'];
    }

    /**
     * Физический адрес покупателя
     * @throws Throwable
     * @return string
     */
    public function getAddressUnsafe()
    {
        return $this->orderInfo['b_address'] . " " . $this->orderInfo['b_city'] . " " . $this->orderInfo['b_country'];
    }

    /**
     * Общая сумма товаров в заказе
     * @throws Throwable
     * @return string
     */
    public function getAmountUnsafe()
    {
        return $this->orderInfo['total'];
    }

    /**
     * Валюта заказа (буквенный код)
     * @throws Throwable
     * @return string
     */
    public function getCurrencyUnsafe()
    {
        return $this->orderInfo['secondary_currency']; // ???
    }

    /**
     * Массив товаров в заказе
     * @throws Throwable
     * @return OrderProductWrapper[]
     */
    public function getProductsUnsafe()
    {
        $products = $this->orderInfo['products'];;
        foreach ($products as $product)
            $productsWrappers[] = new OrderProductWrapperCSCart($product);
        return $productsWrappers;
    }

    const BILLID_METADATA_KEY = 'transaction_id';

    /**
     * BillId (идентификатор хуткигрош) успешно выставленного счета
     * @throws Throwable
     * @return mixed
     */
    public function getExtIdUnsafe()
    {
        if (isset($this->orderInfo["payment_info"]) && isset($this->orderInfo["payment_info"][self::BILLID_METADATA_KEY]))
            return $this->orderInfo["payment_info"][self::BILLID_METADATA_KEY];
        else
            return null;
    }

    /**
     * Текущий статус заказа в CMS
     * @return mixed
     * @throws Throwable
     */
    public function getStatusUnsafe()
    {
        return $this->orderInfo["status"];
    }

    /**
     * Обновляет статус заказа в БД
     * @param $newStatus
     * @return mixed
     * @throws Throwable
     */
    public function updateStatus($newStatus)
    {
        fn_change_order_status($this->getOrderId(), $newStatus, '', false); //todo check
    }

    /**
     * Сохраняет привязку billid к заказу
     * @param $billId
     * @return mixed
     * @throws Throwable
     */
    public function saveExtId($billId)
    {
        $pp_response[self::BILLID_METADATA_KEY] = $billId;
        fn_update_order_payment_info($this->getOrderId(), $pp_response); //todo check
    }

    public function getClientIdUnsafe()
    {
        return ""; //check
    }

    public function getShippingAmountUnsafe()
    {
        return 0; //check
    }
}
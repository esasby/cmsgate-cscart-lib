<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 14:01
 */

namespace esas\cmsgate\wrappers;

use Throwable;

class OrderProductWrapperCSCart extends OrderProductSafeWrapper
{
    private $product;

    /**
     * OrderProductWrapperCSCart constructor.
     * @param $orderProduct
     */
    public function __construct($product)
    {
        $this->product = $product;
    }


    /**
     * Артикул товара
     * @throws Throwable
     * @return string
     */
    public function getInvIdUnsafe()
    {
        return $this->product["product_code"]; // может все-таки product_id или item_id?
    }

    /**
     * Название или краткое описание товара
     * @throws Throwable
     * @return string
     */
    public function getNameUnsafe()
    {
        return $this->product["product"];
    }

    /**
     * Количество товароа в корзине
     * @throws Throwable
     * @return mixed
     */
    public function getCountUnsafe()
    {
        return $this->product["amount"]; //todo check
    }

    /**
     * Цена за единицу товара
     * @throws Throwable
     * @return mixed
     */
    public function getUnitPriceUnsafe()
    {
        return $this->product["price"];
    }
}
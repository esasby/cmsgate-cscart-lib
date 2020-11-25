<?php


namespace esas\cmsgate\cscart;


use esas\cmsgate\ConfigFields;
use esas\cmsgate\Registry;

class CSCartPaymentMethod
{
    private $id;
    private $companyId;
    private $position;
    private $name;
    private $paymentCategory;
    /**
     * @var CSCartPaymentProcessor
     */
    private $processor;
    private $description;
    private $logo;
    /**
     * @var array
     */
    private $processorParams;

    /**
     * CSCartPaymentMethod constructor.
     */
    public function initDefaults()
    {
        $this->name = Registry::getRegistry()->getTranslator()->getConfigFieldDefault(ConfigFields::paymentMethodName());
        $this->description = Registry::getRegistry()->getTranslator()->getConfigFieldDefault(ConfigFields::paymentMethodDetails());
        $this->companyId = fn_allowed_for('ULTIMATE') ? fn_get_default_company_id() : 0;
        $this->paymentCategory = "tab1";
        $this->position = 30;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return CSCartPaymentMethod
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param int|string $companyId
     * @return CSCartPaymentMethod
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return CSCartPaymentMethod
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CSCartPaymentMethod
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentCategory()
    {
        return $this->paymentCategory;
    }

    /**
     * @param mixed $paymentCategory
     * @return CSCartPaymentMethod
     */
    public function setPaymentCategory($paymentCategory)
    {
        $this->paymentCategory = $paymentCategory;
        return $this;
    }

    /**
     * @return CSCartPaymentProcessor
     */
    public function getProcessor()
    {
        return $this->processor;
    }

    /**
     * @param CSCartPaymentProcessor $processor
     * @return CSCartPaymentMethod
     */
    public function setProcessor($processor)
    {
        $this->processor = $processor;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return CSCartPaymentMethod
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     * @return CSCartPaymentMethod
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @return array
     */
    public function getProcessorParams()
    {
        return $this->processorParams;
    }

    /**
     * @param array $processorParams
     * @return CSCartPaymentMethod
     */
    public function setProcessorParams($processorParams)
    {
        $this->processorParams = $processorParams;
        return $this;
    }
}
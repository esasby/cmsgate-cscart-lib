<?php


namespace esas\cmsgate\cscart;


use esas\cmsgate\Registry;

class CSCartPaymentProcessor
{
    private $id;
    private $processorName;
    private $script;
    private $template;
    private $adminTemplate;
    private $callback = 'N';
    private $type = 'P';
    private $addon;

    public function initDefaults()
    {
        //default init
        $moduleMachineName = Registry::getRegistry()->getModuleDescriptor()->getModuleMachineName();
        $this->processorName = ucfirst($moduleMachineName);
        $this->script = $moduleMachineName . '.php';
        $this->adminTemplate = $moduleMachineName . '.tpl';
        $this->addon = $moduleMachineName;
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
     * @return CSCartPaymentProcessor
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getProcessorName()
    {
        return $this->processorName;
    }

    /**
     * @param string $processorName
     * @return CSCartPaymentProcessor
     */
    public function setProcessorName($processorName)
    {
        $this->processorName = $processorName;
        return $this;
    }

    /**
     * @return string
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * @param string $script
     * @return CSCartPaymentProcessor
     */
    public function setScript($script)
    {
        $this->script = $script;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     * @return CSCartPaymentProcessor
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdminTemplate()
    {
        return $this->adminTemplate;
    }

    /**
     * @param string $adminTemplate
     * @return CSCartPaymentProcessor
     */
    public function setAdminTemplate($adminTemplate)
    {
        $this->adminTemplate = $adminTemplate;
        return $this;
    }

    /**
     * @return string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param string $callback
     * @return CSCartPaymentProcessor
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return CSCartPaymentProcessor
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddon()
    {
        return $this->addon;
    }

    /**
     * @param mixed $addon
     * @return CSCartPaymentProcessor
     */
    public function setAddon($addon)
    {
        $this->addon = $addon;
        return $this;
    }
}
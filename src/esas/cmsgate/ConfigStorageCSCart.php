<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 13:14
 */

namespace esas\cmsgate;


use esas\cmsgate\utils\OpencartVersion;
use Exception;

class ConfigStorageCSCart extends ConfigStorageCms
{
    private $configuration;

    /**
     * ConfigurationWrapperOpencart constructor.
     * @param $config
     */
    public function __construct()
    {
        parent::__construct();
        $this->configuration = $this->loadSettingsFromDB();
    }

    /**
     * Получаем из БД настройки процессора по имени.
     *
     * @return array|bool
     */
    protected function loadSettingsFromDB() //todo перенести в func.php
    {
        $processor_name = Registry::getRegistry()->getPaysystemConnector()->getPaySystemConnectorDescriptor()->getModuleMachineName(); //может быть moduleName?
        $processor_data = db_get_row("SELECT * FROM ?:payment_processors WHERE processor = ?s OR processor_script = ?s", $processor_name, strtolower($processor_name) . ".tpl");
        if (empty($processor_data)) {
            return array();
        }
        $pdata = db_get_row("SELECT processor_params FROM ?:payments WHERE processor_id = ?i", $processor_data['processor_id']);
        if (empty($pdata) || empty($pdata['processor_params'])) {
            return array();
        }
        return unserialize($pdata['processor_params']);
    }


    /**
     * @param $key
     * @return string
     * @throws Exception
     */
    public function getConfig($key)
    {
        if (array_key_exists($key, $this->configuration))
            return $this->configuration[$key];
        else
            return null;
    }

    /**
     * @param $cmsConfigValue
     * @return bool
     * @throws Exception
     */
    public function convertToBoolean($cmsConfigValue)
    {
        return strtolower($cmsConfigValue) == 'y';
    }

    public function createCmsRelatedKey($key)
    {
        return $key;
    }

    /**
     * Сохранение значения свойства в харнилища настроек конкретной CMS.
     *
     * @param string $key
     * @throws Exception
     */
    public function saveConfig($key, $value)
    {
       //not implemented
    }
}
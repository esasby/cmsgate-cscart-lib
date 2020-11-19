<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 27.09.2018
 * Time: 13:09
 */

namespace esas\cmsgate\lang;


class LocaleLoaderCSCart extends LocaleLoaderCms
{
    public function getLocale()
    {
        return CART_LANGUAGE . "_" . strtoupper(CART_LANGUAGE);
    }


    public function getCmsVocabularyDir()
    {
        return dirname(__FILE__);
    }
}
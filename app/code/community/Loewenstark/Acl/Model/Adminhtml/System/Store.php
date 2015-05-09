<?php

class Loewenstark_Acl_Model_Adminhtml_System_Store
extends Mage_Adminhtml_Model_System_Store
{
    /**
     * Get websites as id => name associative array
     *
     * @param bool $withDefault
     * @param string $attribute
     * @return array
     */
    public function getWebsiteOptionHash($withDefault = false, $attribute = 'name')
    {
        $options = parent::getWebsiteOptionHash($withDefault, $attribute);
        
        
        return $options;
    }
}
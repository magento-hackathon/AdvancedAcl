<?php

class Loewenstark_Acl_Block_Adminhtml_Store_Switcher
extends Mage_Adminhtml_Block_Store_Switcher
{
    
    protected function _construct() {
        parent::_construct();
    }


    /**
     * @deprecated since version from magento
     */
    public function getWebsiteCollection()
    {
        $collection = Mage::getModel('core/website')->getResourceCollection();

        $websiteIds = $this->getWebsiteIds();
        if (!is_null($websiteIds)) {
            $collection->addIdFilter($this->getWebsiteIds());
        }
        
        $websites = $this->_getNinWebsitesIds();
        if(!empty($websites))
        {
            $collection->addFieldToFilter('website_id', array('nin', $websites));
        }
        return $collection->load();
    }
    
    
    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites()
    {
        $websites = parent::getWebsites();
        $result = array();
        foreach($websites as $website)
        {
            if(in_array($website->getWebsiteId(), $this->_getNinWebsitesIds()))
            {
                $result[] = $website;
            }
        }
        return $result;
    }
    
    /**
     * @deprecated since version from magento
     */
    public function getGroupCollection($website)
    {
        if (!$website instanceof Mage_Core_Model_Website) {
            $website = Mage::getModel('core/website')->load($website);
        }
        
        $collection = $website->getGroupCollection();
        $websites = $this->_getNinWebsitesIds();
        if(!empty($websites))
        {
            $collection->addFieldToFilter('website_id', array('nin', $websites));
        }
        return $collection;
    }
    
    /**
     * Get store groups for specified website
     *
     * @param Mage_Core_Model_Website $website
     * @return array
     */
    public function getStoreGroups($website)
    {
        return parent::getGroups();
    }
    
    /**
     * 
     * @return array
     */
    protected function _getNinWebsitesIds()
    {
        return Mage::helper('loewenstark_acl')->getWebsitesIds();
    }
}
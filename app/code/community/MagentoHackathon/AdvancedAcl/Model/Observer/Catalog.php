<?php

class MagentoHackathon_AdvancedAcl_Model_Observer_Catalog
{

    /**
     * filter out products by allowed store
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterProductGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if ($collection instanceof Mage_Catalog_Model_Resource_Product_Collection) {
            $storeIds = $this->getStoreIds();

            if (!empty($storeIds)) {
                $collection->addStoreFilter(current($storeIds));
            }
        }
    }

    /**
     * filter url rewrite collection by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterUrlRewriteCollection(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if ($collection instanceof Mage_Core_Model_Resource_Url_Rewrite_Collection) {
            $storeIds = $this->getStoreIds();
            if (!empty($storeIds)) {
                $collection->addStoreFilter($storeIds);
            }
        }
    }

    /**
     * retrieves allowed store ids
     *
     * @return mixed
     */
    protected function getStoreIds()
    {
        return Mage::helper('magentohackathon_advancedacl/data')->getActiveRole()->getStoreIds();
    }
}
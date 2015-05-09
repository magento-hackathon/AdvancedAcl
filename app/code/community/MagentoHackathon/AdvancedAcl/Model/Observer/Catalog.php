<?php

class MagentoHackathon_AdvancedAcl_Model_Observer_Catalog
{

    public function filterProductGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection()
        ->getSelect()->join(array(
            'product_store' => Mage::getSingleton('core/resource')->getTableName("catalog/product_website")),
            'e.entity_id = product_store.product_id'

        );
        if ($collection instanceof Mage_Catalog_Model_Resource_Product_Collection) {
            $storeIds = $this->getStoreIds();
            if (!empty($storeIds)) {
                $collection->addFieldToFilter('website_id', array('in' => $storeIds));
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
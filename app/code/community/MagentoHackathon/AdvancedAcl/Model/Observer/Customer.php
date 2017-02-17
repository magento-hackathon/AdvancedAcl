<?php

class MagentoHackathon_AdvancedAcl_Model_Observer_Customer
{

    /**
     * filter out customers by allowed store
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterCustomerGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if ($collection instanceof Mage_Customer_Model_Resource_Customer_Collection) {
            $storeIds = $this->getStoreIds();

            if ( ! empty($storeIds)) {
                $collection->addAttributeToFilter('store_id', array('in' => $storeIds));
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


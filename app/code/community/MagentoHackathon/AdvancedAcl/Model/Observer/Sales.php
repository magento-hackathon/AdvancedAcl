<?php

class MagentoHackathon_AdvancedAcl_Model_Observer_Sales
{

    /**
     * filter the sales order grid for allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterOrderGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderGridCollection();
        $storeIds = $this->getStoreIds();
        if (0 < count($storeIds)) {
            $collection->addAttributeToFilter('store_id', array('in' => $storeIds));
        }

    }

    /**
     * filter sales order invoices collection by  allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterInvoiceGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderInvoiceGridCollection();
        $storeIds = $this->getStoreIds();
        if (0 < count($storeIds)) {
            $collection->addAttributeToFilter('store_id', array('in' => $storeIds));
        }
    }

    /**
     * retrieves allowed store ids 
     *
     * @return mixed
     */
    protected function getStoreIds()
    {
        return Mage::helper('magentohackathon_advancedacl/data')->getActiveRole()->getStoreId();
    }

}
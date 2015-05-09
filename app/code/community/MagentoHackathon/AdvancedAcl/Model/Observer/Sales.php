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
     * filters shipment grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterShipmentsGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderShipmentGridCollection();
        $storeIds = $this->getStoreIds();
        if (0 < count($storeIds)) {
            $collection->addAttributeToFilter('store_id', array('in' => $storeIds));
        }
    }


    /**
     * filters credit memos grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterCreditMemoGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderCreditmemoGridCollection();
        $storeIds = $this->getStoreIds();
        if (0 < count($storeIds)) {
            $collection->addAttributeToFilter('store_id', array('in' => $storeIds));
        }
    }

    public function filterAgreements(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderCreditmemoGridCollection();
        $storeIds = $this->getStoreIds();
        if (0 < count($storeIds)) {
            $collection->addAttributeToFilter('store_id', array('in' => $storeIds));
        }
    }

    public function filterAgreementsGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if ($collection instanceof Mage_Checkout_Model_Resource_Agreement_Collection) {
            if (0 < $this->getStoreIds()) {
                $collection->addStoreFilter($this->getStoreIds());
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
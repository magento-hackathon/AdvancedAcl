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
        $this->filterCollection($collection);

    }

    /**
     * filter sales order invoices collection by  allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterInvoiceGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderInvoiceGridCollection();
        $this->filterCollection($collection);
    }

    /**
     * filters shipment grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterShipmentsGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderShipmentGridCollection();
        $this->filterCollection($collection);
    }


    /**
     * filters credit memos grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterCreditMemoGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderCreditmemoGridCollection();
        $this->filterCollection($collection);
    }

    public function filterAgreements(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderCreditmemoGridCollection();
        $this->filterCollection($collection);
    }

    /**
     * filters agreements grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterAgreementsGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if ($collection instanceof Mage_Checkout_Model_Resource_Agreement_Collection
            || $collection instanceof Mage_Sales_Model_Resource_Order_Payment_Transaction_Collection) {
            if (0 < $this->getStoreIds()) {
                $collection->addStoreFilter($this->getStoreIds());
            }
        }
    }


    /**
     * filters recurring profile grid by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterRecurringProfilesGrid(Varien_Event_Observer $observer)
    {
        /** @var Mage_Sales_Model_Resource_Recurring_Profile_Collection $collection */
        $collection = $observer->getRecurringProfileCollection();
        $storeIds = $this->getStoreIds();
        if (0 < count($storeIds)) {
            $collection->addFieldToFilter('store_id', array('in' => $storeIds));
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

    /**
     * @param $collection
     */
    public function filterCollection($collection)
    {
        $storeIds = $this->getStoreIds();
        if (0 < count($storeIds)) {
            $collection->addAttributeToFilter('store_id', array('in' => $storeIds));
        }
    }

}
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
     * filter search terms by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterSearchTermsGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if ($collection instanceof Mage_CatalogSearch_Model_Resource_Query_Collection) {
            $storeIds = $this->getStoreIds();
            if (!empty($storeIds)) {
                $collection->addStoreFilter($storeIds);
            }
        }
    }
    /**
     * filter search terms by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterTagGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if ($collection instanceof Mage_Tag_Model_Resource_Tag_Collection) {
            $storeIds = $this->getStoreIds();
            if (!empty($storeIds)) {
                $collection->addStoreFilter($storeIds);
            }
        }
    }

    /**
     * filter out categories by allowed stores
     *
     * @param Varien_Event_Observer $observer
     */
    public function filterCategories(Varien_Event_Observer $observer)
    {
        /** @var Mage_Catalog_Model_Resource_Category_Collection $collection */
        $collection = $observer->getCategoryCollection();
        $storeIds = $this->getStoreIds();
        $oldStoreId = $collection->getStoreId();
        // setting the first allowed store if the current store is not allowed for user
        if (!empty($storeIds) && !in_array($oldStoreId, $storeIds)) {
            $allowedStoreId = current($storeIds);
            $collection
                ->setProductStoreId($allowedStoreId)
                ->setStoreId($allowedStoreId);
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
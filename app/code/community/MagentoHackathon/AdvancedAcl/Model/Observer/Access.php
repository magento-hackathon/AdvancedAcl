<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 09.05.15
 * Time: 17:55
 */
class MagentoHackathon_AdvancedAcl_Model_Observer_Access extends
    MagentoHackathon_AdvancedAcl_Model_Observer_Abstract
{

    const CATALOG_PRODUCT_INDEX_ROUTE_PATH = 'adminhtml/catalog_product/index';

    public function productEditAccessAllowed(Varien_Event_Observer $observer)
    {
        /** @var Mage_Core_Controller_Varien_Action $controller */
        $controller = $observer->getControllerAction();

        $storeId = $controller->getRequest()->getParam('store');

        if ($this->getHelper()->isAllowedAccessForStore($storeId)) {
            $this->deniedAction($controller, self::CATALOG_PRODUCT_INDEX_ROUTE_PATH);
        }
    }
}
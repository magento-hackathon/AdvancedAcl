<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 10.05.15
 * Time: 10:46
 */

/**
 * Class MagentoHackathon_AdvancedAcl_Model_Observer_Url
 */
class MagentoHackathon_AdvancedAcl_Model_Observer_Url extends
    MagentoHackathon_AdvancedAcl_Model_Observer_Abstract
{

    const STORE_PARAM_KEY = 'store';

    /**
     * @param Mage_Core_Controller_Varien_Action $controller
     */
    protected function _appendFirstStoreId(Mage_Core_Controller_Varien_Action $controller)
    {
        $storeId = $controller->getRequest()->getParam(self::STORE_PARAM_KEY);

        if (is_null($storeId)) {
            $storeId = current($this->getHelper()->getAllowedStoreIds());
            $controller->getRequest()->setParam(self::STORE_PARAM_KEY, $storeId);
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function catalogProductEdit(Varien_Event_Observer $observer)
    {
        $this->_appendFirstStoreId($observer->getControllerAction());
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function catalogCategoryEdit(Varien_Event_Observer $observer)
    {
        $this->_appendFirstStoreId($observer->getControllerAction());
    }

}
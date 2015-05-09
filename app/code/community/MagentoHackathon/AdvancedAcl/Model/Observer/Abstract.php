<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 09.05.15
 * Time: 18:10
 */

class MagentoHackathon_AdvancedAcl_Model_Observer_Abstract
{
    /**
     * @return MagentoHackathon_AdvancedAcl_Helper_Data
     */
    public function getHelper()
    {
        return Mage::helper('magentohackathon_advancedacl');
    }

    /**
     * @param $controller Mage_Core_Controller_Varien_Action
     * @return bool
     */
    protected function denyAccess($controller)
    {
        $controller->deniedAction();
        return $controller->setFlag('', Mage_Adminhtml_System_ConfigController::FLAG_NO_DISPATCH, true);
        return false;
    }

    /**
     * @param Mage_Core_Controller_Varien_Action $controller
     * @param $routePath
     */
    public function deniedAction($controller, $routePath)
    {
        /** @var Mage_Core_Model_Url $urlModel */
        $urlModel = Mage::helper('adminhtml');
        $controller->getResponse()->setRedirect($urlModel->getUrl($routePath));
        return;
    }
}

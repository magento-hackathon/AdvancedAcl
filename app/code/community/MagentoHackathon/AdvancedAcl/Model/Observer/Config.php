<?php
/**
 * MagentoHackathon
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    MagentoHackathon
 * @package     MagentoHackathon_AdvancedAcl
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Observer for admin role
 *
 * @category    MagentoHackathon
 * @package     MagentoHackathon_AdvancedAcl
 * @author      Thomas Birke <tbirke@netextreme.de>
 */


/**
 * Class MagentoHackathon_AdvancedAcl_Model_Observer_Config
 */
class MagentoHackathon_AdvancedAcl_Model_Observer_Config
{
    public function beforeLoad($observer)
    {
        $helper = Mage::helper('magentohackathon_advancedacl');
        $controller = $observer->getControllerAction();
        $website = $controller->getRequest()->getParam('website');
        $store   = $controller->getRequest()->getParam('store');

        if ($helper->hasFullAccess() || $helper->isSingleStoreMode()) {
            // there is no restriction for this user
            return;
        }

        if ($store) {
            if (false === $helper->isAllowedAccessForStore($store)) {
                return $this->denyAccess($controller);
            }
        } elseif ($website) {
            if (false === $helper->hasFullWebsiteAccess($website)) {
                return $this->denyAccess($controller);
            }
        } else {
            return $this->denyAccess($controller);
        }
    }

    protected function denyAccess($controller)
    {
        $controller->deniedAction();
        $controller->setFlag('', Mage_Adminhtml_System_ConfigController::FLAG_NO_DISPATCH, true);
        return false;
    }
}

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
    extends MagentoHackathon_AdvancedAcl_Model_Observer_Abstract
{
    public function beforeLoad($observer)
    {
        $controller = $observer->getControllerAction();
        if ($controller instanceof Mage_Adminhtml_System_ConfigController) {
            switch ($controller->getRequest()->getActionName()) {
                case 'edit':
                case 'save':
                    return $this->_checkScopeAccess($controller);
            }
        }
    }

    protected function _checkScopeAccess($controller)
    {
        $website = $controller->getRequest()->getParam('website');
        $store   = $controller->getRequest()->getParam('store');

        if ($this->getHelper()->hasFullAccess() || $this->getHelper()->isSingleStoreMode()) {
            // there is no restriction for this user
            return;
        }

        if ($store) {
            if (false === $this->getHelper()->hasStoreViewAccess($store)) {
                return $this->denyAccess($controller);
            }
        } elseif ($website) {
            if (false === $this->getHelper()->hasFullWebsiteAccess($website)) {
                return $this->denyAccess($controller);
            }
        } else {
            return $this->denyAccess($controller);
        }
    }
}

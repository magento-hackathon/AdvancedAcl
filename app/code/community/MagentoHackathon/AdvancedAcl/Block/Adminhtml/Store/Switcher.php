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
 * Block definition for Store Switcher
 *
 * @category    MagentoHackathon
 * @package     MagentoHackathon_AdvancedAcl
 */

class MagentoHackathon_AdvancedAcl_Block_Adminhtml_Store_Switcher
    extends Mage_Adminhtml_Block_Store_Switcher
{
    public function __construct()
    {
        parent::__construct();

        $helper = Mage::helper('magentohackathon_advancedacl');
        if (false === $helper->hasFullAccess()) {
            $this->_hasDefaultOption = false;
        }
    }

    public function isShow()
    {
        $helper = Mage::helper('magentohackathon_advancedacl');
        if (false === $helper->isSingleStoreMode()) {
            return true;
        }
        return parent::isShow();
    }

    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites()
    {
        $helper = Mage::helper('magentohackathon_advancedacl');
        $websites = parent::getWebsites();
        $result = array();
        foreach($websites as $website) {
            foreach ($website->getStores() as $store) {
                if ($helper->hasStoreViewAccess($store)) {
                    $result[] = $website;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Get store groups for specified website
     *
     * @param Mage_Core_Model_Website $website
     * @return array
     */
    public function getStoreGroups($website)
    {
        $helper = Mage::helper('magentohackathon_advancedacl');
        $storeGroups = parent::getStoreGroups($website);
        $result = array();
        foreach($storeGroups as $key=>$storeGroup) {
            foreach ($storeGroups->getStores() as $store) {
                if ($helper->hasStoreViewAccess($store)) {
                    $result[$key] = $storeGroup;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Get store views for specified store group
     *
     * @param Mage_Core_Model_Store_Group $group
     * @return array
     */
    public function getStores($group)
    {
        $helper = Mage::helper('magentohackathon_advancedacl');
        $stores = parent::getStores($group);
        $result = array();
        foreach($stores as $key=>$store) {
            if ($helper->hasStoreViewAccess($store)) {
                $result[$key] = $store;
            }
        }
        return $result;
    }

}

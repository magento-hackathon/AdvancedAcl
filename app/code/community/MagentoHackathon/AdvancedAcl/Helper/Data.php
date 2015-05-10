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
 * Helper class
 *
 * @category    MagentoHackathon
 * @package     MagentoHackathon_AdvancedAcl
 * @author      Andreas Penz <testa.peta@gmail.com>
 * @author      Thomas Birke <tbirke@netextreme.de>
 */

class MagentoHackathon_AdvancedAcl_Helper_Data
    extends Mage_Core_Helper_Abstract
{

    protected $_websites = null;

    /**
     * @var null|boolean
     */
    protected $_isSingleStoreMode = null;

    /**
     * if customer is allowed to access given store
     *
     * @param int|string|Mage_Core_Model_Store $store store id, store code, or store
     * @return bool
     */
    public function hasStoreViewAccess($store = null)
    {
        if (is_null($store)) {
            $store = $this->getDefaultStoreId();
        }
        if (is_object($store) && $store instanceof Mage_Core_Model_Store) {
            $storeId = $store->getId();
        } elseif (is_numeric($store)) {
            $storeId = $store;
        } else {
            $storeId = Mage::getModel('core/store')->load($store)->getId();
        }
        $allowedStores = $this->getActiveRole()->getStoreIds();

        return empty($allowedStores) || in_array($storeId, $allowedStores);
    }

    /**
     * get stores the customer is restricted to
     *
     * @return array
     * @codeCoverageIgnore
     */
    public function getAllowedStoreIds()
    {
        return $this->getActiveRole()->getStoreIds();
    }

    /**
     * if customer is restricted to specific stores
     *
     * @return bool
     */
    public function hasFullAccess()
    {
        $allowedStoreIds = $this->getAllowedStoreIds();

        return empty($allowedStoreIds)
            || Mage::getModel('core/store')->getCollection()->getAllIds() === $allowedStoreIds;
    }

    /**
     * if customer can access all stores of a website
     *
     * @param Mage_Core_Model_Website|string $website Website to check stores of
     * @return bool
     */
    public function hasFullWebsiteAccess($website)
    {
        if (is_string($website)) {
            $website = Mage::getModel('core/website')->load($website);
        }

        $diff = array_diff($website->getStoreIds(), $this->getAllowedStoreIds());
        return empty($diff);
    }

    /**
     * if customer can access all stores of a store group
     *
     * @param Mage_Core_Model_Store_Group|string $group Group to check stores of
     * @return bool
     */
    public function hasFullStoreGroupAccess($storeGroup)
    {
        if (is_string($storeGroup)) {
            $storeGroup = Mage::getModel('core/store_group')->load($storeGroup);
        }

        $diff = array_diff($storeGroup->getStoreIds(), $this->getAllowedStoreIds());
        return empty($diff);
    }

    /**
     * @param int $roleid role_id from admin/role
     * @return boolean
     * @codeCoverageIgnore
     */
    public function isAdmin()
    {
        $collection = Mage::getModel('admin/rules')
            ->getCollection()
            ->addFieldToFilter('role_id', $this->getActiveRole()->getId())
            ->addFieldToFilter('resource_id', 'all')
            ->addFieldToFilter('permission', 'allow');
        if($collection->count() > 0)
        {
            return true;
        }
        return false;
    }

    /**
     *
     * @return Mage_Admin_Model_Role
     * @codeCoverageIgnore
     */
    public function getActiveRole()
    {
        return Mage::getSingleton('admin/session')->getUser()->getRole();
    }

    /**
     * Is single Store mode (only one store without default)
     *
     * @return bool|null
     * @codeCoverageIgnore
     */
    public function isSingleStoreMode()
    {
        if (is_null($this->_isSingleStoreMode)) {
            $this->_isSingleStoreMode = Mage::app()->isSingleStoreMode();
        }
        return $this->_isSingleStoreMode;
    }

    /**
     * get the default store-id
     *
     * @return mixed
     * @throws Mage_Core_Exception
     * @codeCoverageIgnore
     */
    public function getDefaultStoreId()
    {
        return Mage::app()
            ->getWebsite(true)
            ->getDefaultGroup()
            ->getDefaultStoreId();
    }
}

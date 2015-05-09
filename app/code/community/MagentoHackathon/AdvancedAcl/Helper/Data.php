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

    protected $_websites_not_int_list = null;
    protected $_websites = null;
    protected $_role = null;

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
    public function isAllowedAccessForStore($store)
    {
        if (is_object($store) && $store instanceof Mage_Core_Model_Store) {
            $storeId = $store->getId();
        }
        if (is_numeric($store)) {
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
        return empty(array_diff($website->getStoreIds(), $this->getAllowedStoreIds()));
    }

    /**
     *
     * @todo add DispatchEvent in this method
     * @return array
     */
    public function getFields()
    {
        return array(
            'websites', // array
        );
    }

    /**
     * @deprecated since version 1.0.0.1
     *
     * @return array
     */
    public function getFieldsInJson()
    {
        return self::getFields();
    }

    /**
     *
     * @return array
     */
    public function getWebsites()
    {
        if(is_null($this->_websites))
        {
            $websites = array();
            foreach (Mage::app()->getWebsites() as $website)
            {
                /* @var Mage_Core_Model_Website $website */
                $websites[$website->getWebsiteId()] = array(
                    'name' => $website->getName(),
                    'code' => $website->getCode()
                );
            }
            $this->_websites = $websites;
        }
        return $this->_websites;
    }

    /**
     *
     * @return mixed
     */
    public function getWebsiteAsOption()
    {
        $data = array();
        foreach($this->getWebsites() as $_id=>$_website)
        {
            $data[] = array(
                'value' => $_id,
                'label' => $_website['name']
            );
        };
        return $data;
    }

    /**
     *
     * @param int $roleid role_id from admin/role
     * @return boolean
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
     * @param Mage_Admin_Model_Role $role
     * @return MagentoHackathon_AdvancedAcl_Helper_Data
     */
    public function setRole(Mage_Admin_Model_Role $role)
    {
        $this->_role = $role;
        return $this;
    }

    /**
     * check if the admin area is active
     *
     * @return boolean
     */
    public function isAdminArea()
    {
        if(Mage::getDesign()->getArea() == 'adminhtml')
        {
            return true;
        }
        return false;
    }

    /**
     *
     * @return Mage_Admin_Model_Role
     */
    public function getActiveRole()
    {
        return Mage::getSingleton('admin/session')->getUser()->getRole();
    }

    /**
     *
     * @return array
     */
    public function getWebsitesIds()
    {
        if(is_null($this->_websites_not_int_list))
        {
            $websites = $this->getActiveRole()->getWebsites();
            $this->_websites_not_int_list = array_keys($websites);
        }
        return $this->_websites_not_int_list;
    }

    /**
     * Is single Store mode (only one store without default)
     *
     * @return bool|null
     */
    public function isSingleStoreMode()
    {
        if (is_null($this->_isSingleStoreMode)) {
            $this->_isSingleStoreMode = Mage::app()->isSingleStoreMode();
        }
        return $this->_isSingleStoreMode;
    }

}

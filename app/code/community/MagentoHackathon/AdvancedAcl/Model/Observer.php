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
 * General Observer
 *
 * @category    MagentoHackathon
 * @package     MagentoHackathon_AdvancedAcl
 */

class MagentoHackathon_AdvancedAcl_Model_Observer
{
    /**
     *
     * @param Varien_Event $event
     */
    public function loadAfterAdminModelRole($event)
    {
        $rule = $event->getObject();
        /* @var Mage_Admin_Model_Role $rule */
        if($rule instanceof Mage_Admin_Model_Role)
        {
            $websites = $rule->getData('websites');
            if(!is_array($websites))
            {
                $websites = (array)explode(',', $websites);
            }
            $rule->setData('websites_as_array', array_filter($websites));
        }
    }

    /**
     *
     * @param Varien_Event $event
     */
    public function saveBeforeAdminModelRole($event)
    {
        $rule = $event->getObject();
        /* @var Mage_Admin_Model_Role $rule */
        if($rule instanceof Mage_Admin_Model_Role)
        {

            $websites = $rule->getData('websites');
            if(!is_array($websites))
            {
                $websites = (array)explode(',', $websites);
            }
            $rule->setData('websites', implode(',', array_filter($websites)));
        }
    }

    /**
     *
     * @param Varien_Event $event
     */
    public function addTabToRoles($event)
    {
        $block = $event->getBlock();
        /* @var Mage_Adminhtml_Block_Permissions_Editroles $block */
        if($block instanceof Mage_Adminhtml_Block_Permissions_Editroles)
        {
            if(!$this->_isAdmin())
            {
                $tab = $block->getLayout()->createBlock('magentohackathon_advancedacl/adminhtml_permissions_tab_Rolesextend', 'adminhtml.permissions.tab.magentohackathon_advancedacl');
                $block->addTab('magentohackathon_advancedacl', $tab);
            }
        }
    }

    /**
     *
     * @param type $event
     */
    public function addAdminPermissionsRolePrepareSave($event)
    {
        $role = $event->getObject();
        /* @var Mage_Admin_Model_Role $role */
        $request = $event->getRequest();
        /* @var Mage_Core_Controller_Request_Http $request */
        if($request->getParam('all', false))
        {
            foreach($this->_helper()->getFields() as $field)
            {
                $role->setData($field, null);
            }
        } else {
            $websites = $request->getParam('websites', null);
            if(!is_array($websites))
            {
                $websites = (array)explode(',', $websites);
            }
            $role->setData('websites', implode(',', array_filter($websites)));
        }
    }

    /**
     *
     * @param Varien_Event $event
     */
    public function addRoleFilterToProductCollection($event)
    {
        $collection = $event->getCollection();
        /* @var Mage_Catalog_Model_Resource_Product_Collection $collection */

        if($this->_helper()->isAdminArea())
        {
            $websites = $this->_helper()->getActiveRole()->getWebsites();
            if(!is_null($websites))
            {
                $collection->addWebsiteFilter(array_filter((array)explode(',',$websites)));
            }
        }
    }

    /**
     *
     * @param Varien_Event $event
     */
    public function addRoleFilterToCustomerCollection($event)
    {
        $collection = $event->getCollection();
        /* @var Mage_Customer_Model_Resource_Customer_Collection $collection */
        if($this->_helper()->isAdminArea() && $collection instanceof Mage_Customer_Model_Resource_Customer_Collection)
        {
            $websites = $this->_helper()->getActiveRole()->getWebsites();
            if(!empty($websites))
            {
                $websites = array_filter((array)explode(',', $websites));
                $collection->addAttributeToFilter('website_id', array('in' => $websites));
            }
        }
    }

    /**
     * get role from registry, from eg. editRole Controller
     *
     * @return Mage_Admin_Model_Roles
     */
    protected function _getRole()
    {
        return Mage::registry('current_role');
    }

    /**
     *
     * @return bool
     */
    protected function _isAdmin()
    {
        $role = $this->_getRole();
        if($role)
        {
            return $this->_helper()->isAdmin($role->getId());
        }
        return false;
    }

    /**
     *
     * @return MagentoHackathon_AdvancedAcl_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('magentohackathon_advancedacl');
    }

    public function filterOrderGrid(Varien_Event_Observer $observer)
    {
        $collection = $observer->getOrderGridCollection();
        $collection->addAttributeToFilter('store_id', array('in' => array(2)));
    }
}

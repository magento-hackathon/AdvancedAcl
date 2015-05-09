<?php

class Loewenstark_Acl_Model_Observer
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
                $tab = $block->getLayout()->createBlock('loewenstark_acl/adminhtml_permissions_tab_Rolesextend', 'adminhtml.permissions.tab.loewenstark_acl');
                $block->addTab('loewenstark_acl', $tab);
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
     * @return Loewenstark_Acl_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('loewenstark_acl');
    }
}
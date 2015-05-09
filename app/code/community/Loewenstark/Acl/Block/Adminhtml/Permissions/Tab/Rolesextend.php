<?php

class Loewenstark_Acl_Block_Adminhtml_Permissions_Tab_Rolesextend
extends Mage_Adminhtml_Block_Widget_Form
implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function _construct()
    {
        parent::_construct();
    }
    
    protected function _initForm()
    {

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('adminhtml')->__('Role Information')));

        $fieldset->addField('websites', 'multiselect',
            array(
                'name'  => 'websites',
                'label' => $this->_helper()->__('Websites'),
                'id'    => 'websites',
                'values' => $this->_helper()->getWebsiteAsOption()
            )
        );
        
        $form->setValues($this->getRole()->getData());
        $this->setForm($form);
    }
    
    /**
     * 
     */
    protected function _beforeToHtml() {
        $this->_initForm();
        parent::_beforeToHtml();
    }

    /**
     * 
     * @return Mage_Admin_Model_Roles
     */
    protected function getRole()
    {
        return Mage::registry('current_role');
    }

    /**
     * 
     * @return Mage_Admin_Model_Roles
     */
    protected function getRoles()
    {
        return $this->getRole();
    }

    /**
     * 
     * @return Loewenstark_Acl_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('loewenstark_acl');
    }


    public function canShowTab()
    {
        return true;
    }

    public function getTabLabel()
    {
        return Mage::helper('loewenstark_acl')->__('Extended Role');
    }

    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    public function isHidden()
    {
        return false;
    }

}
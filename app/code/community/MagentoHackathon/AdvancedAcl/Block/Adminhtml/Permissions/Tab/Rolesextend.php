<?php

class MagentoHackathon_AdvancedAcl_Block_Adminhtml_Permissions_Tab_Rolesextend
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * @return MagentoHackathon_AdvancedAcl_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('magentohackathon_advancedacl');
    }

    protected function _initForm()
    {
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('adminhtml')->__('Advanced Acl'))
        );

        /**
         * Check is single store mode
         */
        if (!$this->_getHelper()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('cms')->__('Store View'),
                'title'     => Mage::helper('cms')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
        }

        $form->setValues($this->getRole()->getData());
        $this->setForm($form);
    }

    /**
     *
     */
    protected function _beforeToHtml()
    {
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
     * @return MagentoHackathon_AdvancedAcl_Helper_Data
     */
    protected function _helper()
    {
        return Mage::helper('magentohackathon_advancedacl');
    }


    public function canShowTab()
    {
        return true;
    }

    public function getTabLabel()
    {
        return Mage::helper('magentohackathon_advancedacl')->__('Advanced Acl');
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
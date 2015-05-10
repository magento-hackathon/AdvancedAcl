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
 * @author      Andreas Penz <testa.peta@gmail.com>
 */


/**
 * Class MagentoHackathon_AdvancedAcl_Model_Observer_Role
 */
class MagentoHackathon_AdvancedAcl_Model_Observer_Role
{

    /**
     * Add store restriction tab
     *
     * @param Varien_Event $event
     */
    public function addTabToRoles($event)
    {
        $block = $event->getBlock();

        /* @var Mage_Adminhtml_Block_Permissions_Editroles $block */
        if ($block instanceof Mage_Adminhtml_Block_Permissions_Editroles
            && Mage::helper('magentohackathon_advancedacl')->isAdmin()
        ) {
            $tab = $block->getLayout()->createBlock(
                'magentohackathon_advancedacl/adminhtml_permissions_tab_stores',
                'adminhtml.permissions.tab.magentohackathon_advancedacl'
            );
            $block->addTab('magentohackathon_advancedacl', $tab);
        }
    }

    /**
     * @param $roleId
     * @return mixed
     */
    public function lookupStoreIds($roleId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->_getTable('magentohackathon_advancedacl/role_store'), 'store_id')
            ->where('role_id = ?',(int)$roleId);

        return $adapter->fetchCol($select);
    }

    /**
     * @return mixed
     */
    protected function _getReadAdapter()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }

    /**
     * @return mixed
     */
    protected function _getWriteAdapter()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_write');
    }

    /**
     * @param $tableName
     * @return mixed
     */
    protected function _getTable($tableName)
    {
        return Mage::getSingleton('core/resource')->getTableName($tableName);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function addStoresToRoleModel(Varien_Event_Observer $observer)
    {
        /** @var Mage_Admin_Model_Roles $role */
        $role = $observer->getObject();
        /** @var Mage_Core_Controller_Request_Http $request */
        $request = $observer->getRequest();

        if ($stores = $request->getParam('stores')) {
            $role->setStores($stores);
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function saveAfter(Varien_Event_Observer $observer)
    {
        $object = $observer->getDataObject();

        if ($object instanceof Mage_Admin_Model_Roles) {
            if ($object->getStores()) {
                $oldStores = $this->lookupStoreIds($object->getId());
                $newStores = (array)$object->getStores();
                if (empty($newStores)) {
                    $newStores = (array)$object->getStoreId();
                }
                $table  = $this->_getTable('magentohackathon_advancedacl/role_store');
                $insert = array_diff($newStores, $oldStores);
                $delete = array_diff($oldStores, $newStores);

                if ($delete) {
                    $where = array(
                        'role_id = ?'     => (int) $object->getId(),
                        'store_id IN (?)' => $delete
                    );

                    $this->_getWriteAdapter()->delete($table, $where);
                }

                if ($insert) {
                    $data = array();

                    foreach ($insert as $storeId) {
                        $data[] = array(
                            'role_id'  => (int) $object->getId(),
                            'store_id' => (int) $storeId
                        );
                    }
                    $this->_getWriteAdapter()->insertMultiple($table, $data);
                }
            }
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function afterLoad(Varien_Event_Observer $observer)
    {
        $object = $observer->getDataObject();
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_ids', $stores);
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function beforeDelete(Varien_Event_Observer $observer)
    {
        $object = $observer->getDataObject();

        if ($object->getId()) {
            $condition = array(
                'role_id = ?' => (int)$object->getId(),
            );
            $this->_getWriteAdapter()->delete($this->_getTable('magentohackathon_advancedacl/role_store'), $condition);
        }
    }
}

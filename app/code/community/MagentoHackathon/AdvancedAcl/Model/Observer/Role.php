<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 09.05.15
 * Time: 13:31
 */

/**
 * Class MagentoHackathon_AdvancedAcl_Model_Observer_Role
 */
class MagentoHackathon_AdvancedAcl_Model_Observer_Role
{

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
            $object->setData('store_id', $stores);
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
            $this->_getWriteAdapter()->delete($this->_getTable('magentohackathon_advancedacl/rule_store'), $condition);
        }
    }
}

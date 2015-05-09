<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('magentohackathon_advancedacl/role_store'))
    ->addColumn('role_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
    ), 'Role ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Store ID')
    ->addIndex($installer->getIdxName('magentohackathon_advancedacl/role_store', array('store_id')),
        array('store_id'))
    ->addForeignKey($installer->getFkName('magentohackathon_advancedacl/role_store', 'role_id', 'admin/role', 'role_id'),
        'role_id', $installer->getTable('admin/role'), 'role_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('magentohackathon_advancedacl/role_store', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Admin Role To Store Linkage Table');
$installer->getConnection()->createTable($table);

$installer->endSetup();
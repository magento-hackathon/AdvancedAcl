<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('admin/role'), 'loewenstark_acl', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => '64k',
    'nullable' => true,
    'default' => null,
    'comment' => 'Loewenstark ACL field'
));

$installer->endSetup();

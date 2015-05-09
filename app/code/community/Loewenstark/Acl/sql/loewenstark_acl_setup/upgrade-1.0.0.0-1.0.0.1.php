<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('admin/role'), 'websites', array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 255,
    'nullable' => true,
    'default' => null,
    'comment' => 'Loewenstark Websites field'
));

$installer->getConnection()->dropColumn($installer->getTable('admin/role'), 'loewenstark_acl');

$installer->endSetup();

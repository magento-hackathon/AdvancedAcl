<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 10.05.15
 * Time: 11:11
 */

class MagentoHackathon_AdvancedAcl_Test_Helper_DataTests
    extends EcomDev_PHPUnit_Test_Case
{

    const HELPER_ALIAS = 'magentohackathon_advancedacl';

    public function testIsAllowedAccessForStoreWithoutStoreParam()
    {
        $rolesMock = $this->getModelMock('admin/roles', array('__call'));

        $rolesMock->expects($this->once())
            ->method('__call')
            ->with('getStoreIds')
            ->will($this->returnValue(array(1)));

        $this->replaceByMock('model', 'admin/roles', $rolesMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getDefaultStoreId', 'getActiveRole'));

        $helperMock->expects($this->once())
            ->method('getDefaultStoreId')
            ->will($this->returnValue(1));

        $helperMock->expects($this->once())
            ->method('getActiveRole')
            ->will($this->returnValue($rolesMock));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertTrue(
            Mage::helper(self::HELPER_ALIAS)->hasStoreViewAccess()
        );

        $rolesMock = $this->getModelMock('admin/roles', array('__call'));

        $rolesMock->expects($this->once())
            ->method('__call')
            ->with('getStoreIds')
            ->will($this->returnValue(array(2)));

        $this->replaceByMock('model', 'admin/roles', $rolesMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getDefaultStoreId', 'getActiveRole'));

        $helperMock->expects($this->once())
            ->method('getDefaultStoreId')
            ->will($this->returnValue(1));

        $helperMock->expects($this->once())
            ->method('getActiveRole')
            ->will($this->returnValue($rolesMock));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertFalse(
            Mage::helper(self::HELPER_ALIAS)->hasStoreViewAccess()
        );

    }

    public function testIsAllowedAccessForStoreWithStoreParam()
    {
        $rolesMock = $this->getModelMock('admin/roles', array('__call'));

        $rolesMock->expects($this->once())
            ->method('__call')
            ->with('getStoreIds')
            ->will($this->returnValue(array(1)));

        $this->replaceByMock('model', 'admin/roles', $rolesMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getDefaultStoreId', 'getActiveRole'));

        $helperMock->expects($this->once())
            ->method('getActiveRole')
            ->will($this->returnValue($rolesMock));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertTrue(
            Mage::helper(self::HELPER_ALIAS)->hasStoreViewAccess(1)
        );

        $rolesMock = $this->getModelMock('admin/roles', array('__call'));

        $rolesMock->expects($this->once())
            ->method('__call')
            ->with('getStoreIds')
            ->will($this->returnValue(array(1)));

        $this->replaceByMock('model', 'admin/roles', $rolesMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getDefaultStoreId', 'getActiveRole'));

        $helperMock->expects($this->once())
            ->method('getActiveRole')
            ->will($this->returnValue($rolesMock));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertFalse(
            Mage::helper(self::HELPER_ALIAS)->hasStoreViewAccess(2)
        );
    }

    public function testHasFullAccess()
    {
        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds', 'getActiveRole'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(3,4)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $coreStoreCollectionMock = $this->getResourceModelMock('core/store_collection', array('getAllIds'));

        $coreStoreCollectionMock->expects($this->once())
            ->method('getAllIds')
            ->will($this->returnValue(array(3,4)));

        $this->replaceByMock('resource_model', 'core/store_collection', $coreStoreCollectionMock);

        $this->assertTrue(
          Mage::helper(self::HELPER_ALIAS)->hasFullAccess()
        );

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds', 'getActiveRole'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(3,4)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $coreStoreCollectionMock = $this->getResourceModelMock('core/store_collection', array('getAllIds'));

        $coreStoreCollectionMock->expects($this->once())
            ->method('getAllIds')
            ->will($this->returnValue(array(1,2,3,4,5)));

        $this->replaceByMock('resource_model', 'core/store_collection', $coreStoreCollectionMock);

        $this->assertFalse(
            Mage::helper(self::HELPER_ALIAS)->hasFullAccess()
        );

    }

    public function testHasFullWebsiteAccess()
    {
        $coreWebsiteModelMock = $this->getModelMock('core/website', array('getStoreIds'));

        $coreWebsiteModelMock->expects($this->once())
            ->method('getStoreIds')
            ->will($this->returnValue(array(13)));

        $this->replaceByMock('model', 'core/website', $coreWebsiteModelMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(13)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertTrue(
            Mage::helper(self::HELPER_ALIAS)->hasFullWebsiteAccess('dummy')
        );

        $coreWebsiteModelMock = $this->getModelMock('core/website', array('getStoreIds'));

        $coreWebsiteModelMock->expects($this->once())
            ->method('getStoreIds')
            ->will($this->returnValue(array(13)));

        $this->replaceByMock('model', 'core/website', $coreWebsiteModelMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(12)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertFalse(
            Mage::helper(self::HELPER_ALIAS)->hasFullWebsiteAccess('dummy')
        );

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(12)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertTrue(
            Mage::helper(self::HELPER_ALIAS)->hasFullWebsiteAccess(
                (new Varien_Object())->setStoreIds(array(12))
            )
        );

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(12)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertFalse(
            Mage::helper(self::HELPER_ALIAS)->hasFullWebsiteAccess(
                (new Varien_Object())->setStoreIds(array(2))
            )
        );
    }

    public function testHasFullStoreGroupAccess()
    {
        $coreStoreGroupModelMock = $this->getModelMock('core/store_group', array('getStoreIds'));

        $coreStoreGroupModelMock->expects($this->once())
            ->method('getStoreIds')
            ->will($this->returnValue(array(4,5,6)));

        $this->replaceByMock('model', 'core/store_group', $coreStoreGroupModelMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(4,5,6)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertTrue(
            Mage::helper(self::HELPER_ALIAS)->hasFullStoreGroupAccess('dummy')
        );

        $coreStoreGroupModelMock = $this->getModelMock('core/store_group', array('getStoreIds'));

        $coreStoreGroupModelMock->expects($this->once())
            ->method('getStoreIds')
            ->will($this->returnValue(array(5)));

        $this->replaceByMock('model', 'core/store_group', $coreStoreGroupModelMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(30)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertFalse(
            Mage::helper(self::HELPER_ALIAS)->hasFullStoreGroupAccess('dummy')
        );

        $this->replaceByMock('model', 'core/store_group', $coreStoreGroupModelMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(5)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertTrue(
            Mage::helper(self::HELPER_ALIAS)->hasFullStoreGroupAccess(
                (new Varien_Object())->setStoreIds(array(5))
            )
        );

        $this->replaceByMock('model', 'core/store_group', $coreStoreGroupModelMock);

        $helperMock = $this->getHelperMock(self::HELPER_ALIAS, array('getAllowedStoreIds'));

        $helperMock->expects($this->once())
            ->method('getAllowedStoreIds')
            ->will($this->returnValue(array(9)));

        $this->replaceByMock('helper', self::HELPER_ALIAS, $helperMock);

        $this->assertFalse(
            Mage::helper(self::HELPER_ALIAS)->hasFullStoreGroupAccess(
                (new Varien_Object())->setStoreIds(array(5))
            )
        );

    }

}
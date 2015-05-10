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

}
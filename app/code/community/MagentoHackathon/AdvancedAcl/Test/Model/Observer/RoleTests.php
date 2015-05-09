<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 09.05.15
 * Time: 15:32
 */

class MagentoHackathon_AdvancedAcl_Test_Model_Observer_RoleTests extends EcomDev_PHPUnit_Test_Case
{

    public function testAddStoresToRoleModelAddsNothing()
    {
        $storeIds = array();
        $roleObjectMock = $this->getMockBuilder('Varien_Event_Observer')->getMock();

        $roleObjectMock->expects($this->never())
            ->method('__call')
            ->with(
                $this->equalTo('setStores')
            );

        $event = (new Varien_Event_Observer())
            ->setData('object', $roleObjectMock)
            ->setRequest(
                (new Mage_Core_Controller_Request_Http())->setParam('stores', $storeIds)
            );

        Mage::getModel('magentohackathon_advancedacl/observer_role')->addStoresToRoleModel($event);
    }

    public function testAddStoresToRoleModelAddsStores()
    {
        $storeIds = array(1,2,3);
        $roleObjectMock = $this->getMockBuilder('Varien_Event_Observer')->getMock();

        $roleObjectMock->expects($this->once())
            ->method('__call')
            ->with(
                $this->equalTo('setStores'),
                $this->equalTo(array($storeIds))
            );

        $event = (new Varien_Event_Observer())
            ->setData('object', $roleObjectMock)
            ->setRequest(
                (new Mage_Core_Controller_Request_Http())->setParam('stores', $storeIds)
            );

        Mage::getModel('magentohackathon_advancedacl/observer_role')->addStoresToRoleModel($event);
    }

}

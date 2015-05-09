<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 09.05.15
 * Time: 14:39
 */

class MagentoHackathon_AdvancedAcl_Test_Config_ObserverRoleConfigTEsts
    extends EcomDev_PHPUnit_Test_Case_Config
{
    public function testEventObserversDefined()
    {
        $this->assertEventObserverDefined(
            'global',
            'admin_permissions_role_prepare_save',
            'magentohackathon_advancedacl/observer_role',
            'addStoresToRoleModel'
        );

        $this->assertEventObserverDefined(
            'global',
            'admin_roles_save_after',
            'magentohackathon_advancedacl/observer_role',
            'saveAfter'
        );

        $this->assertEventObserverDefined(
            'global',
            'admin_roles_load_after',
            'magentohackathon_advancedacl/observer_role',
            'afterLoad'
        );

        $this->assertEventObserverDefined(
            'global',
            'admin_roles_delete_before',
            'magentohackathon_advancedacl/observer_role',
            'beforeDelete'
        );
    }
}
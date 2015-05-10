<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 10.05.15
 * Time: 11:01
 */

class MagentoHackathon_AdvancedAcl_Test_Config_ObserverUrlConfigTests
    extends EcomDev_PHPUnit_Test_Case_Config
{
    public function testEventObserversDefined()
    {
        $this->assertEventObserverDefined(
            'adminhtml',
            'controller_action_predispatch_adminhtml_catalog_product_edit',
            'magentohackathon_advancedacl/observer_url',
            'catalogProductEdit'
        );

        $this->assertEventObserverDefined(
            'adminhtml',
            'controller_action_predispatch_adminhtml_catalog_category_edit',
            'magentohackathon_advancedacl/observer_url',
            'catalogCategoryEdit'
        );
    }
}

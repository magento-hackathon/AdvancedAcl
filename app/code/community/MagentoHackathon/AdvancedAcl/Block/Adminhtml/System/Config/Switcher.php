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
 * Block definition for Store Switcher
 *
 * @category    MagentoHackathon
 * @package     MagentoHackathon_AdvancedAcl
 */

class MagentoHackathon_AdvancedAcl_Block_Adminhtml_System_Config_Switcher
    extends Mage_Adminhtml_Block_System_Config_Switcher
{

    public function getStoreSelectOptions()
    {
        $options = parent::getStoreSelectOptions();

        $helper = Mage::helper('magentohackathon_advancedacl');
        if (false === $helper->hasFullAccess()) {
            unset($options['default']);
            foreach ($options as $key=>$option) {
                if (preg_match('/^website_(.+)$/', $key, $matches)) {
                    if (false === $helper->hasFullWebsiteAccess($matches[1])) {
                        unset($options[$key]);
                    }
                } elseif (preg_match('/^group_(.+)_(open|close)$/', $key, $matches)) {
                    if (false === $helper->hasFullStoreGroupAccess($matches[1])) {
                        unset($options[$key]);
                    }
                } elseif (preg_match('/^store_(.+)$/', $key, $matches)) {
                    if (false === $helper->hasStoreViewAccess($matches[1])) {
                        unset($options[$key]);
                    }
                }
            }
        }

        return $options;
    }
}

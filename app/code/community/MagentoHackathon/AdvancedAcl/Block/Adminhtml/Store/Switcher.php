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

class MagentoHackathon_AdvancedAcl_Block_Adminhtml_Store_Switcher
    extends Mage_Adminhtml_Block_Store_Switcher
{

    protected function _construct() {
        parent::_construct();
    }


    /**
     * @deprecated since version from magento
     */
    public function getWebsiteCollection()
    {
        $collection = Mage::getModel('core/website')->getResourceCollection();

        $websiteIds = $this->getWebsiteIds();
        if (!is_null($websiteIds)) {
            $collection->addIdFilter($this->getWebsiteIds());
        }

        $websites = $this->_getNinWebsitesIds();
        if(!empty($websites))
        {
            $collection->addFieldToFilter('website_id', array('nin', $websites));
        }
        return $collection->load();
    }


    /**
     * Get websites
     *
     * @return array
     */
    public function getWebsites()
    {
        $websites = parent::getWebsites();
        $result = array();
        foreach($websites as $website)
        {
            if(in_array($website->getWebsiteId(), $this->_getNinWebsitesIds()))
            {
                $result[] = $website;
            }
        }
        return $result;
    }

    /**
     * @deprecated since version from magento
     */
    public function getGroupCollection($website)
    {
        if (!$website instanceof Mage_Core_Model_Website) {
            $website = Mage::getModel('core/website')->load($website);
        }

        $collection = $website->getGroupCollection();
        $websites = $this->_getNinWebsitesIds();
        if(!empty($websites))
        {
            $collection->addFieldToFilter('website_id', array('nin', $websites));
        }
        return $collection;
    }

    /**
     * Get store groups for specified website
     *
     * @param Mage_Core_Model_Website $website
     * @return array
     */
    public function getStoreGroups($website)
    {
        return parent::getGroups();
    }

    /**
     *
     * @return array
     */
    protected function _getNinWebsitesIds()
    {
        return Mage::helper('magentohackathon_advancedacl')->getWebsitesIds();
    }
}

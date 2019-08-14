<?php
/**
 * Magento
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
 * @category    Netsol
 * @package     Netsol_Orderhistory
 * @copyright   Copyright (c) 2016 Netsolutions India (http://www.netsolutions.in)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Netsol_Orderhistory_Model_Paattribute extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
	
	/**
	 * @event:Trigger before save product 
	 * @return saved object value of warranty period in db.
	 * */
	public function beforeSave($object) 
    { 
		 $this->getsetting = Mage::helper('orderhistory/data');
		 
		if(Mage::helper('core')->isModuleEnabled('Netsol_Orderhistory') && $this->getsetting->isEnabled()) 
		{ 
			/**before saving the product check if the attribute `warranty_period` is array.
			 * if it is, save warranty year, warranty period, wararnty days in the db **/
			$attributeCode = $this->getAttribute()->getAttributeCode();
			$data = $object->getData($attributeCode);
			$attributeValue = $data['warranty_years']. "-" .$data['warranty_months']. "-". $data['warranty_days']; 
			if (is_array($data)) {
				$data = array_filter($data);
				$object->setData($attributeCode, $attributeValue);
			}
			return parent::beforeSave($object);
		}
    }

}

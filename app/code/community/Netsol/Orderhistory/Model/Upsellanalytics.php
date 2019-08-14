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
 
class Netsol_Orderhistory_Model_Upsellanalytics extends Mage_Core_Model_Abstract
{
	/**
	 * General setting instance
	 *
	 * @var  Netsol_Orderhistory_Helper_Data 
	 */
    protected $getsetting = null;
    
	/**
	 * initiate instance
	 */
    protected function _construct() {
       $this->_init("orderhistory/upsellanalytics");
       $this->getsetting = Mage::helper('orderhistory/data');
    }

    /**
	 * @description: Get last 5 orders product upsell collection of 
	 * logged in customer
	 * @return  $upSellPid (product id)
	 * */
	public function upSellIteratorMehod() {
		/**if CrossUpSell config is enabled**/
		$upSellPid = array();
		$websiteId = Mage::app()->getWebsite()->getId();
		if($this->getsetting->getUpSellEnable()){
			/**fetch 5 recent orders of logged in customer start**/
			$ordersCollections = Mage::getResourceModel('sales/order_collection')
			->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
			->addFieldToFilter('status', 'complete')
			->setOrder('created_at', 'desc')
			->setPageSize(5);
			/**fetch 5 recent orders end**/
			
			/***Collection of order item ids start**/
			foreach($ordersCollections as $order):
				$orderItems = $order->getAllVisibleItems();
				foreach($orderItems as $item)
				{
					$parentId = $item->getParentItemId();
					$pid = $item->getProductId();
					$itemIds[] = ($parentId != '') ? $parentId : $pid;
				}
			endforeach;
			/***Collection of order item ids end**/
			
			/***Collection of up sell product ids in array variable start**/
			if(!empty($itemIds)) {

				foreach($itemIds as $itemId)
				{
					$product = Mage::getModel('catalog/product')->load($itemId);
					$upsellProductCollection = $product->getUpSellProductCollection();

					foreach($upsellProductCollection as $pdt)
					{
						$pdt = $pdt->getData();
						$uproduct = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('entity_id');
						$uproduct->addAttributeToFilter('entity_id',array('eq' => $pdt['entity_id']));
						$uproduct->addWebsiteFilter($websiteId);
						$uproduct->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
						$uproduct->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
						Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($uproduct);
						$uproduct = $uproduct->getData();
						if(!empty($uproduct)){
							$upSellPid[] = $uproduct[0]['entity_id']; 
						}
					} 
				}
				$upSellPid = array_slice($upSellPid,0,$this->getsetting->getNoOfDisplayProductWeightage());
				/***Collection of cross sell product ids end**/	
			}
			
		}
		return $upSellPid;
	}
}

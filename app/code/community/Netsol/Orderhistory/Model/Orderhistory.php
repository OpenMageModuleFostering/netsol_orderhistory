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
 * @copyright   Copyright (c) 2015 Netsolutions India (http://www.netsolutions.in)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Netsol_Orderhistory_Model_Orderhistory extends Mage_Core_Model_Abstract
{
	
	/**
	 * initiate instance
	 */
    protected function _construct()
    { 
       $this->_init("orderhistory/orderhistory");
    }
    
    /**
	 * @description: Resource iterator technique allows 
	 * you to get each item one by one through the function callback
	 *  using the walk function in core/resource_iterator
	 * 
	 * @param 
	 * @return  
	 * */
	public function orderIteratorMehod()
	{
		$orderItemIds = array();
		$productIds = array();
		$orderProductId = array();
		/*** Collection orders and its order item ids ***/
		$time = time();
		$helper = Mage::helper('orderhistory/data');
		$orderPeriod = $helper->getOrderHistoryPeriod();
		$maxProductCount = $helper->getMaxProductCount();
		$numberOfProductDisplay =  $helper->getNoOfDisplayProductWeightage();
		$websiteId = Mage::app()->getWebsite()->getId();
		$to = date('Y-m-d', $time); // current date
		$from = date("Y-m-d", strtotime("-".$orderPeriod)); //orders from which period to till date
		$crosellProductIds = Mage::getModel('orderhistory/crosssellanalytics')->crossSellIteratorMehod();
		$upsellProductIds = Mage::getModel('orderhistory/upsellanalytics')->upSellIteratorMehod();
		/* Get the collection */
		$orders = Mage::getResourceModel('sales/order_collection')
					->addFieldToFilter('customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId())
					->addAttributeToFilter('created_at', array('from'=>$from, 'to'=>$to))
					->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
					->setOrder('updated_at', 'DESC');

		/***Collection of order item ids start**/
		foreach($orders as $order)
		{
			$orderCreatedAt = $order->getCreatedAt();
			$orderItems = $order->getAllVisibleItems();
			foreach($orderItems as $item)
			{
				$pid = $item->getProductId();
				$orderItemIds[$pid]= $orderCreatedAt; // collection order item id
			}
		}
		
		
		/***Collection of order item ids end**/
		if(!empty($orderItemIds)) {
			$orderProductId = array_keys($orderItemIds);
		}
		
		/***product ids collection for checking warranty period and predictive style of each product***/
		if(!empty($orderProductId)) {
			$i=0;
			
			foreach($orderProductId as $productId) { 
				$product = Mage::getModel('catalog/product')->getCollection();
				$product->addAttributeToFilter('entity_id',array('eq' => $productId));
				$product->addWebsiteFilter($websiteId);
				$product->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
				$product->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
				Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($product);
				$product = $product->getData();
				if(!empty($product)) {
					$product = Mage::getModel('catalog/product')->load($productId); 
				
					$productid = $product->getId();
					$warranty = $product->getWarrantyPeriod();
					
					//if product warranty and predictive style value is empty then fetch default warranty and predictive style value
					if($warranty == '' || $warranty == '0-0-0'){ 
						$warranty = $helper->getWarrantyPeriodDefaultValue();			
					}	

					$deliverDate = $orderItemIds[$productid];
					$orderDeliverydate = date('Y-m-d', strtotime($deliverDate));
					$warrantyOfProduct = explode("-",$warranty); 
					$startPeriodTodisplay = round((1/3 *$helper->getTimePeriodToDisplayProduct())); //one third of buffer
					$endPeriodTodisplay = round((2/3 *$helper->getTimePeriodToDisplayProduct())); //two third of buffer
					$warrantyPeriod = date("Y-m-d",strtotime(date("Y-m-d", strtotime($orderDeliverydate)) . " + ".$warrantyOfProduct[2]." days + " .$warrantyOfProduct[1]." month + ".$warrantyOfProduct[0]." years"));
					$startPeriodTodisplay = date('Y-m-d',strtotime($warrantyPeriod ." - ".$startPeriodTodisplay." days")); // one third of buffer is subtract to start the product display
					$endPeriodTodisplay = date('Y-m-d',strtotime($warrantyPeriod ." + ".$endPeriodTodisplay." days"));
					$currDate = date('Y-m-d');
					
					/** 
					 * true if current date comes between start and end period to display
					 * **/
					if($startPeriodTodisplay <= $currDate && $currDate <= $endPeriodTodisplay) 
					{ 
					   /**predictive style is same*/
						$productIds[] = $product->getId();

					}		
				}
				$i++;
			}
			if(empty($crosellProductIds) && empty($upsellProductIds) && !empty($productIds)) {
				$productIds = array_slice($productIds,0,$maxProductCount);
			} elseif((empty($crosellProductIds) || empty($upsellProductIds)) && !empty($productIds)) {
				$numberOfProductDisplay = ceil($maxProductCount / 2 );
				$productIds = array_slice($productIds,0,$numberOfProductDisplay);
			} elseif(!empty($productIds)) {
				$productIds = array_slice($productIds,0,$numberOfProductDisplay);
			}

		}

		/*** end of product ids collection ***/		
		return $productIds;
	}
}

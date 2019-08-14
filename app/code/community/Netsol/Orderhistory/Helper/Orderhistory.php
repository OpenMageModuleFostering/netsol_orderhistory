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
class Netsol_Orderhistory_Helper_Orderhistory extends Mage_Core_Helper_Abstract
{
	 const XML_PATH_PREDICTIVE_EMAIL_TEMPLATE = 'pa_orderhistorysetting/paorderhistory/orderhistory_email';
	 const XML_PATH_PREDICTIVE_EMAIL_IDENTITY = 'pa_orderhistorysetting/paorderhistory/orderhistory_email_email_sender';
	 
	 
	 /**
	 * @description: Resource iterator technique allows 
	 * you to get each item one by one through the function callback
	 *  using the walk function in core/resource_iterator
	 * 
	 * @param 
	 * @return  
	 * */
	public function orderIterator($eachCronCustomerCount,$start)
	{
		try 
        {
			$orderItemIds = array();
			$productIds = array();
			$orderProductId = array();
			/*** Collection orders and its order item ids ***/
			$time = time();
			$helper = Mage::helper('orderhistory/data');
			$orderPeriod = $helper->getOrderHistoryPeriod();
			$numberOfProductDisplay =  $helper->getNoOfDisplayProductWeightage();
			$websiteId = Mage::app()->getWebsite()->getId();
			$to = date('Y-m-d', $time); // current date
			$from = date("Y-m-d", strtotime("-".$orderPeriod)); //orders from which period to till date
			$customerCollections = Mage::getResourceModel('sales/order_collection')
									->addAttributeToSelect('status');
			$customerCollections->getSelect()->order('customer.entity_id')->limit($eachCronCustomerCount,$start)->group('customer.entity_id')->join( array('customer'=> 'customer_entity'),
								'customer.entity_id=main_table.customer_id',array('customer.entity_id')); 
			$customerCollections->addFieldToFilter('main_table.status', 'complete');
			
			$j=0;
			foreach($customerCollections as $customer)
			{

				/* Get the collection */
				$orders = Mage::getResourceModel('sales/order_collection')
							->addFieldToFilter('customer_id', $customer->getEntityId())
							->addAttributeToFilter('created_at', array('from'=>$from, 'to'=>$to))
							->addAttributeToFilter('status', array('eq' => Mage_Sales_Model_Order::STATE_COMPLETE))
							->setOrder('updated_at', 'DESC');
				foreach($orders as $order)
				{
					$orderCreatedAt = $order->getCreatedAt();
					$orderItems = $order->getAllVisibleItems();
					foreach($orderItems as $item)
					{
						$pid = $item->getProductId();
						$orderItemIds[$j][$pid]= $orderCreatedAt; // collection order item id
					}
				}
				//echo "<pre>";
				//print_r($orderItemIds);
				/***Collection of order item ids end**/
				if(!empty($orderItemIds[$j])) {
					$orderProductId = array_keys($orderItemIds[$j]);
				}

				if(!empty($orderProductId)) {
					$i=0;
					foreach($orderProductId as $productId) { 	
						$product = Mage::getModel('catalog/product')->getCollection();
						$product->addAttributeToFilter('entity_id',array('eq' => $productId));
						//$product->addWebsiteFilter($websiteId);
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

							$deliverDate = $orderItemIds[$j][$productid];
							$orderDeliverydate = date('Y-m-d', strtotime($deliverDate));
							$warrantyOfProduct = explode("-",$warranty); 
							
							$emailSendPeriod = $helper->getEmailTriggerDays();
							
							$warrantyPeriod = date("Y-m-d",strtotime(date("Y-m-d", strtotime($orderDeliverydate)) . " + ".$warrantyOfProduct[2]." days + " .$warrantyOfProduct[1]." month + ".$warrantyOfProduct[0]." years"));
							$emailSendPeriod = date('Y-m-d',strtotime($warrantyPeriod ." - ".$emailSendPeriod." days"));
							$currDate = date('Y-m-d');
							
							/** 
							 * true if current date comes between start and end period to display
							 *
							 * **/
							if($emailSendPeriod == $currDate) 
							{ 
							   /**predictive style is same*/
								$productIds[$customer->getEntityId()][] = $product->getId();
							
							}
						}		
						$i++;
					}
				}
				
				$j++;
			}
			$this->predictiveAnalysisEmail($productIds);

	    } catch (Exception $e) {
			Mage::printException($e);
	    }

	}
	
	protected function predictiveAnalysisEmail($pids = array())
	{
		try {
			$customerIds = array_keys($pids);
			$productIds = array();
			$productIdsDiff = array();
			foreach($customerIds as $cid) {
				$cusomterDetail = Mage::getModel('customer/customer')->load($cid);
		
				$customerName = $cusomterDetail->getName();
				$customerEmail = $cusomterDetail->getEmail();

				if(!empty($pids[$cid]))
				{
					
					$paCustEmailModel = Mage::getModel('orderhistory/patempentry')->getCollection();
					$paCustEmailModel->addFieldToSelect('pid');
					$paCustEmailModel->addFieldToFilter('pid',array('in'=>$pids[$cid]));
					$paCustEmailModel->addFieldToFilter('custid',array('eq'=>$cid));
					$paCustEmailModel->addFieldToFilter('mailsent',array('eq'=>1));
					foreach($paCustEmailModel as $pid) {
						$productIds[] = $pid->getPid();
					}
					$productIdsDiff = array_diff($pids[$cid],$productIds);
					
					if(!empty($productIdsDiff)) { 
						
						$emailVars = array('customer_name' => $customerName,' customer_email' => $customerEmail,'products' => $productIdsDiff);
						$emailTemplate  = Mage::getModel('core/email_template')->loadDefault(  Mage::getStoreConfig(self::XML_PATH_PREDICTIVE_EMAIL_TEMPLATE));
						$sender =  Mage::getStoreConfig(self::XML_PATH_PREDICTIVE_EMAIL_IDENTITY);
						$processedTemplate = $emailTemplate->getProcessedTemplate($emailVars);
						$emailTemplate->setSenderName($sender);
						$emailTemplate->setSenderEmail($sender);
						$emailTemplate->setType('html');
						$emailTemplate->setTemplateSubject('Inspired by your order history!');
						$emailTemplate->send($customerEmail,$emailTemplateVariables);

						foreach($productIdsDiff as $pid){ 
							$paCustomerEmailModel = Mage::getModel('orderhistory/patempentry');
							/**CustomerEmail records according product id with sent email flag**/
							$paCustomerEmailModel->setCustid($cid);
							$paCustomerEmailModel->setPid($pid);
							$paCustomerEmailModel->setMailsent(1);
							$paCustomerEmailModel->setCreatedAt(date('Y-m-d'));
							$paCustomerEmailModel->save();
							/**CustomerEmail records according product id with sent email flag**/
							
						}
					}
					
					
				}
			}
			//$paCustomerEmailTempModel = Mage::getModel('predictiveanalytics/patemp');
		} catch(Exception $e) {
			echo $e->getMessage(); die;
		}
	} 
}

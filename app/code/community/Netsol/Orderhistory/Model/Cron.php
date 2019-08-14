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
 
class Netsol_Orderhistory_Model_Cron
{
	/**
	  *
	  * @var number of cron tab
	  */
	protected $_numberOfCronTabs = 5;
	
	/**
	  *	@desciption: Cron job task one trigger based on customers
	  * @param : $observer
	  * @return email trigger based on recommendation
	  */
    public function oneEmailWarrantyProducts($observer) 
    {
       try 
       {
		   
			$cronCount = 0;
		    $eachCronCustomerCount = $this->_limitOfEachCronJob();
		    $start = $cronCount;
		    $helper = Mage::helper('orderhistory/orderhistory');
			$orderPeriod = $helper->orderIterator($eachCronCustomerCount,$start);
			
	
	   } catch (Exception $e) {
			Mage::printException($e);
	   }
    }
    
    /**
	  *	@desciption: Cron job task two trigger based on customers
	  * 
	  * @return email trigger based on recommendation
	  */
    public function twoEmailWarrantyProducts($observer) 
    {
       try 
       {
			$cronCount = 1;
		    $eachCronCustomerCount = $this->_limitOfEachCronJob(); 
		    $start = $cronCount * $eachCronCustomerCount;
		    $helper = Mage::helper('orderhistory/orderhistory');
			$orderPeriod = $helper->orderIterator($eachCronCustomerCount,$start);
	
	   } catch (Exception $e) {
			Mage::printException($e);
	   }
    }
    
    /**
	  *	@desciption: Cron job task three trigger based on customers
	  * 
	  * @return email trigger based on recommendation
	  */
    public function threeEmailWarrantyProducts($observer) 
    {
       try 
       {
			$cronCount = 2;
		    $eachCronCustomerCount = $this->_limitOfEachCronJob(); 
		    $start = $cronCount * $eachCronCustomerCount;
		    $helper = Mage::helper('orderhistory/orderhistory');
			$orderPeriod = $helper->orderIterator($eachCronCustomerCount,$start);
	
	   } catch (Exception $e) {
			Mage::printException($e);
	   }
    }
    
     /**
	  *	@desciption: Cron job task four trigger based on customers
	  * 
	  * @return email trigger based on recommendation
	  */
    public function fourEmailWarrantyProducts($observer) 
    {
       try 
       {
			$cronCount = 3;
		    $eachCronCustomerCount = $this->_limitOfEachCronJob(); 
		    $start = $cronCount * $eachCronCustomerCount;
		    $helper = Mage::helper('orderhistory/orderhistory');
			$orderPeriod = $helper->orderIterator($eachCronCustomerCount,$start);
	
	   } catch (Exception $e) {
			Mage::printException($e);
	   }
    }
    
    /**
	  *	@desciption: Cron job task five trigger based on customers
	  * 
	  * @return email trigger based on recommendation
	  */
    public function fiveEmailWarrantyProducts($observer) 
    {
       try 
       {
			$cronCount = 4;
		    $eachCronCustomerCount = $this->_limitOfEachCronJob(); 
		    $start = $cronCount * $eachCronCustomerCount;
		    $helper = Mage::helper('orderhistory/orderhistory');
			$orderPeriod = $helper->orderIterator($eachCronCustomerCount,$start);
	
	   } catch (Exception $e) {
			Mage::printException($e);
	   }
    }
    /**
	 * @description 
	 * @param		$observer
	 * @return		
	 */
    protected function _limitOfEachCronJob()
    {
	   try 
       {
		   
			$numberOfCronTabs = $this->_numberOfCronTabs;
			//$numberOfCustomers = Mage::getModel('customer/customer')->getCollection()->getSize(); //Number of customers in websites
			$customerCollections = Mage::getResourceModel('sales/order_collection')
									->addAttributeToSelect('status');
			$customerCollections->getSelect()->order('customer.entity_id')->group('customer.entity_id')->join( array('customer'=> Mage::getConfig()->getTablePrefix().'customer_entity'),
								'customer.entity_id=main_table.customer_id',array('customer.entity_id')); 
			$customerCollections->addFieldToFilter('main_table.status', 'complete');
			$numberOfCustomers = count($customerCollections);
			$eachCronCustomerCount = ($numberOfCustomers > 10) ? ceil($numberOfCustomers/$numberOfCronTabs) : $numberOfCustomers;  //each cron customers count
			
			//s$paCustEmailModel->addAttribueToSelect('*');
			$currDate = date('Y-m-d');
			$deleteDate = date('Y-m-d',strtotime($currDate ." - 15 days"))." 00:00:00"; 
			$paCustEmailModel = Mage::getModel('orderhistory/patempentry')->getCollection();
			$paCustEmailModel->addFieldToFilter('created_at',array('eq'=>$deleteDate));
			 try {
				 
				 foreach($paCustEmailModel as $pasCustomEmail) {
					 $pasCustomEmail->delete();
					 echo "Data deleted successfully.";
				 }
				
			} catch (Exception $e){
				echo $e->getMessage(); 
			}
			return $eachCronCustomerCount;
	   } catch (Exception $e) {
			Mage::printException($e);
	   }
	}
}

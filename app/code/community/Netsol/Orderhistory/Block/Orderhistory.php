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
class Netsol_Orderhistory_Block_Orderhistory extends Mage_Catalog_Block_Product_Abstract
{   
	/**
	 * General setting instance
	 *
	 * @var  Netsol_Orderhistory_Helper_Data 
	 */
    protected $getsetting = null;
    
    /**
	 * orderHistory setting instance
	 *
	 * @var  Netsol_Orderhistory_Helper_Data 
	 */
    protected $predictiveAnalysisBlock = null;
    
    /**
	 * numberOfProduct todisplay setting instance
	 *
	 * @var  Netsol_Orderhistory_Helper_Data 
	 */
    protected $noOfproducts = null;
    
     /**
	 * initiate instance
	 *
	 * @var  Netsol_Orderhistory_Helper_Data 
	 */
    public function __construct() {
		   
        $this->getsetting = Mage::helper('orderhistory/data');
        $this->noOfproducts = $this->getsetting->getMaxProductCount();
    }    
    
    /**
     * @description Retrieve collection based on setting
     *	
     * @return	productIds
     */
    public function predictiveAnalysisBlock()
    {
		if ($this->getsetting->isEnabled ()) {
				$this->predictiveAnalysisBlock = $this->_predictiveAnalysisBlock();
				return $this->predictiveAnalysisBlock;

		}
	}
	
	/**
	 * @description: Based on Warranty Period and 
	 * preditive style of product of product and customer order item history

	 * @return  $productIds
	 * */
	 protected function getorderHistoryProducts()
	 {
		 try {
			$orderProductIds = Mage::getModel('orderhistory/orderhistory')->orderIteratorMehod();
			$productIds = array_unique($orderProductIds);
			
			return $productIds;
		 } catch(Exception $e) {
			echo $e->getMessage(); die;
		}
	 }

	 /**
	 * @description: Based on Warranty Period and 
	 * preditive style of product of product and customer order item history
	 * 
	 * @return  $productIds
	 * */
	 protected function _predictiveAnalysisBlock()
	 {
		 
		 if(Mage::getSingleton('customer/session')->isLoggedIn())
		 {
			$productCollection = array();
			$productIds = array();
			$crosellProductIds = array();
			$upsellProductIds = array();
			/**product ids collection from order history , crossell, upsell start**/
			$productIds = $this->getorderHistoryProducts();
			if($this->getsetting->getCrossSellEnable()) {
				$crosellProductIds = Mage::getModel('orderhistory/crosssellanalytics')->crossSellIteratorMehod();
			}

			if($this->getsetting->getUpSellEnable()) {
				$upsellProductIds = Mage::getModel('orderhistory/upsellanalytics')->upSellIteratorMehod();
			}
			$productIds = array_merge($productIds,$upsellProductIds,$crosellProductIds);
			$productIds = array_unique($productIds);
				
			return $productIds;
		 }
		
	 }

}

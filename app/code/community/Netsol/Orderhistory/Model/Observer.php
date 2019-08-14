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
 
class Netsol_Orderhistory_Model_Observer
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
    public function __construct() {   
        $this->getsetting = Mage::helper('orderhistory/data');
    }    

	/**
	 * @description Event after layout block generated
	 * Display block according to page selected at admin config
	 * Display block according to position selected at admin config 
	 * if left postion is enable, we'll unset right and content
	 * @param		$observer
	 * @return		no
	 */
	public function unsetOrderhistorylayoutBlocks($observer)
	{	
		/**If admin is not logged in**/
		if(!Mage::app()->getStore()->isAdmin()) {
			$homePagePosition	   = $this->getsetting->getHomePageShowInPosition();
			$productDetailPagePosition = $this->getsetting->getProductDetailShowInPosition();
			$cartPagePosition	   = $this->getsetting->getCartPageShowInPosition();
			$observerData         = $observer->getAction()->getLayout();
			$template	           = $observerData->getBlock('root')->getTemplate();
			$currentPageHandle     = Mage::app()->getFrontController()->getAction()->getFullActionName();
			/**if module is enabled**/
			if($this->getsetting->isEnabled()){
				if($currentPageHandle == 'cms_index_index') { //if homepage
					if($homePagePosition == "left") { //if block position is left
						$blockContent = $observerData->getBlock('content'); 
						// unset predictiveAnalytics block template content 
						if($blockContent) {  
							$blockContent->unsetChild('custom_pacontent');
						}
						$blockRight = $observerData->getBlock('right');  
						if($blockRight) {
							$blockRight->unsetChild('custom_paright');
						}
					}
					elseif($homePagePosition == "content") {
						$blockLeft = $observerData->getBlock('left');  
						if($blockLeft) {
							$blockLeft->unsetChild('custom_paright');
						}
						$blockRight = $observerData->getBlock('right');  
						if($blockRight) {
							$blockRight->unsetChild('custom_paright');
						}
					}
					elseif($homePagePosition == "right") {
						$blockLeft = $observerData->getBlock('left');  
						if($blockLeft) {
							$blockLeft->unsetChild('custom_paright');
						}
						$blockContent = $observerData->getBlock('content');  
						if($blockContent) {
							$blockContent->unsetChild('custom_pacontent');
						}
					}
					elseif($homePagePosition == "none") {
						$blockLeft = $observerData->getBlock('left');  
						if($blockLeft) {
							$blockLeft->unsetChild('custom_paright');
						}
						$blockRight = $observerData->getBlock('right');  
						if($blockRight) {
							$blockRight->unsetChild('custom_paright');
						}
						$blockContent = $observerData->getBlock('content');  
						if($blockContent) {
							$blockContent->unsetChild('custom_pacontent');			
						}
					}
		
				}
				elseif($currentPageHandle == 'catalog_product_view'){ //if catalog product view
					if($productDetailPagePosition == "left") {
						$blockContent = $observerData->getBlock('content');  
						if($blockContent) {
							$blockContent->unsetChild('custom_pacontent');
						}
						$blockRight = $observerData->getBlock('right');  
						if($blockRight) {
							$blockRight->unsetChild('custom_paright');
						}
					}
					elseif($productDetailPagePosition == "content") {
						$blockLeft = $observerData->getBlock('left');  
						if($blockLeft) {
							$blockLeft->unsetChild('custom_paright');
						}
						$blockRight = $observerData->getBlock('right');  
						if($blockRight) {
							$blockRight->unsetChild('custom_paright');
						}
					}
					elseif($productDetailPagePosition == "right") {
						$blockLeft = $observerData->getBlock('left'); 
						if($blockLeft) { 
							$blockLeft->unsetChild('custom_paright');
						}
						$blockContent = $observerData->getBlock('content');  
						if($blockContent) {
							$blockContent->unsetChild('custom_pacontent');
						}
					}
					elseif($productDetailPagePosition == "none") {
						$blockLeft = $observerData->getBlock('left');  
						if($blockLeft) {
							$blockLeft->unsetChild('custom_paright');
						}
						$blockRight = $observerData->getBlock('right');  
						if($blockRight) {
							$blockRight->unsetChild('custom_paright');
						}
						$blockContent = $observerData->getBlock('content');  
						if($blockContent) {
							$blockContent->unsetChild('custom_pacontent');			
						}
					}
				}
				elseif($currentPageHandle == 'checkout_cart_index'){ //if cart page
					if($cartPagePosition == "left") {
						$blockContent = $observerData->getBlock('content'); 
						if($blockContent) { 
							$blockContent->unsetChild('custom_pacontent');
						}
						$blockRight = $observerData->getBlock('right');  
						if($blockRight){
							$blockRight->unsetChild('custom_paright');
						}
					}
					elseif($cartPagePosition == "content") {
						$blockLeft = $observerData->getBlock('left');  
						if($blockLeft){
							$blockLeft->unsetChild('custom_paright');
						}
						$blockRight = $observerData->getBlock('right');  
						if($blockRight) {
							$blockRight->unsetChild('custom_paright');
						}
					}
					elseif($cartPagePosition == "right") {
						$blockLeft = $observerData->getBlock('left');  
						if($blockLeft) {
							$blockLeft->unsetChild('custom_paright');
						}
						$blockContent = $observerData->getBlock('content');  
						if($blockContent) {
							$blockContent->unsetChild('custom_pacontent');
						}
					}
					elseif($cartPagePosition == "none") {
						$blockLeft = $observerData->getBlock('left');  
						if($blockLeft) {
							$blockLeft->unsetChild('custom_paright');
						}
						$blockRight = $observerData->getBlock('right');  
						if($blockRight) {
							$blockRight->unsetChild('custom_paright');
						}
						$blockContent = $observerData->getBlock('content');  
						if($blockContent) {
							$blockContent->unsetChild('custom_pacontent');			
						}
					}				
			 }
				
			}else{ // if module is disabled then unset whole blocks
				$blockLeft = $observerData->getBlock('left');  
				if($blockLeft) {
					$blockLeft->unsetChild('custom_paright');
				}
				$blockRight = $observerData->getBlock('right');  
				if($blockRight) {
					$blockRight->unsetChild('custom_paright');
				}

				if(($currentPageHandle == 'checkout_cart_index') || ($currentPageHandle == 'catalog_product_view') || ($currentPageHandle == 'cms_index_index'))
				{ 
					$observerData->getBlock('head')->removeItem('skin_js', 'js/netsol/orderhistory/jquery-1.10.2.min.js');

				}
			}
			/**if module is enabled end**/
		}
		/**If admin is not logged in end**/
		
	}
	
	/**
	 * @description Event after edit product in admin 
	 * @param		$observer
	 * @return		custom block template with custom form
	 */
    public function convertCustomValues($observer) {
		if(Mage::helper('core')->isModuleEnabled('Netsol_Orderhistory') && $this->getsetting->isEnabled()) 
		{
			$form = $observer->getEvent()->getForm();
			$customValues = $form->getElement('warranty_period');
			if ($customValues) {
				$customValues->setRenderer(
					Mage::app()->getLayout()->createBlock('orderhistory/adminhtml_product_paattribute')
				); //set a custom renderer to your attribute
			}
		}
    }
    
	/**
	 * @description Event fire on adminhtml layout  
	 * @param		$observer
	 * @return		custom layout for our admin module
	 */
	public function addAdminCustomLayoutHandle($observer) {
		$controllerAction = $observer->getEvent()->getAction();
		$layout = $observer->getEvent()->getLayout();
		if ($controllerAction && $layout && $controllerAction instanceof Mage_Adminhtml_System_ConfigController) { // Can be checked in other ways of course
			
			if ($controllerAction->getRequest()->getParam('section') == 'pa_orderhistorysetting') { 
				$layout->getUpdate()->addHandle('orderhistory_adminhtml_system_config_edit');
			}
		}
		return $this;
	}
}

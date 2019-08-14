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
class Netsol_Orderhistory_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Path to store config front-end output is enabled or disabled is stored
	 *
	 * @var  string 
	 */
    const XML_PATH_STATUS = 'pa_orderhistorysetting/paorderhistory/enable';
    
     /**
     * Path to store config of predictive analytics block page option is stored
	 *
	 * @var  string 
	 */
    const XML_PATH_HOME_PAGE_SHOW_IN_POSITION = 'pa_orderhistorysetting/paorderhistory/home_page_position_of_block';
   
    /**
     * Path to store config of predictive analytics block position option for product detail page
	 *
	 * @var  string 
	 */
    const XML_PATH_PRODUCT_DETAIL_SHOW_IN_POSITION = 'pa_orderhistorysetting/paorderhistory/product_detail_position_of_block';
    
     /**
     * Path to store config of predictive analytics block position option for cart page
	 *
	 * @var  string 
	 */
    const XML_PATH_CART_PAGE_SHOW_IN_POSITION = 'pa_orderhistorysetting/paorderhistory/cart_page_position_of_block';
    
    
    /**
     * Path to store config of predictive analytics option is stored
	 *
	 * @var  string 
	 */
    const XML_PATH_MAX_PRODUCT_COUNT = 'pa_orderhistorysetting/paorderhistory/max_product_count';
    
     /**
     * Path to store config of number of recent order for which recommedation will apply
	 *
	 * @var  string 
	 */
    const XML_PATH_MAX_RECENT_ORDER_COUNT = 'pa_orderhistorysetting/paorderhistory/max_recent_order_count';
    
     /**
     * Path to store config of number of recent order for which recommedation will apply
	 *
	 * @var  string 
	 */
    const XML_PATH_WARRANTY_PERIOD_DEFAULT_VALUE = 'pa_orderhistorysetting/paorderhistory/warranty_period_default_value';
    
     /**
     * Path to store config of number of recent order for which recommedation will apply
	 *
	 * @var  string 
	 */
    const XML_PATH_ORDER_HISTORY_PERIOD = 'pa_orderhistorysetting/paorderhistory/order_history_period';
    
     /**
     * Path to store time period to display product on frontend in days
	 *
	 * @var  string 
	 */
    const XML_PATH_TIME_PERIOD_TO_DISPLAY_PRODUCT = 'pa_orderhistorysetting/paorderhistory/time_period_to_display_product';
	
     /**
     * Path to store time period to display product on frontend in days
	 *
	 * @var  string 
	 */
    const XML_PATH_ENABLE_JQUERY = 'pa_orderhistorysetting/paorderhistory/enabled_jquery';
    
     /**
     * Path to store time period to display product on frontend in days
	 *
	 * @var  string 
	 */
    const XML_PATH_HEADING = 'pa_orderhistorysetting/paorderhistory/orderhistory_analytics_heading';
    
     /**
     * Path to display crosssell and upsell product
	 * @var  string 
	 */
    const XML_PATH_CROSSSELL_ENABLE = 'pa_orderhistorysetting/paorderhistory/crosssell_enable';

    /**
     * Path to display crosssell and upsell product
	 * @var  string 
	 */
    const XML_PATH_UPSELL_ENABLE = 'pa_orderhistorysetting/paorderhistory/upsell_enable';
    
     /**
     * Path to email trigger days
	 * @var  string 
	 */
    const XML_PATH_EMAIL_TRIGGER_DAYS = 'pa_orderhistorysetting/paorderhistory/email_trigger_days';
    
   	 /**
	 * enable/disable 
	 *
	 * @var  boolean 
	 */
    protected $isEnabled = null;
    
     /**
     * Predictive Analytics Option
     *
     * @var  array 
     */
    protected $predictiveAnalyticsOption = null;
    
     /**
     * Show Block In Position
     *
     * @var  array 
     */
    protected $homePageShowInPosition = null;
    
     /**
     * Show Block In Position
     *
     * @var  array 
     */
    protected $productDetailShowInPosition = null;
    
     /**
     * Show Block In Position
     *
     * @var  array 
     */
    protected $cartPageShowInPosition = null;
      
     /**
     * Max product to display
     *
     * @var  number 
     */
    protected $maxProductCount = null;
	 
	 /**
     * Max recent order item 
     *
     * @var  number 
     */
    protected $maxRecentOrderCount = null;
    
	 /**
     * Max order period
     *
     * @var  number 
     */
    protected $warrantyPeriodDefaultValue = null;
    
	 /**
     * Max order period
     *
     * @var  number 
     */
    protected $orderHistoryPeriod = null;
    
	 /**
     * Time period to display product on frontend in days
     *
     * @var  number 
     */
    protected $timePeriodToDisplayProduct = null;
    
	 /**
     * Enable Jquery
     *
     * @var  number 
     */
    protected $enableJquery = null;
    
	 /**
     * Heading
     *
     * @var  number 
     */
    protected $heading = null;
    
	 /**
     * Cross sell Status
     *
     * @var  number 
     */
    protected $crossSellEnable = null;
    
	 /**
     * Up sell Status
     *
     * @var  number 
     */
    protected $upSellEnable = null;
    
	 /**
     * email trigger
     *
     * @var  number 
     */
    protected $emailTriggerDays = null;
    

    public function __construct()
    {
        if(($this->isEnabled = $this->_isEnabled())) {
			$this->homePageShowInPosition = $this->_getHomePageShowInPosition();
			$this->productDetailShowInPosition = $this->_getProductDetailShowInPosition();
			$this->cartPageShowInPosition = $this->_getCartPageShowInPosition();
			$this->maxProductCount = $this->_getmaxProductCount();
			$this->maxRecentOrderCount = $this->_getMaxRecentOrderCount();
			$this->warrantyPeriodDefaultValue = $this->_getWarrantyPeriodDefaultValue();
			$this->orderHistoryPeriod = $this->_getOrderHistoryPeriod();
			$this->timePeriodToDisplayProduct = $this->_getTimePeriodToDisplayProduct();
			$this->enableJquery = $this->_getEnableJquery();
			$this->heading = $this->_getHeading();
			$this->crossSellEnable = $this->_getCrossSellEnable();
			$this->upSellEnable = $this->_getUpSellEnable();
			$this->emailTriggerDays = $this->_getEmailTriggerDays();
         }
    }

    /**
     * @description Check whether is enable or not
     *
     * @param		no
     * @return		boolean
     */
    public function isEnabled()
    {
        return (bool) $this->isEnabled;
    }
    
    /**
     * @description Retrieve Predictive Analytics Options
     *
     * @param		no
     * @return		string
     */
    public function getPredictiveAnalyticsOption()
    {
        return $this->predictiveAnalyticsOption;
    }
    
    /**
     * @description Retrieve Predictive Position Options
     *
     * @param		no
     * @return		string
     */
    public function getHomePageShowInPosition()
    {
        return $this->homePageShowInPosition;
    }
    
    /**
     * @description Retrieve Predictive Position Options
     *
     * @param		no
     * @return		string
     */
    public function getProductDetailShowInPosition()
    {
        return $this->productDetailShowInPosition;
    }
    
    /**
     * @description Retrieve Predictive Position Options
     *
     * @param		no
     * @return		string
     */
    public function getCartPageShowInPosition()
    {
        return $this->cartPageShowInPosition;
    }

    /**
     * @description Retrieve max product to display
     *
     * @param		no
     * @return		string
     */
    public function getMaxProductCount()
    {
        return $this->maxProductCount;
    }
    
    /**
     * @description Retrieve max product to display
     *
     * @param		no
     * @return		string
     */
    public function getMaxRecentOrderCount()
    {
        return $this->maxRecentOrderCount;
    }
    
    /**
     * @description Retrieve max product to display
     *
     * @param		no
     * @return		string
     */
    public function getWarrantyPeriodDefaultValue()
    {
        return $this->warrantyPeriodDefaultValue;
    }
    
    /**
     * @description order History product to display
     *
     * @param		no
     * @return		string
     */
    public function getOrderHistoryPeriod()
    {
        return $this->orderHistoryPeriod;
    }
    
    /**
     * @description order History product to display
     *
     * @param		no
     * @return		string
     */
    public function getTimePeriodToDisplayProduct()
    {
        return $this->timePeriodToDisplayProduct;
    }
    
    /**
     * @description Enable Jquery
     *
     * @param		no
     * @return		string
     */
    public function getEnableJquery()
    {
        return $this->enableJquery;
    }
    
    /**
     * @description Heading
     *
     * @param		no
     * @return		string
     */
    public function getHeading()
    {
        return $this->heading;
    }
    /**
     * @description get Cross Up Sell Enable
     *
     * @param		no
     * @return		string
     */
    public function getCrossSellEnable()
    {
        return $this->crossSellEnable;
    }

    /**
     * @description get Up Sell Enable
     *
     * @param		no
     * @return		string
     */
    public function getUpSellEnable()
    {
        return $this->upSellEnable;
    }
    
    /**
     * @description email trigger
     *
     * @param		no
     * @return		string
     */
    public function getEmailTriggerDays()
    {
        return $this->emailTriggerDays;
    }

     /**
     * @description Number Of Product To display weightage for
     * Order History and Seasons
     * 
     * @param		no
     * @return		int
     */
    public function getNoOfDisplayProductWeightage()
    {
		
		if($this->getCrossSellEnable() == 1 && $this->getUpSellEnable() == 1) {
			$this->noOfproducts = ceil($this->getMaxProductCount() / 3 );
		}elseif($this->getCrossSellEnable() == 1 || $this->getUpSellEnable() == 1){
			$this->noOfproducts = ceil($this->getMaxProductCount() / 2 );
		}else{
			$this->noOfproducts = $this->getMaxProductCount();
		}
	
		return $this->noOfproducts;
    }
    
    /**
     * @description retrieve options
     *
     * @param		no
     * @return		string
     */
     
    protected function _isEnabled()
    {
        return $this->_getStoreConfig(self::XML_PATH_STATUS);
    }
    
	protected function _getHomePageShowInPosition()
	{
		return $this->_getStoreConfig(self::XML_PATH_HOME_PAGE_SHOW_IN_POSITION);
	}
	
	protected function _getProductDetailShowInPosition()
	{
		return $this->_getStoreConfig(self::XML_PATH_PRODUCT_DETAIL_SHOW_IN_POSITION);
	}
	
	protected function _getCartPageShowInPosition()
	{
		return $this->_getStoreConfig(self::XML_PATH_CART_PAGE_SHOW_IN_POSITION);
	}
     
	protected function _getMaxProductCount()
	{
		return $this->_getStoreConfig(self::XML_PATH_MAX_PRODUCT_COUNT);
	}
	protected function _getMaxRecentOrderCount()
	{
		return $this->_getStoreConfig(self::XML_PATH_MAX_RECENT_ORDER_COUNT);
	}
	protected function _getWarrantyPeriodDefaultValue()
	{
		return $this->_getStoreConfig(self::XML_PATH_WARRANTY_PERIOD_DEFAULT_VALUE);
	}
	protected function _getOrderHistoryPeriod()
	{
		return $this->_getStoreConfig(self::XML_PATH_ORDER_HISTORY_PERIOD);
	}
	protected function _getTimePeriodToDisplayProduct()
	{
		return $this->_getStoreConfig(self::XML_PATH_TIME_PERIOD_TO_DISPLAY_PRODUCT);
	}
	protected function _getEnableJquery()
	{
		return $this->_getStoreConfig(self::XML_PATH_ENABLE_JQUERY);
	}
	protected function _getHeading()
	{
		return $this->_getStoreConfig(self::XML_PATH_HEADING);
	}
	protected function _getCrossSellEnable()
	{
		return $this->_getStoreConfig(self::XML_PATH_CROSSSELL_ENABLE);
	}
	protected function _getUpSellEnable()
	{
		return $this->_getStoreConfig(self::XML_PATH_UPSELL_ENABLE);
	}
	protected function _getEmailTriggerDays()
	{
		return $this->_getStoreConfig(self::XML_PATH_EMAIL_TRIGGER_DAYS);
	}
	    protected function _getStoreConfig($xmlPath)
    {
        return Mage::getStoreConfig($xmlPath, Mage::app()->getStore()->getId());
    }
    

}
	 

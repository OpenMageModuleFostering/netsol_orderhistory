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
 
class Netsol_Orderhistory_Block_Adminhtml_Product_Paattribute
    extends Mage_Adminhtml_Block_Widget
    implements Varien_Data_Form_Element_Renderer_Interface {

	 /**
	 * initiate instance
	 *
	 * @return Block template  
	 */
    public function __construct()
    { 
        $this->setTemplate('orderhistory/product/paattribute.phtml'); //set a template
    }
    
    /**
	 * @params Form elements
	 *
	 * @return html
	 */
    public function render(Varien_Data_Form_Element_Abstract $element) { 
        $this->setElement($element);
        return $this->toHtml();
    }
    
}

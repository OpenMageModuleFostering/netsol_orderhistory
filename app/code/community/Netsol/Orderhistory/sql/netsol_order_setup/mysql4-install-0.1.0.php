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
$installer = $this;
$installer->startSetup();
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->removeAttribute('catalog_product', 'warranty_period');

$installer->addAttribute('catalog_product', 'warranty_period', array(
  'attribute_set_name' => 'Default',
  'group' => 'Personalytics', 
  'type'              => 'varchar',
  'frontend'          => '',
  'label'             => 'Warranty Period',
  'input'             => 'text',
  'class'             => '',
  'visible'           => true,
  'backend'           => 'orderhistory/paattribute',  //a custom backend model that will handle serialization and deserialization
  'required'          => false,
  'user_defined'      => true,
  'default' => 'Green',
  'searchable'        => false,
  'filterable'        => false,
  'comparable'        => false,
  'visible_on_front'  => false,
  'unique'            => false,
  'is_configurable'=>'1',
  'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
 )); 


$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('netsol_paemail_entries')};
	
	CREATE TABLE IF NOT EXISTS {$this->getTable('netsol_paemail_entries')} (
	`id`int(11) NOT NULL auto_increment,
	`custid` int(11) NOT NULL,
	`pid` int(11) NOT NULL,
	`mailsent` int(11) NOT NULL,
	`created_at` timestamp NULL,
	PRIMARY KEY  (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
"); 

$installer->endSetup();

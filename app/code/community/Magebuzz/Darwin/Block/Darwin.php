<?php
/*
* Copyright (c) 2015 www.magebuzz.com 
*/
class Magebuzz_Darwin_Block_Darwin extends Mage_Core_Block_Template {
	public function _prepareLayout() {
		return parent::_prepareLayout();
  }
    
	public function isActive() { 
		return Mage::helper('darwin')->isActive();
	}
	
	public function getSiteUrl() {
		return Mage::helper('darwin')->getSiteUrl();
	}
}
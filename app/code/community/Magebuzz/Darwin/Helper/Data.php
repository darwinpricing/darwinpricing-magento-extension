<?php
/*
* Copyright (c) 2015 www.magebuzz.com 
*/
class Magebuzz_Darwin_Helper_Data extends Mage_Core_Helper_Abstract {
	protected $_storeId;
	
	public function _construct() {
		parent::_construct();
		$this->_storeId = Mage::app()->getStore()->getId();
	}
	
	public function isActive() {
		return Mage::getStoreConfig('darwin/general/is_active', $this->_storeId);
	}
	
	public function getApiUrl() {
		return Mage::getStoreConfig('darwin/general/api_url', $this->_storeId);
	}
	
	public function getApiUser() {
		return Mage::getStoreConfig('darwin/general/api_user', $this->_storeId);
	}
	
	public function getSecretKey() {
		return Mage::getStoreConfig('darwin/general/secret_key', $this->_storeId);
	}
	
	public function getSiteUrl() {
		$siteUrl = '';
		$siteUrl = $this->getApiUrl() ? $this->getApiUrl() : 'https://api.darwinpricing.com';
		$siteUrl .= '/widget?site-id=' . $this->getApiUser();
		return $siteUrl;
	}
	
	/*
	* call function to post data to API server 
	*/
	public function post($data = null, $type = 'order') {
		$url = $this->getApiUrl() ? $this->getApiUrl() : 'https://api.darwinpricing.com';
		if ($type == 'order') {			
			$url .= '/magento/webhook-order-payment?site-id=' . $this->getApiUser() . '&hash=' . $this->getSecretKey();
    }
		else {
			$url .= '/magento/webhook-refund-create?site-id=' . $this->getApiUser() . '&hash=' . $this->getSecretKey();
		}
		
		Mage::log('url '. $url, null, 'darwinpricing.log');
		try {
			$ch = curl_init();          
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			
	    $response = curl_exec($ch);
			
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
    }
		catch (Exception $e) {
			echo $e->getMessage();
			die('fff');
		}
		Mage::log('response '. print_r(json_decode($response, true), true), null, 'darwinpricing.log');
    return json_decode($response, true);  
  }
}
<?php

class Uplift_DecisionEngine_Helper_Data extends Mage_Core_Helper_Abstract {

    protected $_storeId;

    public function _construct() {
        parent::_construct();
        $this->_storeId = Mage::app()->getStore()->getId();
    }

    public function isActive() {
        return Mage::getStoreConfig('DecisionEngine/general/is_active', $this->_storeId);
    }

    public function getApiUrl() {
        return Mage::getStoreConfig('DecisionEngine/general/api_url', $this->_storeId);
    }

    public function getApiUser() {
        return Mage::getStoreConfig('DecisionEngine/general/api_user', $this->_storeId);
    }

    public function getSecretKey() {
        return Mage::getStoreConfig('DecisionEngine/general/secret_key', $this->_storeId);
    }

    public function getWidgetUrl() {
        if (!$this->getApiUrl()) {
            return '';
        }
        $siteUrl = $this->getApiUrl() . '/widget?site-id=' . $this->getApiUser();
        return $siteUrl;
    }

    public function postOrder($data = null) {
        if (!$this->getApiUrl()) {
            return;
        }
        $queryParameters = array(
            'site-id' => $this->getApiUser(),
            'hash' => $this->getSecretKey(),
        );
        $url = $this->getApiUrl() . '/magento/webhook-order-payment?' . http_build_query($queryParameters);
        $this->_postJson($url, $data);
    }

    public function postRefund($data = null) {
        if (!$this->getApiUrl()) {
            return;
        }
        $queryParameters = array(
            'site-id' => $this->getApiUser(),
            'hash' => $this->getSecretKey(),
        );
        $url = $this->getApiUrl() . '/magento/webhook-refund-create?' . http_build_query($queryParameters);
        $this->_postJson($url, $data);
    }

    protected function _postJson($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_exec($ch);
    }

}

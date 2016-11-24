<?php

class FC_DarwinPricing_Helper_Data extends Mage_Core_Helper_Abstract {

    protected $_storeId;

    public function _construct() {
        parent::_construct();
        $this->_storeId = Mage::app()->getStore()->getId();
    }

    /**
     * @return bool
     */
    public function isActive() {
        return Mage::getStoreConfig('DarwinPricing/general/is_active', $this->_storeId);
    }

    /**
     * @return string
     */
    public function getApiUrl() {
        return Mage::getStoreConfig('DarwinPricing/general/api_url', $this->_storeId);
    }

    /**
     * @return string
     */
    public function getApiUser() {
        return Mage::getStoreConfig('DarwinPricing/general/api_user', $this->_storeId);
    }

    /**
     * @return string
     */
    public function getSecretKey() {
        return Mage::getStoreConfig('DarwinPricing/general/secret_key', $this->_storeId);
    }

    /**
     * @return string
     */
    public function getWidgetUrl() {
        if (!$this->getApiUrl()) {
            return '';
        }
        $siteUrl = $this->getApiUrl() . '/widget?site-id=' . $this->getApiUser();
        return $siteUrl;
    }

    /**
     * @param array $data
     */
    public function postOrder(array $data) {
        if (!$this->getApiUrl()) {
            return;
        }
        $queryParameters = array(
            'site-id' => $this->getApiUser(),
            'hash' => $this->getSecretKey(),
        );
        $url = $this->getApiUrl() . '/magento/webhook-order-payment?' . http_build_query($queryParameters);
        $body = json_encode($data);
        $this->_webhook($url, $body);
    }

    /**
     * @param array $data
     */
    public function postRefund(array $data) {
        if (!$this->getApiUrl()) {
            return;
        }
        $queryParameters = array(
            'site-id' => $this->getApiUser(),
            'hash' => $this->getSecretKey(),
        );
        $url = $this->getApiUrl() . '/magento/webhook-refund-create?' . http_build_query($queryParameters);
        $body = json_encode($data);
        $this->_webhook($url, $body);
    }

    /**
     * @param callable $workload
     */
    protected function _executeOnShutdown(callable $workload) {
        register_shutdown_function(function() use ($workload) {
            if (function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }
            $workload();
        });
    }

    /**
     * @param string $url
     * @param string $body
     */
    protected function _webhook($url, $body) {
        $optionList = array(
            CURLOPT_POST => true,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT_MS => 3000,
            CURLOPT_POSTFIELDS => $body,
        );
        $this->_executeOnShutdown(function() use($optionList) {
            $ch = curl_init();
            curl_setopt_array($ch, $optionList);
            curl_exec($ch);
            curl_close($ch);
        });
    }

}

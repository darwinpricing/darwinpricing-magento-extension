<?php

class FC_DarwinPricing_Block_DarwinPricing extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    /**
     * @return bool
     */
    public function isActive() {
        return Mage::helper('DarwinPricing')->isActive();
    }

    /**
     * @return string
     */
    public function getWidgetUrl() {
        return Mage::helper('DarwinPricing')->getWidgetUrl();
    }

}

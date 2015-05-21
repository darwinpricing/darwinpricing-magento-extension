<?php

class FC_DarwinPricing_Block_DarwinPricing extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function isActive() {
        return Mage::helper('DarwinPricing')->isActive();
    }

    public function getWidgetUrl() {
        return Mage::helper('DarwinPricing')->getWidgetUrl();
    }

}

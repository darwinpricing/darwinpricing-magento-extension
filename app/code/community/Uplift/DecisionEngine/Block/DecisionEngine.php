<?php

class Uplift_DecisionEngine_Block_DecisionEngine extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function isActive() {
        return Mage::helper('DecisionEngine')->isActive();
    }

    public function getWidgetUrl() {
        return Mage::helper('DecisionEngine')->getWidgetUrl();
    }

}

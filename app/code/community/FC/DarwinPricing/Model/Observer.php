<?php

class FC_DarwinPricing_Model_Observer {

    /**
     * @param Varien_Event_Observer $observer
     */
    public function postOrder(Varien_Event_Observer $observer) {
        $darwinPricingHelper = Mage::helper('DarwinPricing');
        if (!$darwinPricingHelper->isActive()) {
            return;
        }
        $invoice = $observer->getEvent()->getInvoice();
        $orderId = $invoice->getOrderId();
        $order = Mage::getModel('sales/order')->load($orderId);
        $orderDetails = array();
        $orderDetails['order_id'] = $orderId;
        $orderDetails['total'] = $order->getGrandTotal();
        $orderDetails['currency'] = $order->getOrderCurrencyCode();
        $orderDetails['coupon_code'] = $order->getCouponCode();
        $orderDetails['shipping_amount'] = $order->getShippingAmount();
        $orderDetails['taxes'] = $order->getTaxAmount();
        $orderDetails['customer'] = array(
            'id' => $order->getCustomerId(),
            'email_address' => $order->getCustomerEmail(),
            'ip_address' => $order->getXForwardedFor() ? $order->getXForwardedFor() : $order->getRemoteIp(),
        );
        $orderDetails['items'] = array();
        foreach ($order->getAllVisibleItems() as $item) {
            $orderDetails['items'][] = array(
                'id' => $item->getProductId(),
                'sku' => $item->getSku(),
                'qty' => $item->getQtyOrdered(),
                'unit_price' => $item->getPrice(),
                'unit_cost' => $item->getBaseCost(),
            );
        }
        $darwinPricingHelper->postOrder($orderDetails);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function postRefund(Varien_Event_Observer $observer) {
        $darwinPricingHelper = Mage::helper('DarwinPricing');
        if (!$darwinPricingHelper->isActive()) {
            return;
        }
        $creditMemo = $observer->getEvent()->getCreditmemo();
        $order = $creditMemo->getOrder();
        $refundDetails = array();
        $refundDetails['refund_id'] = $creditMemo->getId();
        $refundDetails['order_id'] = $order->getId();
        $refundDetails['refunded_amount'] = $creditMemo->getGrandTotal();
        $refundDetails['currency'] = $order->getOrderCurrencyCode();
        $refundDetails['refunded_taxes'] = $creditMemo->getTaxAmount();
        $refundDetails['customer'] = array(
            'id' => $order->getCustomerId(),
            'email_address' => $order->getCustomerEmail(),
            'ip_address' => $order->getXForwardedFor() ? $order->getXForwardedFor() : $order->getRemoteIp(),
        );
        $refundDetails['items'] = array();
        foreach ($creditMemo->getAllItems() as $item) {
            $refundDetails['items'][] = array(
                'id' => $item->getProductId(),
                'sku' => $item->getSku(),
                'qty' => $item->getQty(),
                'unit_price' => $item->getPrice(),
                'unit_cost' => $item->getBaseCost(),
            );
        }
        $darwinPricingHelper->postRefund($refundDetails);
    }

}

<?php
/*
* Copyright (c) 2015 www.magebuzz.com 
*/
class Magebuzz_Darwin_Model_Observer {
	public function postOrderDetail(Varien_Event_Observer $observer) {
		Mage::log('after invoice created', null, 'darwinpricing.log');
		$invoice = $observer->getEvent()->getInvoice();
		$orderId = $invoice->getOrderId();

		$order = Mage::getModel('sales/order')->load($orderId);
		$orderData = array();
		$orderData['order_id'] = $orderId;
		$orderData['total'] = $order->getGrandTotal();
		$orderData['currency'] = $order->getOrderCurrencyCode();
		$orderData['coupon_code'] = $order->getCouponCode();
		
		$items = array(); 
		foreach ($order->getAllVisibleItems() as $item) {
			$items[] = array(
				'id' => $item->getProductId(), 
				'qty' => $item->getQtyOrdered(), 
				'unit_price' => $item->getPrice()
			);
		}
		
		$orderData['items'] = $items; 
		$orderData['shipping_amount'] = $order->getShippingAmount(); 
		$ip_address = $order->getXForwardedFor() ? $order->getXForwardedFor() : $order->getRemoteIp();
		$orderData['customer'] = array(
			'id' => $order->getCustomerId(),
			'email_address' => $order->getCustomerEmail(), 
			'ip_address'	=> $ip_address
		);
		
		$jsonData = json_encode($orderData);
		Mage::log('json data'. $jsonData, null, 'darwinpricing.log');
		
		$result = Mage::helper('darwin')->post($jsonData);
	}
	
	public function refundOrder(Varien_Event_Observer $observer) {
		$creditMemo = $observer->getEvent()->getCreditmemo();
		$payment = $observer->getEvent()->getPayment();
		$order = $creditMemo->getOrder();
		$data = array();
		$data['refund_id'] = $creditMemo->getId();
		$data['order_id'] = $order->getId();	
		$data['refunded_amount'] = $creditMemo->getGrandTotal();	
		$data['currency'] = $order->getOrderCurrencyCode();
		$ip_address = $order->getXForwardedFor() ? $order->getXForwardedFor() : $order->getRemoteIp();
		$data['customer'] = array(
			'id' => $order->getCustomerId(),
			'email_address' => $order->getCustomerEmail(),
			'ip_address'	=> $ip_address
		);
		foreach ($creditMemo->getAllItems() as $item) {
			$items[] = array(
				'id' => $item->getProductId(), 
				'qty' => $item->getQty(), 
				'unit_price' => $item->getPrice()
			);
		}
		$data['items'] = $items; 
		$jsonData = json_encode($data);
		$result = Mage::helper('darwin')->post($jsonData, 'refund');
	}
}
<?php
/*
* Copyright (c) 2015 www.magebuzz.com 
*/
class Magebuzz_Darwin_IndexController extends Mage_Core_Controller_Front_Action {
  public function indexAction() {			
		$orderId = '5';
		$order = Mage::getModel('sales/order')->load($orderId);
		$orderData = array();
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
		$orderData['customer'] = array(
			'id' => $order->getCustomerId(),
			'email_address' => $order->getCustomerEmail(),
			'ip_address'	=> $order->getRemoteIp()
		);
		$jsonData = json_encode($orderData);
		Mage::helper('darwin')->post($jsonData);
		
		die('xxx');
		
  }
	
	public function refundAction() {	
		$creditMemo = Mage::getModel('sales/order_creditmemo')->load(1);
		$order = $creditMemo->getOrder();
		$data = array();
		$data['refund_id'] = $creditMemo->getId();
		$data['order_id'] = $order->getId();	
		$data['refunded_amount'] = $creditMemo->getGrandTotal();	
		$data['currency'] = $order->getOrderCurrencyCode();
		$data['customer'] = array(
			'id' => $order->getCustomerId(),
			'email_address' => $order->getCustomerEmail(),
			'ip_address'	=> $order->getRemoteIp()
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
		echo $jsonData.'<br/>';
		die('aaaa');
	}
}
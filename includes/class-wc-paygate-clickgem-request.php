<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Plugin Name: ClickGem Paygate for Woocommerce
 * Plugin URI: https://www.clickgem.com/
 * Description: Add-on paygate clickgem.com for Woocommerce
 * Version: 1.0.0
 * Author: HaiNN
 * Author URI: http://newwayit.vn/
 * License: GPL2
 */

/**
 * Plugin này xây dựng cơ bản theo URL http://newwayit.vn/
 */
class WC_Gateway_ClickGem_Request {
	
	
	
	protected $gateway;
	
	public $get_transaction;
	
	
	protected $notify_url;
	
	public function __construct( $gateway, $order ) {
		$this->gateway    = $gateway;
		
		$this->notify_url = WC()->api_request_url( 'WC_ClickGemPaygate' );
		$this->get_transaction = $this->get_transaction_args( $order );
		
	}
	
	protected function getaddress( $order ) {
		$array_address =  array(
				'ct_firstname' 	=> $order->billing_first_name,
				'ct_lastname'	=> $order->billing_last_name,
				'ct_address' 	=> $order->billing_address_1,
				'ct_city' 		=> $order->billing_city,
				'ct_postcode' 	=> $order->billing_postcode,
				'ct_country' 	=> $order->billing_country,
				'ct_telephone' 	=> $order->billing_phone,
				'ct_note' 		=> $order->customer_note,
			);
		return wp_json_encode($array_address);
	}
	protected function get_transaction_args( $order ) {		
		return array(
				'invoiceNumber' => $order->get_order_number(),
				'invoiceDate'   => date('m/d/Y',current_time( 'timestamp' )),
				'billTo'      	=> $order->get_billing_email(),
				'typePayments' 	=> $this->gateway->typepayments,
				'typeOrderFee'  => $this->gateway->typeorderfee,
				'typeInvoice'   => $this->gateway->typeinvoice,
				'dueDate'       => $this->gateway->duedate,
				'unit' 			=> get_woocommerce_currency(),
				'type_discount' => get_woocommerce_currency(),
				'OrtherDiscount'=> $order->get_total_discount(),
				'shipping' 		=> $order->get_shipping_total(),
				'note'          => $order->customer_message,
				'terms'       	=> '',
				'website'    	=> $order->get_checkout_order_received_url(),
				'item'    		=> $this->line_items(),
				'address'    	=> $this->getaddress($order),
			);
	}
	public function get_request_url( $transaction, $url = 'https://api.clickgem.com/paygate/') {
		
		include dirname( __FILE__ ) . '/curl.php';
		$setting = $this->gateway;
		$username = $setting->api_username;
		$password = $setting->api_password;
		$url = $setting->api_url;
			
		$api = new curl_paygate($username,$password,$url);
		
		return $api->create_paygate($transaction);
	}
	protected function line_items() {
		$array_item = array();
		$product = WC()->cart->get_cart();		
		foreach($product as $key => $value){			
			$array_item[$key]['itemName'] = $value['data']->get_title();
			$array_item[$key]['itemQuality'] = $value['quantity'];
			$array_item[$key]['itemPrice'] = $value['data']->get_regular_price();
			$itemDiscount = 0;
			if( $value['data']->is_on_sale() ) {
				$itemDiscount = $value['data']->get_regular_price() - $value['data']->get_sale_price();
			}
			$array_item[$key]['itemDiscount'] = $itemDiscount;
			$array_item[$key]['typeItemDiscount'] = get_woocommerce_currency();			$array_item[$key]['description'] = '';
		}
		return wp_json_encode($array_item);
	}
}
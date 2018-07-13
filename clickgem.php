<?php
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
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
add_filter( 'woocommerce_currencies', 'add_my_currency' );

function add_my_currency( $currencies ) {

	 $currencies['LTC'] = __( 'LiteCoin', 'woocommerce' );
	 $currencies['BCH'] = __( 'BitCoin Cash', 'woocommerce' );
	 $currencies['CGM'] = __( 'ClickGem', 'woocommerce' );
	
	 return $currencies;

}

add_filter('woocommerce_currency_symbol', 'add_my_currency_symbol', 10, 2);

function add_my_currency_symbol( $currency_symbol, $currency ) {

	 switch( $currency ) {
		 case 'LTC': $currency_symbol = 'LTC'; break;
		 case 'BCH': $currency_symbol = 'BCH'; break;
		 case 'CGM': $currency_symbol = 'CGM'; break;
	 }

	 return $currency_symbol;

}
add_action ( 'plugins_loaded', 'woocommerce_ClickGemPaygate_init', 0 );

function woocommerce_ClickGemPaygate_init() {
	if (! class_exists ( 'WC_Payment_Gateway' ))
		return;
	class WC_ClickGemPaygate extends WC_Payment_Gateway {
		
		public function __construct() {
			$this->icon 			= 'https://www.clickgem.com/assets/images/logo/logo.png';
			$this->id 				= 'cgmpaygate';
			$this->has_fields 		= false;
			$this->method_title      = __( 'Project Clickgem', 'cgmpaygate' );
			/* translators: %s: Link to WC system status page */
			$this->method_description = __( 'ClickGem Standard redirects customers to ClickGem to enter their payment information.', 'cgmpaygate' );
			
			$this->init_form_fields();
			
			$this->init_settings();
			
			$this->title = $this->settings ['title'];
			$this->description = $this->settings['description'];
			$this->api_username = $this->settings['api_username'];
			$this->api_password = $this->settings['api_password'];
			$this->api_signature = $this->settings['api_signature'];
			$this->typepayments = $this->settings['typepayments'];
			$this->typeorderfee = $this->settings['typeorderfee'];
			$this->typeinvoice = $this->settings['typeinvoice'];
			$this->duedate = $this->settings['duedate'];
			$this->api_url 		= 'https://api.clickgem.com/paygate/';
			if ( ! $this->is_valid_for_use() ) {
				$this->enabled = 'no';
			}
			
			add_action ( 'woocommerce_update_options_payment_gateways_' . $this->id, array (
					$this,
					'process_admin_options' 
			) );
			
			// add_action( 'wc_gateway_stripe_process_payment_error', 'plx_wc_order_failed' );
			add_action ( 'woocommerce_thankyou', function ($order_id) {
				$order = new WC_Order ( $order_id );
				$status = $order->status;
				
				// check payment type is clickgem.com paygate
				if ($order->payment_method == 'cgmpaygate' and $_POST['api_signature'] == $this->api_signature) {
					if($_POST['status'] == 'partial_paid'){
						$status = 'wc-on-hold';
					}elseif($_POST['status'] == 'paid'){
						$status = 'wc-completed';
					}elseif($_POST['status'] == 'cancelled'){
						$status = 'wc-cancelled';
					}elseif($_POST['status'] == 'refunded'){
						$status = 'wc-refunded';
					}
					$order->update_status( $status, __('Thank you for your payment Clickgem PayGate', 'cgmpaygate') );
				}
				
			} );
		}
		public function init_form_fields() {
			$this->form_fields = include dirname( __FILE__ ) . '/includes/settings-cgm.php';
		}
		
		
		


		function process_payment($order_id) {
			include_once dirname( __FILE__ ) . '/includes/class-wc-paygate-clickgem-request.php';
			$order = new WC_Order ( $order_id );
			$clickgem_request = new WC_Gateway_ClickGem_Request( $this, $order );
			$curl = $clickgem_request->get_request_url($clickgem_request->get_transaction);
			
			if(empty($curl->error)){
				return array (
						'result' => 'success',
						'redirect' => $curl->success
				);
			}else{
				wc_add_notice( $curl->error, 'error' );
			}
		}
		/**
		 * Check if this gateway is enabled and available in the user's country.
		 *
		 * @return bool
		 */
		public function is_valid_for_use() {
			return in_array(
				get_woocommerce_currency(),
				apply_filters(
					'woocommerce_clickgem_supported_currencies',
					array( 'USD', 'EUR', 'CGM', 'LTC', 'BTC', 'BCH', 'CGMT' )
				),
				true
			);
		}

		/**
		 * Admin Panel Options.
		 * - Options for bits like 'title' and availability on a country-by-country basis.
		 *
		 * @since 1.0.0
		 */
		public function admin_options() {
			if ( $this->is_valid_for_use() ) {
				parent::admin_options();
			} else {
				?>
				<div class="inline error">
					<p>
						<strong><?php esc_html_e( 'Gateway disabled', 'woocommerce' ); ?></strong>: <?php esc_html_e( 'Clickgem does not support your store currency.', 'woocommerce' ); ?>
					</p>
				</div>
				<?php
			}
		}
	}
	function woocommerce_add_ClickGemPaygate_gateway($methods) {
		$methods [] = 'WC_ClickGemPaygate';
		return $methods;
	}
	
	add_filter ( 'woocommerce_payment_gateways', 'woocommerce_add_ClickGemPaygate_gateway' );
	
	class CGM_Checkout
	{
		//Hàm xây dựng url
		public function runCheckoutUrl($return_url)
		{
			header("Location:".$return_url) ;
		}
	
	}
}


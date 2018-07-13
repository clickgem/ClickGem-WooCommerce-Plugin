<?phpif ( ! defined( 'ABSPATH' ) ) {	exit;}/** * Plugin Name: ClickGem Paygate for Woocommerce * Plugin URI: https://www.clickgem.com/ * Description: Add-on paygate clickgem.com for Woocommerce * Version: 1.0.0 * Author: HaiNN * Author URI: http://newwayit.vn/ * License: GPL2 *//** * Plugin này xây dựng cơ bản theo URL http://newwayit.vn/ */
return array (
		'enabled' => array (
				'title' => __ ( 'Enabled / Disabled', '' ),
				'type' => 'checkbox',
				'label' => __ ( 'enabled/ disabled Clickgem Paygate', 'cgmpaygate' ),
				'default' => 'no' 
		),
		'title' => array (
				'title' => __ ( 'Title', 'cgmpaygate' ),
				'type' => 'text',
				'description' => __ ( 'Payment method name', 'cgmpaygate' ),
				'default' => __ ( 'Clickgem Payment', 'cgmpaygate' ) 
		),
		'description' => array (
				'title' => __ ( 'Description', 'cgmpaygate' ),
				'type' => 'textarea',
				'description' => __ ( 'Description of the payment method (displayed when the customer selects the payment method). HTML can be used.', 'cgmpaygate' ),
				'default' => __ ( 'Clickgem Payment is electronic e-commerce specialized for online payment security.', 'cgmpaygate' ) 
		),
		'api_username'          => array(
			'title'       => __( 'Live API username', 'cgmpaygate' ),
			'type'        => 'text',
			'description' => __( 'Get your API credentials from Clickgem.', 'cgmpaygate' ),
			'default'     => '',
			'desc_tip'    => true,
			'placeholder' => __( 'Optional', 'cgmpaygate' ),
		),
		'api_password'          => array(
			'title'       => __( 'Live API password', 'cgmpaygate' ),
			'type'        => 'password',
			'description' => __( 'Get your API credentials from Clickgem.', 'cgmpaygate' ),
			'default'     => '',
			'desc_tip'    => true,
			'placeholder' => __( 'Optional', 'cgmpaygate' ),
		),
		'api_signature'          => array(
			'title'       => __( 'Live API signature', 'cgmpaygate' ),
			'type'        => 'password',
			'description' => __( 'Get your API credentials from Clickgem.', 'cgmpaygate' ),
			'default'     => '',
			'desc_tip'    => true,
			'placeholder' => __( 'Optional', 'cgmpaygate' ),
		),
		'typepayments'         => array(
			'title'       => __( 'Type payments', 'cgmpaygate' ),
			'type'        => 'select',
			'class'       => 'wc-enhanced-select',
			'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'woocommerce' ),
			'default'     => 'one',
			'desc_tip'    => true,
			'options'     => array(
				'one'          	=> __( 'Single payment', 'cgmpaygate' ),
				'multiple'		=> __( 'Multiple payments', 'cgmpaygate' ),
			),
		),
		'typeorderfee'         => array(
			'title'       => __( 'Type order fee', 'cgmpaygate' ),
			'type'        => 'select',
			'class'       => 'wc-enhanced-select',
			'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'cgmpaygate' ),
			'default'     => 'creator',
			'desc_tip'    => true,
			'options'     => array(
				'creator'          	=> __( 'Seller', 'cgmpaygate' ),
				'customers' 		=> __( 'Buyer', 'cgmpaygate' ),
				'customers_creator' => __( 'Seller and Buyer', 'cgmpaygate' ),
			),
		),
		'typeinvoice'         => array(
			'title'       => __( 'Type invoice', 'cgmpaygate' ),
			'type'        => 'select',
			'class'       => 'wc-enhanced-select',
			'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'cgmpaygate' ),
			'default'     => 'quantity',
			'desc_tip'    => true,
			'options'     => array(
				'quantity'          => __( 'Quantity', 'cgmpaygate' ),
				'hours' 			=> __( 'Hours', 'cgmpaygate' ),
				'amountOnly' 		=> __( 'Amount only', 'cgmpaygate' ),
			),
		),
		'duedate'         => array(
			'title'       => __( 'Due date', 'cgmpaygate' ),
			'type'        => 'select',
			'class'       => 'wc-enhanced-select',
			'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'cgmpaygate' ),
			'default'     => '0',
			'desc_tip'    => true,
			'options'     => array(
				'0'          	=> __( 'No due date', 'cgmpaygate' ),
				'12h' 			=> __( '12 hours', 'cgmpaygate' ),
				'1d' 			=> __( '1 day', 'cgmpaygate' ),
				'3d' 			=> __( '3 day', 'cgmpaygate' ),
				'1w' 			=> __( '1 week', 'cgmpaygate' ),
				'1m' 			=> __( '1 month', 'cgmpaygate' ),
			),
		),
);
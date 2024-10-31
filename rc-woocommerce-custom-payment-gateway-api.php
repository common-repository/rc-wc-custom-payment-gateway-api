<?php
/* @wordpress-plugin
 * Plugin Name: RC WooCommerce Custom Payment Gateway API
 * Description: Allow custom payment gatway for woocommerce and after compelete payment call restful api.
 * Version: 1.0.0
 * WC requires at least: 3.0
 * WC tested up to: 5.4
 * Author: rcodehub107           
 * Text Domain: rc-woocommerce-custom-payment-gateway-api
 * License: GPL-2.0+
 */

$active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
if(wpruby_custom_payment_is_woocommerce_active()){
	add_filter('woocommerce_payment_gateways', 'add_custom_payment_gateway');
	function add_custom_payment_gateway( $gateways ){
		$gateways[] = 'RC_WooCommerce_Custom_Payment_Gateway_API';
		return $gateways; 
	}

	add_action('plugins_loaded', 'init_custom_payment_gateway');
	function init_custom_payment_gateway(){
		require 'class-rc-woocommerce-custom-payment-gateway-api.php';
	}
}


/**
 * @return bool
 */
function wpruby_custom_payment_is_woocommerce_active()
{
	$active_plugins = (array) get_option('active_plugins', array());

	if (is_multisite()) {
		$active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	}

	return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
}
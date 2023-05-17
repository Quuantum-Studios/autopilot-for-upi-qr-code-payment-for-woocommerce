<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://quuantum.com
 * @since      1.0.0
 *
 * @package    Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce/includes
 * @author     Quuantum <contact@quuantum.com>
 */
class Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'auto-order-updater-for-upi-qr-code-payment-for-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

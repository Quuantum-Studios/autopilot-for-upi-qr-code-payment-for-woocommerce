<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://quuantum.com
 * @since             1.0.0
 * @package           Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Auto Order Updater for UPI QR Code Payment Gateway
 * Plugin URI:        https://quuantum.com/auto-order-updater-for-upi-qr-code-payment-for-woocommerce
 * Description:       This is an AI-powered plugin to help you update your orders by verifying payments received through UPI on your woocommerce store.
 * Version:           1.0.0
 * Author:            Quuantum
 * Author URI:        https://quuantum.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       auto-order-updater-for-upi-qr-code-payment-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AUTO_ORDER_UPDATER_FOR_UPI_QR_CODE_PAYMENT_FOR_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-auto-order-updater-for-upi-qr-code-payment-for-woocommerce-activator.php
 */
function activate_auto_order_updater_for_upi_qr_code_payment_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-order-updater-for-upi-qr-code-payment-for-woocommerce-activator.php';
	Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-auto-order-updater-for-upi-qr-code-payment-for-woocommerce-deactivator.php
 */
function deactivate_auto_order_updater_for_upi_qr_code_payment_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-auto-order-updater-for-upi-qr-code-payment-for-woocommerce-deactivator.php';
	Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_auto_order_updater_for_upi_qr_code_payment_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_auto_order_updater_for_upi_qr_code_payment_for_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-auto-order-updater-for-upi-qr-code-payment-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_auto_order_updater_for_upi_qr_code_payment_for_woocommerce() {

	$plugin = new Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce();
	$plugin->run();

}
run_auto_order_updater_for_upi_qr_code_payment_for_woocommerce();

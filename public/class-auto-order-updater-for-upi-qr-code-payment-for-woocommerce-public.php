<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://quuantum.com
 * @since      1.0.0
 *
 * @package    Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce/public
 * @author     Quuantum <contact@quuantum.com>
 */
class Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/auto-order-updater-for-upi-qr-code-payment-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Auto_Order_Updater_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/auto-order-updater-for-upi-qr-code-payment-for-woocommerce-public.js', array( 'jquery'), $this->version, true);

		wp_localize_script(
			$this->plugin_name,
			'qaouData',
			$this->localized_script_vars()
		);
	}


	public function change_upiwc_button_show_interval($interval)
	{
		return 120000;
	}

	public function fn_check_order_status_upi_paid(){
		wp_send_json_success($_POST['payer_vpa']);
	}

	public function localized_script_vars(){
		return array(
			'ajax_url' => admin_url('admin-ajax.php')
		);
	}
}

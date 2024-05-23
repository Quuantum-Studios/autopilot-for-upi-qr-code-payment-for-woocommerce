<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  https://www.quuantum.com
 * @since 1.0.0
 *
 * @package    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce/includes
 * @author     Quuantum <contact@quuantum.com>
 */
class Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string    $version    The current version of the plugin.
     */
    protected $version;

    private $defaults;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct() {
        if ( defined('QAUPIWC_VERSION') ) {
            $this->version = QAUPIWC_VERSION;
        } else {
            $this->version = '1.0.5';
        }
        $this->plugin_name = 'autopilot-for-upi-qr-code-payment-for-woocommerce';

        $this->defaults = (object)array(
            'openai_key'      => 'MyChatGPTKey',
            'instructions'    => 'Scan the QR Code with any UPI apps like BHIM, Paytm, Google Pay, PhonePe or any Banking UPI app to make payment for this order.',
            'confirm_message' => "After successful payment, enter the UPI Reference ID or Transaction Number and your UPI ID (if asked).<br>Click Confirm, only after amount deducted from your account.<br><a href=\"https://hindi.planmoneytax.com/utr-number-in-phonepe-and-google-pay/\" target=\"_blank\">Click here</a> to know how you can get the 12-digit Transaction/UTR/Reference ID for your UPI app.",
        );

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
     * - Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_i18n. Defines internationalization functionality.
     * - Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Admin. Defines all hooks for the admin area.
     * - Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'includes/class-autopilot-for-upi-qr-code-payment-for-woocommerce-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(__DIR__) . 'includes/class-autopilot-for-upi-qr-code-payment-for-woocommerce-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(__DIR__) . 'admin/class-autopilot-for-upi-qr-code-payment-for-woocommerce-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(__DIR__) . 'public/class-autopilot-for-upi-qr-code-payment-for-woocommerce-public.php';

        $this->loader = new Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function set_locale() {

        $plugin_i18n = new Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Admin($this->get_plugin_name(), $this->get_version(), $this->defaults);

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_filter('rest_pre_echo_response', $plugin_admin, 'log_api_requests', 10, 3);
        // $this->loader->add_filter('rest_pre_serve_request', $plugin_admin, 'allow_cors_for_merchant_device_only');
        $this->loader->add_filter('upiwc_capture_payment_order_status', $plugin_admin, 'change_upiwc_capture_payment_order_status', 10, 2);
        $this->loader->add_filter('upiwc_order_total_amount', $plugin_admin, 'change_order_amount', 10, 2);
        $this->loader->add_action('rest_api_init', $plugin_admin, 'update_order_api');

        $plugin_admin->load_admin_views();
        $plugin_admin_views = new Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Admin_Views($this->defaults);
        $this->loader->add_action('admin_menu', $plugin_admin_views, 'plugin_add_options_page');
        $this->loader->add_filter('plugin_action_links_' . QAUPIWC_BASENAME, $plugin_admin_views, 'action_links');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function define_public_hooks() {

        $plugin_public = new Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Public($this->get_plugin_name(), $this->get_version(), $this->defaults);

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('upiwc_button_show_interval', $plugin_public, 'change_upiwc_button_show_interval');
        $this->loader->add_action('wp_ajax_nopriv_check_order_status_upi_paid', $plugin_public, 'fn_check_order_status_upi_paid');
        $this->loader->add_action('wp_ajax_check_order_status_upi_paid', $plugin_public, 'fn_check_order_status_upi_paid');

        $this->loader->add_action('wp_ajax_nopriv_check_qr_scanned', $plugin_public, 'fn_check_qr_scanned');
        $this->loader->add_action('wp_ajax_check_qr_scanned', $plugin_public, 'fn_check_qr_scanned');

        $plugin_public->load_public_views();
        // $plugin_admin_views = new Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Public_Views($this->defaults);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since  1.0.0
     * @return string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since  1.0.0
     * @return Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since  1.0.0
     * @return string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}

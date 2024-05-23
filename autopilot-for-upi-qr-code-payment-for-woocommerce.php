<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://www.quuantum.com
 * @since   1.0.0
 * @package Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Autopilot for UPI QR Code Payment Gateway
 * Plugin URI:        https://www.quuantum.com/autopilot-for-upi-qr-code-payment-for-woocommerce
 * Description:       This is an AI-powered plugin to help you update your orders by verifying payments received through UPI on your woocommerce store.
 * Version:           1.0.5
 * Author:            Quuantum
 * Author URI:        https://www.quuantum.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       autopilot-for-upi-qr-code-payment-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined('WPINC') ) {
    die;
}

/**
 * QAUPIWC class.
 *
 * @class Main class of the plugin.
 */
final class QAUPIWC
{

    /**
     * Plugin version.
     *
     * @var string
     */
    public $version = '1.0.5';

    /**
     * The single instance of the class.
     *
     * @var QAUPIWC
     */
    protected static $instance = null;

    /**
     * Retrieve main QAUPIWC instance.
     *
     * Ensure only one instance is loaded or can be loaded.
     *
     * @see    upiwc()
     * @return QAUPIWC
     */
    public static function get() {
        if ( is_null(self::$instance) && ! (self::$instance instanceof QAUPIWC) ) {
            self::$instance = new QAUPIWC();
            self::$instance->setup();
        }

        return self::$instance;
    }

    /**
     * Instantiate the plugin.
     */
    private function setup() {
        // Define plugin constants.
        $this->define_constants();

        // Instantiate services.
        $this->instantiate();

        // Loaded action.
        do_action('qaupiwc_loaded');
    }

    /**
     * Define the plugin constants.
     */
    private function define_constants() {
        define('QAUPIWC_VERSION', $this->version);
        define('QAUPIWC_FILE', __FILE__);
        define('QAUPIWC_PATH', dirname(QAUPIWC_FILE) . '/');
        define('QAUPIWC_URL', plugins_url('', QAUPIWC_FILE) . '/');
        define('QAUPIWC_BASENAME', plugin_basename(QAUPIWC_FILE));
    }

    /**
     * Instantiate services.
     */
    private function instantiate() {
        register_activation_hook(QAUPIWC_FILE, array( $this, 'activate' ));
        register_deactivation_hook(QAUPIWC_FILE, array( $this, 'deactivate' ));

        // Initialize the action and filter hooks.
        $this->init_actions();
    }

    /**
     * Initialize WordPress action and filter hooks.
     */
    private function init_actions() {
        /**
         * The core plugin class that is used to define internationalization,
         * admin-specific hooks, and public-facing site hooks.
         */
        require_once QAUPIWC_PATH . 'includes/class-autopilot-for-upi-qr-code-payment-for-woocommerce.php';
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        if ( is_plugin_active('upi-qr-code-payment-for-woocommerce/upi-qr-code-payment-for-woocommerce.php') ) {
            $plugin = new Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce();
            $plugin->run();
        } else {
            add_action('admin_notices', array( $this, 'install_or_activate_main_plugin' ));
        }
    }

    // Activation
    public function activate() {
        require_once QAUPIWC_PATH . 'includes/class-autopilot-for-upi-qr-code-payment-for-woocommerce-activator.php';
        Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Activator::activate();
    }

    // Deactivation
    public function deactivate() {
        require_once QAUPIWC_PATH . 'includes/class-autopilot-for-upi-qr-code-payment-for-woocommerce-deactivator.php';
        Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Deactivator::deactivate();
    }


    /**
     * Install or activate the main plugin
     */
    public function install_or_activate_main_plugin() {
        $plugin_slug = 'upi-qr-code-payment-for-woocommerce/upi-qr-code-payment-for-woocommerce.php';
        $install_plugin_slug = 'upi-qr-code-payment-for-woocommerce';

        if ( current_user_can('install_plugins') ) {
            $install_url = wp_nonce_url(admin_url('update.php?action=install-plugin&plugin=' . $install_plugin_slug), 'install-plugin_' . $install_plugin_slug);
            $activate_url = wp_nonce_url(admin_url('plugins.php?action=activate&plugin=' . $plugin_slug), 'activate-plugin_' . $plugin_slug);

            if ( is_plugin_inactive($plugin_slug) ) {
                if ( ! $this->is_plugin_installed($plugin_slug) ) {
                    echo '<div class="notice notice-info"><p>The UPI QR Code Payment Gateway for WooCommerce plugin is required for Autopilot addon to work properly. <a href="' . esc_attr($install_url) . '">Click here to install it</a>.</p></div>';
                } else {
                    echo '<div class="notice notice-info"><p>The UPI QR Code Payment Gateway for WooCommerce plugin is required for Autopilot addon to work properly. <a href="' . esc_attr($activate_url) . '">Click here to activate it</a>.</p></div>';
                }
            } else {
                echo '<div class="notice notice-info"><p>The UPI QR Code Payment Gateway for WooCommerce plugin is required for Autopilot addon to work properly. <a href="' . esc_attr($install_url) . '">Click here to install it</a>.</p></div>';
            }
        }
    }

    /**
     * Check if a plugin is installed
     */
    private function is_plugin_installed( $plugin_slug ) {
        $plugins = get_plugins();
        return isset($plugins[ $plugin_slug ]);
    }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function qaupiwc() {
    return QAUPIWC::get();
}
qaupiwc();

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link  https://www.quuantum.com
 * @since 1.0.0
 *
 * @package    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce/public
 * @author     Quuantum <contact@quuantum.com>
 */
class Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Public
{

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $version    The current version of this plugin.
     */
    private $version;
    private $defaults;
    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     */
    public function __construct( $plugin_name, $version, $defaults ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->defaults = $defaults;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/autopilot-for-upi-qr-code-payment-for-woocommerce-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/autopilot-for-upi-qr-code-payment-for-woocommerce-public.js', array( 'jquery' ), $this->version, true);

        wp_localize_script(
            $this->plugin_name,
            'qaupiwcData',
            $this->localized_script_vars()
        );
    }

    public function load_public_views() {
        include plugin_dir_path(__FILE__) . '/partials/autopilot-for-upi-qr-code-payment-for-woocommerce-public-display.php';
    }

    public function change_upiwc_button_show_interval( $interval ) {
        return 10 * 60 * 1000;
    }

    public function fn_check_qr_scanned() {
        $nonce = sanitize_text_field(wp_unslash($_POST['nonce'] ?? ''));
        if ( ! wp_verify_nonce($nonce, 'qaupiwc_nonce') ) {
            $this->outputJsonResult('invalid_format', 'Invalid nonce');
        }

        $amount = sanitize_text_field(wp_unslash($_POST['amount'] ?? ''));
        if ( empty($amount) || ! floatval($amount) ) {
            $this->outputJsonResult('invalid_format', 'amount not provided');
        }

        $amount = floatval($amount);

        global $wpdb;
        $table_name = $wpdb->prefix . 'qaupiwc_transactions';
        $current_time = current_time('Y-m-d H:i:s');
        $two_hours_ago = gmdate('Y-m-d H:i:s', strtotime('-1 hours', strtotime($current_time)));

        $row_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `%1s` WHERE amount = CAST(%f AS FLOAT) AND txn_time >= CAST(%s AS datetime)", $table_name, $amount, $two_hours_ago));
        if ( $row_count > 0 ) {
            $this->outputJsonResult('scan_success', 'Payment is done', true);
        }

        $error_message = '';
        if ( $row_count === null ) {
            $error_message = $wpdb->last_error;
        }
        $this->outputJsonResult('scan_pending', "User not yet payed; AMOUNT: $amount, DATE: $two_hours_ago, ERROR: $error_message");
    }

    public function fn_check_order_status_upi_paid() {
        $nonce = sanitize_text_field(wp_unslash($_POST['nonce'] ?? ''));
        if ( ! wp_verify_nonce($nonce, 'qaupiwc_nonce') ) {
            $this->outputJsonResult('invalid_format', 'Invalid nonce');
        }

        $order_id = sanitize_text_field(wp_unslash($_POST['order_id'] ?? ''));
        $transaction_id = sanitize_text_field(wp_unslash($_POST['txn_id'] ?? ''));
        if ( empty($order_id) || ! (int)$order_id ) {
            $this->outputJsonResult('invalid_format', 'Order id not provided');
        }
        if ( empty($transaction_id) ) {
            $this->outputJsonResult('invalid_format', 'Txn ID not provided');
        }

        $order_id = (int)$order_id;
        $orders = wc_get_orders(
            array(
				'meta_key'     => '_transaction_id',
				'meta_value'   => $transaction_id,
				'meta_compare' => '=',
				'numberposts'  => -1,
				'exclude'      => array( $order_id ),
            )
        );
        if ( count($orders) > 0 ) {
            $this->outputJsonResult('invalid_format', 'Provided transaction id already assigned to another order');
        }

        $order = wc_get_order($order_id);
        if ( ! $order || ! is_a($order, 'WC_Order') ) {
            $this->outputJsonResult('not_found', 'Order with this id not found');
        }

        $o_transaction_id = get_post_meta($order->get_id(), '_transaction_id', true);
        $o_amount = $order->get_total();
        global $wpdb;
        $table_name = $wpdb->prefix . 'qaupiwc_transactions';
        $row_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `%1s` WHERE txn_id = %d", $table_name, $transaction_id));

        if ( $row_count > 0 ) {
            $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM `%1s` WHERE txn_id = %d", $table_name, $transaction_id));
            $transaction = $rows[0];
            if ( ! empty($transaction->order_id) ) {
                if ( $order_id === (int)$transaction->order_id ) {
                    if ( empty($o_transaction_id) || $o_transaction_id !== $transaction_id ) {
                        update_post_meta($order->get_id(), '_transaction_id', $transaction_id);
                    }

                    if ( $order->get_status() !== 'completed' ) {
                        $order->update_status('completed');
                        $order->add_order_note('Order verified and completed by Autopilot.', false);
                        $order->save();
                    }
                    $this->outputJsonResult('payment_success', 'Payment is done', true);
                }
                $this->outputJsonResult('invalid_format', 'Order id not associated with the given txn id');
            }

            if ( ($o_amount - 1) < floatval($transaction->amount) && floatval($transaction->amount) < ($o_amount + 1) ) {
                if ( empty($o_transaction_id) || $o_transaction_id !== $transaction_id ) {
                    update_post_meta($order->get_id(), '_transaction_id', $transaction_id);
                }
                $wpdb->update($table_name, array( 'order_id' => $order_id ), array( 'txn_id' => $transaction_id ));
                if ( $order->get_status() !== 'completed' ) {
                    $order->update_status('completed');
                    $order->add_order_note('Order verified and completed by Autopilot.', false);
                    $order->save();
                }
                $this->outputJsonResult('payment_success', 'Payment is done', true);
            }

            $this->outputJsonResult('invalid_order', "Recieved transaction amount is not near to the order amount.\nOrder amount:$o_amount, Txn amount: " . $transaction->amount);
        }

        if ( empty($o_transaction_id) || $o_transaction_id !== $transaction_id ) {
            update_post_meta($order->get_id(), '_transaction_id', $transaction_id);
        }
        $this->outputJsonResult('order_pending', 'Order is still pending');
    }

    public function outputJsonResult( $status, $msg, $isSuccess = false ) {
        $result['status'] = $status;
        $result['description'] = $msg;
        if ( ! $isSuccess ) {
            wp_send_json_error($result);
        } else {
            wp_send_json_success($result);
        }
        wp_die();
    }

    public function localized_script_vars() {
        $nonce = wp_create_nonce('qaupiwc_nonce');
        return array(
			'nonce'           => $nonce,
			'ajax_url'        => admin_url('admin-ajax.php'),
			'instructions'    => get_option('qaupiwc_popup_instructions', $this->defaults->instructions),
			'confirm_message' => get_option('qaupiwc_popup_confirm_message', $this->defaults->confirm_message),
        );
    }
}

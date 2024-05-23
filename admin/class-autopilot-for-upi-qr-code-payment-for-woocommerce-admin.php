<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://www.quuantum.com
 * @since 1.0.0
 *
 * @package    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce/admin
 * @author     Quuantum <contact@quuantum.com>
 */
class Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Admin
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
     * @param string $plugin_name The name of this plugin.
     * @param string $version     The version of this plugin.
     */
    public function __construct( $plugin_name, $version, $defaults ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->defaults = $defaults;
    }

    /**
     * Register the stylesheets for the admin area.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/autopilot-for-upi-qr-code-payment-for-woocommerce-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/autopilot-for-upi-qr-code-payment-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false);
    }

    public function load_admin_views() {
        include plugin_dir_path(__FILE__) . '/partials/autopilot-for-upi-qr-code-payment-for-woocommerce-admin-display.php';
    }

    public function allow_cors_for_merchant_device_only( $value ) {
        $user_agent = sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'] ?? ''));
        if ( in_array($user_agent, array( 'QAUPIWC_USER_DEVICE' )) !== false ) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
        }

        return $value;
    }

    public function change_upiwc_capture_payment_order_status( $order_status, $order ) {
        if ( $order->get_status() === 'completed' ) {
            return 'completed';
        }

        return $order_status;
    }

    public function log_api_requests( $result, $handler, $request ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'qaupiwc_api_logs';

        $endpoint = $request->get_route();
        if ( strpos($endpoint, 'qaupiwc/v1/update-order') === false ) {
            return $result;
        }

        $method = $request->get_method();
        $headers = $request->get_headers();
        unset($headers['authorization']);
        unset($headers['Authorization']);
        $body = $request->get_body_params();

        if ( empty($body) ) {
            $payload = file_get_contents('php://input');
            $body = json_decode($payload, true);
            $body = array_map('sanitize_text_field', $body);
        }

        $data = array(
			'endpoint'      => $endpoint,
			'method'        => $method,
            'headers'       => wp_json_encode($headers, JSON_PRETTY_PRINT),
            'body'          => wp_json_encode($body, JSON_PRETTY_PRINT),
            'response_body' => wp_json_encode($result, JSON_PRETTY_PRINT),
			'logged_at'     => current_time('mysql'),
        );

        $result['log_status'] = $wpdb->insert($table_name, $data);

        return $result;
    }

    public function update_order_api() {
        register_rest_route(
            'qaupiwc/v1', '/update-order/', array(
				'methods'             => 'POST',
				'permission_callback' => function ( $request ) {
					$headers = $request->get_headers();
					if ( isset($headers['Authorization']) || isset($headers['authorization']) ) {
						$authHeader = $headers['Authorization'] ?? $headers['authorization'];
						$token = explode(' ', $authHeader[0]);
						if ( isset($token[1]) ) {
							$key = base64_decode($token[1]);
							$data = explode(':', $key);
							if ( empty($data) || count($data) !== 2 ) {
									return false;
							}

							$password = $data[1];
							$username = $data[0];
							$user = wp_authenticate_application_password(null, $username, $password);
							if ( ! is_wp_error($user) ) {
									return true;
							}
						}
					}
					return false;
				},
				'callback'            => array( $this, 'update_order' ),
            )
        );
    }

    public function txn_messages_transient_manager( string $message ) {
        $json_data = get_transient('qaupiwc_txn_messages_parsed_data');

        if ( false !== $json_data ) {
            $json_data = json_decode($json_data, true);

            $msg_key = md5($message);
            if ( isset($json_data[ $msg_key ]) ) {
                return $json_data[ $msg_key ];
            }

            $parsed_data = $this->extract_msg_data($message);
            $json_data[ $msg_key ] = $parsed_data;
            $json_data = wp_json_encode($json_data);
            set_transient('qaupiwc_txn_messages_parsed_data', $json_data, 2592000);
            return $parsed_data;
        }

        $msg_key = md5($message);
        $parsed_data = $this->extract_msg_data($message);
        $json_data = array( $msg_key => $parsed_data );
        $json_data = wp_json_encode($json_data);
        set_transient('qaupiwc_txn_messages_parsed_data', $json_data, 2592000);
        return $parsed_data;
    }

    public function update_order( $request ) {
        $msg = $request->get_param('string_data');
        if ( empty($msg) ) {
            $payload = file_get_contents('php://input');
            $decoded_payload = json_decode($payload, true);
            $decoded_payload = array_map('sanitize_text_field', $decoded_payload);
            $msg = $decoded_payload['string_data'];
        }

        if ( strpos($msg, 'received from') === false ) {
            return new WP_REST_Response(array( 'error' => 'This is not a transactional message to recieve money' ), 400);
        }

        $data = $this->txn_messages_transient_manager($msg);

        if ( isset($data['error']) ) {
            return new WP_REST_Response($data, 503);
            wp_die();
        }

        if ( ! is_array($data) ) {
            return new WP_REST_Response(array( $data ), 503);
            wp_die();
        }

        $amount = $data['parsed_data'][0]['amount'] ?? '';
        $upi_transaction_id = $data['parsed_data'][0]['ref_id'] ?? '';

        if ( empty($amount) ) {
            return new WP_REST_Response(array(
				'error'  => 'amount not found',
				'parsed' => $data,
			), 503);
            wp_die();
        }

        if ( empty($upi_transaction_id) ) {
            return new WP_REST_Response(array(
				'error'  => 'upi_transaction_id not found',
				'parsed' => $data,
			), 503);
            wp_die();
        }

        $amount = floatval($amount);

        // ==========
        global $wpdb;
        $table_name = $wpdb->prefix . 'qaupiwc_transactions';
        $row_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `%1s` WHERE txn_id = %d", $table_name, $upi_transaction_id));

        if ( $row_count > 0 ) {
            return new WP_REST_Response(array( 'error' => 'Txn already updated' ), 503);
            wp_die();
        }
        // =============
        
        $args = array(
			'status'       => 'pending',
			'meta_key'     => '_transaction_id',
			'meta_value'   => $upi_transaction_id,
			'meta_compare' => '=',
			'numberposts'  => -1,
			'post_type'    => 'shop_order',
			'orderby'      => 'date',
			'order'        => 'DESC',
			'meta_query'   => array(
				array(
					'key'     => '_order_total',
					'value'   => array( $amount - 1, $amount + 1 ),
					'type'    => 'DECIMAL',
					'compare' => 'BETWEEN',
				),
			),
        );
        $orders = wc_get_orders($args);

        // $transaction_id = $orders[0]->get_meta('_transaction_upi_id');
        $order_id = '';
        if ( ! empty($orders) && count($orders) > 0 ) {
            $order_id = $orders[0]->get_id();
            $orders[0]->update_status('completed');
            $orders[0]->save();
        }

        $data = array(
			'txn_id'        => $upi_transaction_id,
			'order_id'      => $order_id,
			'amount'        => $amount,
            'parsed_output' => wp_json_encode($data['parsed_data'], JSON_PRETTY_PRINT),
			'txn_time'      => current_time('mysql'),
        );
        $wpdb->insert($table_name, $data);

        return new WP_REST_Response(array( "status" => "Txn updated" ), 200);
        wp_die();
    }

    private function extract_msg_data( $request ) {
        $data = $this->parse_msg_using_gpt($request);

        if ( $data['error'] || empty($data) ) {
            $error = $data['error'];
            $data = $this->parse_msg_using_regex($request);
        }

        return array(
			'parsed_data' => $data,
			'gpt_error'   => $error,
		);
    }

    public function parse_msg_using_gpt( $request ) {
        $apiKey = get_option('qaupiwc_openai_key');
        if ( ! $apiKey || empty($apiKey) ) {
            $apiKey = 'myRandomKey';
        }

        if ( empty($apiKey) ) {
            return array( 'error' => 'No OpenAI API key provided' );
        }

        $stringData = "\"{$request}\"
    Output in minified Json format only- {amount:float,ref_id:int}";

        $fields = array(
			"model"       => "text-davinci-003",
			"prompt"      => $stringData,
			"max_tokens"  => 90,
			"temperature" => 1.0,
        );

        $response = wp_safe_remote_post(
            'https://api.openai.com/v1/completions',
            array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $apiKey,
					'Content-Type'  => 'application/json',
				),
                'body'    => wp_json_encode($fields),
            )
        );

        if ( is_wp_error($response) ) {
            return array( 'error' => $response->get_error_message() );
        }

        $data = json_decode(wp_remote_retrieve_body($response));
        if ( ! isset($data->choices[0]->text) ) {
            return array( 'error' => $data );
        }

        $parsedData = $data->choices[0]->text;
        preg_match_all('~\{(?:[^{}]|(?R))*\}~', $parsedData, $matches);

        if ( empty($matches) ) {
            return array( 'error' => "No relevant data found in the msg" );
        }

        $data = array_map(
            function ( $val ) {
                return json_decode($val, true);
            },
            ...$matches
        );

        if ( empty($data) ) {
            return array( 'error' => "No json found in the parsed msg" );
        }

        return $data;
    }

    public function parse_msg_using_regex( $request ) {
        $amountRegex = '/Rs\.([\d\.]+)/';
        preg_match($amountRegex, $request, $amountMatches);
        $amount = floatval($amountMatches[1]);

        $refIdRegex = '/Ref:\s*(\d+)/';
        preg_match($refIdRegex, $request, $refIdMatches);
        $refId = intval($refIdMatches[1]);

        if ( empty($amount) || empty($refId) ) {
            return array(
                'error'               => 'missing data in parsed string',
                'partial_parsed_data' => array(
                    'amount' => $amount,
                    'ref_id' => $refId,
                ),
                'req_string'          => $request,
            );
        }

        return array(
            array(
                'amount' => $amount,
                'ref_id' => $refId,
            ),
        );
    }

    public function change_order_amount( $total, $order ) {
        $random = wp_rand(0, 100) / 100;
        return $total + number_format($random, 2);
    }
}

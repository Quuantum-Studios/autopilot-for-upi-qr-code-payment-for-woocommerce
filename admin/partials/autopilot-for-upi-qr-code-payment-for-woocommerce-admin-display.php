<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://www.quuantum.com
 * @since 1.0.0
 *
 * @package    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce/admin/partials
 */

class Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Admin_Views
{
    private $defaults;
    public function __construct( $defaults ) {
        $this->defaults = $defaults;
    }

    public function plugin_options_page() {
        $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'transactions';
        echo '<div class="wrap">
            <h2>Options</h2>
            <h2 class="nav-tab-wrapper">
                <a href="?page=AutopilotForUpiwc&tab=instructions" class="nav-tab ' . ($active_tab === 'instructions' ? 'nav-tab-active' : '') . '">Instructions</a>
                <a href="?page=AutopilotForUpiwc&tab=settings" class="nav-tab ' . ($active_tab === 'settings' ? 'nav-tab-active' : '') . '">Settings</a>
                <a href="?page=AutopilotForUpiwc&tab=transactions" class="nav-tab ' . ($active_tab === 'transactions' ? 'nav-tab-active' : '') . '">Transactions</a>
                <a href="?page=AutopilotForUpiwc&tab=api-logs" class="nav-tab ' . ($active_tab === 'api-logs' ? 'nav-tab-active' : '') . '">API Logs</a>
            </h2>';

        if ( $active_tab === 'api-logs' ) {
            $this->plugin_logs_page();
        } elseif ( $active_tab === 'transactions' ) {
            $this->plugin_transactions_page();
        } elseif ( $active_tab === 'settings' ) {
            $this->wph_settings_content();
        } elseif ( $active_tab === 'instructions' ) {
            $this->wph_instructions_content();
        }

        echo '</div>';
    }

    public function plugin_add_options_page() {
        add_options_page('Autopilot for UPIWC', 'Autopilot for UPIWC', 'manage_options', 'AutopilotForUpiwc', array( &$this, 'plugin_options_page' ));
        add_action('admin_init', array( $this, 'wph_setup_sections' ));
        add_action('admin_init', array( $this, 'wph_setup_fields' ));
    }

    // ================

    public function wph_instructions_content() {
        $token = "<YOUR_TOKEN_HERE>";
        $token_already_exists = false;

        if ( isset($_POST['generate_new_code']) && ! check_admin_referer('generate_new_code_' . get_current_user_id()) ) {
            echo '<h2>Invalid request</h2>';
            return;
        }

        if ( isset($_POST['generate_new_code']) && $_POST['generate_new_code'] === 'true' ) {

            $current_user = wp_get_current_user();
            $username = $current_user->user_login;

            $app_exists = WP_Application_Passwords::application_name_exists_for_user($current_user->ID, 'AutopilotForUpiwc');
            if ( ! $app_exists ) {
                $new_app_password = WP_Application_Passwords::create_new_application_password(
                    $current_user->ID,
                    array(
                        'name' => 'AutopilotForUpiwc',
                    )
                );
                $appPassword = $new_app_password[0];

                $token = base64_encode($username . ':' . $appPassword);
            } else {
                $token_already_exists = true;
            }
        }

        $headers = array(
            'Authorization' => 'Basic ' . $token,
        );

        $current_url = home_url('/');
        $endpoint = '/wp-json/qaupiwc/v1/update-order/';

        $webhook_url = trailingslashit($current_url) . ltrim($endpoint, '/');
        $header_code = wp_json_encode($headers, JSON_PRETTY_PRINT);
        $body_code = wp_json_encode(json_decode('{"string_data":"%text%"}', true), JSON_PRETTY_PRINT);
?>

        <h2>Instructions</h2>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <td>
                        <b style="color: red;">Important Notice:</b> This plugin functionality will currently only work for UPI ids registered on paytm app because it's gives direct option to turn on the SMS alerts for payments recieved.
                    </td>
                </tr>
                <tr>
                    <th scope="row">Before moving forward, Please complete the following:</th>
                </tr>
                <tr>
                    <td>
                        <b> Enable SMS alerts in paytm app for payments recieved</b><br>
                        1. Login to your paytm app.<br>
                        2. Go to Profile Settings > Manage Notifications<br>
                        3. Turn on 'Wallet and Bank SMS Subscription'<br>

                    </td>
                </tr>
                <tr>
                    <td>
                        Please save the OpenAI API key in settings section:<br>
                        Current status: <?php echo empty(get_option('qaupiwc_openai_key')) ? '<b style="color: red">Not provided</b>' : '<b style="color: green">Active</b>' ?></b>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Steps to follow to setup the app on your phone:</th>
                </tr>
                <tr>
                    <td>
                        <a href="https://github.com/toppersdesk/autopilot-android-app/releases" target="_blank">Download</a> and install this android app to forward txn related messages on your website. Code for this app is open source.
                        <p>Know more about this app <a target="_blank" href="https://github.com/toppersdesk/autopilot-android-app">here</a></p>
                    </td>
                </tr>
                <tr>
                    <th>Main Steps:</th>
                </tr>
                <tr>
                    <th scope="row">Step 1:</th>
                </tr>
                <tr>
                    <td scope="row">
                        <?php
                        if ( isset($_POST['generate_new_code']) && $_POST['generate_new_code'] === 'true' ) {
                            if ( $token_already_exists ) {
                                echo '<p style="color: red;">An application password with name AutopilotForUpiwc already exists. <p>Please go into profile settings and revoke it first.';

                                $user_id = get_current_user_id();
                                $profile_url = get_edit_profile_url($user_id);
                                $application_passwords_url = $profile_url . '#application-passwords-section';
                        ?>
                                <a href="<?php echo esc_url($application_passwords_url); ?>" target="_blank">Go to Application Passwords</a>

                                <br>
                                Once you deleted the existing token from profile, Click below:
                                <br><br>
                                <form method="POST" action="">
                                    <input name="generate_new_code" id="generate_new_code" type="hidden" value="true">
                                    <?php wp_nonce_field('generate_new_code_' . get_current_user_id()); ?>
                                    <button type="submit">
                                        Retry Generate new token
                                    </button>
                                </form>
                            <?php
                            } else {
                                echo '<p style="color:green">Success!!</p>
                                A new token has been generated and added in the below code. Please copy the token as well. It will not be shown again.<br><br>Generated Token:';
                                echo '<pre>' . wp_kses(htmlspecialchars($token), array( 'br' => array() )) . '</pre>';
                            }
                        } else { ?>
                            <form method="POST" action="">
                                <input name="generate_new_code" id="generate_new_code" type="hidden" value="true">
                                <?php wp_nonce_field('generate_new_code_' . get_current_user_id()); ?>
                                <button type="submit">
                                    Generate new token
                                </button>
                            </form>
                        <?php
                        } ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Step 2:</th>
                </tr>
                <tr>
                    <td>
                        <p>Below are the details to setup the app so that the messages you recieve on the phone related to amount you recieved on the upi are then forwarded to your website webhook.<br> Don't worry, your messages data will be used and saved on your website only and you can delete it anytime.</p>
                    </td>
                </tr>

                <tr>
                    <td class="wrap">
                        <h2 class="nav-tab-wrapper instructions">
                            <a href="?page=AutopilotForUpiwc&amp;tab=instructions#auto" class="nav-tab nav-tab-active">Auto Login</a>
                            <a href="?page=AutopilotForUpiwc&amp;tab=instructions#manual" class="nav-tab">Manual</a>
                        </h2>
                        <section class="instructions">
                            <p>Just scan the QR code on phone and open the link in Autopilot app.<br>
                                The app will automatically setup the config required.</p>
                            <?php
                            if ( isset($_POST['generate_new_code']) && $_POST['generate_new_code'] === 'true' && ! str_contains($header_code, "YOUR_TOKEN_HERE") ) {
                                require_once QAUPIWC_PATH . 'libs/qrcode.php';

                                $login_url = "qaupiwc://import?url=" . rawurlencode($webhook_url) . "&headers=" . rawurlencode($header_code) . "&body=" . rawurlencode($body_code) . "&sender=" . rawurlencode('.*-PAYTMB');
                                $generator = new QRCode($login_url, array(
                                    'w' => 200,
                                    'h' => 200,
                                ));
                                $image = $generator->render_image();
                                ob_start();
                                imagepng($image);
                                imagedestroy($image);
                                $rawImageBytes = ob_get_clean();

                                echo '<img src="data:image/png;base64,' . esc_attr(base64_encode($rawImageBytes)) . '" />';
                            } else {
                                echo '<h3>Please Generate new token first to get the QR code</h3>';
                            }
                            ?>
                        </section>
                        <section class="instructions">
                            <table>
                                <tr>
                                    <td>
                                        <?php
                                        if ( ! isset($_GET['source']) || $_GET['source'] !== 'email' ) { ?>
                                            <b>Optional- To directly copy paste the below details in your phone, you can email this page link on your email. Then open it on your phone.</b>
                                            <br>
                                            <br>
                                        <?php
                                        }
                                        if ( isset($_POST['email_instructions']) && $_POST['email_instructions'] === 'true' ) {
                                            $admin_email = get_option('admin_email');
                                            $subject = 'Autopilot Setup Instructions';
                                            $body = 'Hi admin name,<br><br>Here are the instructions to setup and automate your payments received by customers on your WooCommerce store using UPI QR code payment gateway:<br><br><a href="' . admin_url('options-general.php?page=AutopilotForUpiwc&tab=instructions&source=email') . '">Click here</a> to view the instructions.';
                                            $headers = array( 'Content-Type: text/html; charset=UTF-8' );
                                            $result = wp_mail($admin_email, $subject, $body, $headers);

                                            if ( $result ) {
                                                echo '<p style="color:green">Email sent successfully to the admin.</p>';
                                            } else {
                                                echo '<p style="color:red">Failed to send email.</p>';
                                            }
                                        } elseif ( ! isset($_GET['source']) || $_GET['source'] !== 'email' ) { ?>
                                            <form method="POST" action="">
                                                <input name="email_instructions" id="email_instructions" type="hidden" value="true">
                                                <button type="submit">
                                                    Email instructions
                                                </button>
                                            </form>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4>Webhook url</h4>
                                        <pre><?php echo esc_url($webhook_url) ?></pre>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4>Headers to send</h4>
                                        <pre><?php echo wp_kses(htmlspecialchars($header_code), array( 'br' => array() )) ?></pre>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4>Request body</h4>
                                        <pre><?php echo wp_kses($body_code, array( 'br' => array() )) ?></pre>
                                    </td>
                                </tr>
                            </table>
                        </section>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php
    }

    // ================

    private function display_api_logs_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'qaupiwc_api_logs';

        $logs = $wpdb->get_results($wpdb->prepare("SELECT * FROM `%1s` ORDER BY logged_at DESC", $table_name));

        if ( ! empty($logs) ) {
            echo '<table class="widefat">
					<thead>
						<tr>
							<th>ID</th>
							<th>Endpoint</th>
							<th>Method</th>
							<th>Headers</th>
							<th>Body</th>
							<th>Response Body</th>
							<th>Logged At</th>
						</tr>
					</thead>
					<tbody>';

            foreach ( $logs as $log ) {
                echo '<tr>
						<td>' . esc_html($log->id) . '</td>
						<td>' . esc_html($log->endpoint) . '</td>
						<td>' . esc_html($log->method) . '</td>
						<td><pre style="max-height: 25vh;overflow-y:auto;">' . esc_html($log->headers) . '</pre></td>
						<td><pre>' . esc_html($log->body) . '</pre></td>
						<td>' . esc_html($log->response_body) . '</td>
						<td>' . esc_html($log->logged_at) . '</td>
					</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo 'No API logs found.';
        }
    }

    public function plugin_logs_page() {
        echo '<div class="wrap"> 
				<h2>API Logs</h2>';

        $this->display_api_logs_table();

        echo '</div>';
    }

    private function display_transactions_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'qaupiwc_transactions';

        $txns = $wpdb->get_results($wpdb->prepare("SELECT * FROM `%1s` ORDER BY txn_time DESC", $table_name));

        if ( ! empty($txns) ) {
            echo '<table class="widefat">
					<thead>
						<tr>
							<th>ID</th>
							<th>txn_id</th>
							<th>order_id</th>
							<th>amount</th>
							<th>parsed_output</th>
							<th>txn_time</th>
						</tr>
					</thead>
					<tbody>';

            foreach ( $txns as $txn ) {
                echo '<tr>
						<td>' . esc_html($txn->id) . '</td>
						<td>' . esc_html($txn->txn_id) . '</td>
						<td>' . esc_html($txn->order_id) . '</td>
						<td>' . esc_html($txn->amount) . '</td>
						<td><pre style="max-height: 25vh;overflow-y:auto;">' . wp_kses($txn->parsed_output, array( 'br' => array() )) . '</pre></td>
						<td>' . esc_html($txn->txn_time) . '</td>
					</tr>';
            }

            echo '</tbody></table>';
        } else {
            echo 'No Txn found.';
        }
    }

    public function plugin_transactions_page() {
        echo '<div class="wrap"> 
				<h2>Transactions</h2>';

        $this->display_transactions_table();

        echo '</div>';
    }

    // ==============

    public function wph_settings_content() {
    ?>
        <div class="wrap">
            <h1>Autopilot</h1>
            <?php //settings_errors();
            ?>
            <form method="POST" action="options.php">
                <?php
                settings_fields('AutopilotForUpiwc');
                do_settings_sections('AutopilotForUpiwc');
                submit_button();
                ?>
            </form>
        </div>
<?php
    }

    public function wph_setup_sections() {
        add_settings_section('AutopilotForUpiwc_section', 'Updates Order status based on the payments received on the payee UPI address.', array(), 'AutopilotForUpiwc');
    }

    public function wph_setup_fields() {
        $fields = array(
            array(
                'section'     => 'AutopilotForUpiwc_section',
                'label'       => 'OpenAI API Key',
                'placeholder' => 'key',
                'id'          => 'qaupiwc_openai_key',
                'desc'        => 'API key to parse data from payment messages. If you don\'t have the key yet, you can put any random text and the plugin will automatically use default regex to parse the messages.',
                'type'        => 'text',
                'default'     => $this->defaults->openai_key,
            ),
            array(
                'section'     => 'AutopilotForUpiwc_section',
                'label'       => 'Popup Instructions',
                'placeholder' => 'instructions',
                'id'          => 'qaupiwc_popup_instructions',
                'desc'        => 'Instructions that will be added to the order pay popup on desktop devices.',
                'type'        => 'textarea',
                'default'     => $this->defaults->instructions,
            ),
            array(
                'section'     => 'AutopilotForUpiwc_section',
                'label'       => 'Confirm Message',
                'placeholder' => 'message here',
                'id'          => 'qaupiwc_popup_confirm_message',
                'desc'        => 'This displays a message to customer as payment processing text.',
                'type'        => 'textarea',
                'default'     => $this->defaults->confirm_message,
            ),
        );
        foreach ( $fields as $field ) {
            add_settings_field($field['id'], $field['label'], array( $this, 'wph_field_callback' ), 'AutopilotForUpiwc', $field['section'], $field);
            register_setting('AutopilotForUpiwc', $field['id']);
        }
    }
    public function wph_field_callback( $field ) {
        $value = get_option($field['id']);
        if ( ! $value || empty($value) ) {
            $value = $field['default'] ?? '';
        }

        $placeholder = '';
        if ( isset($field['placeholder']) ) {
            $placeholder = $field['placeholder'];
        }
        switch ( $field['type'] ) {
            case 'textarea':
                printf(
                    '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="7" cols="50">%3$s</textarea>',
                    esc_attr($field['id']),
                    esc_attr($placeholder),
                    esc_attr($value)
                );
                break;
            default:
                printf(
                    '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
                    esc_attr($field['id']),
                    esc_attr($field['type']),
                    esc_attr($placeholder),
                    esc_attr($value)
                );
        }
        if ( isset($field['desc']) ) {
            if ( $desc = $field['desc'] ) {
                printf('<p class="description">%s </p>', esc_html($desc));
            }
        }
    }

    /**
     * Show action links on the plugin screen.
     *
     * @param  mixed $links Plugin Action links.
     * @return array
     */
    public function action_links( $links ) {
        $links[] = '<a href="' . admin_url('options-general.php?page=AutopilotForUpiwc&tab=settings') . '">Settings</a>';

        return $links;
    }
}

<?php

/**
 * Fired during plugin activation
 *
 * @link  https://www.quuantum.com
 * @since 1.0.0
 *
 * @package    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce
 * @subpackage Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce/includes
 * @author     Quuantum <contact@quuantum.com>
 */
class Autopilot_For_Upi_Qr_Code_Payment_For_Woocommerce_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since 1.0.0
     */
    public static function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $logs_table_name = $wpdb->prefix . 'qaupiwc_api_logs';
        $logs_sql = "CREATE TABLE $logs_table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			endpoint varchar(255) NOT NULL,
			method varchar(10) NOT NULL,
			headers text,
			body longtext,
			response_body longtext,
			logged_at datetime NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

        $transactions_table_name = $wpdb->prefix . 'qaupiwc_transactions';
        $transactions_sql = "CREATE TABLE $transactions_table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			txn_id varchar(255) NOT NULL,
			order_id varchar(255) DEFAULT '',
			amount float DEFAULT 0,
			parsed_output longtext,
			txn_time datetime NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($logs_sql);
        dbDelta($transactions_sql);
    }
}

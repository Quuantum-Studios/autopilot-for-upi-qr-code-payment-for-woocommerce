=== Autopilot For UPI QR Code Payment Gateway for WooCommerce ===
Contributors: quuantum
Tags: automatic, upi, upi payment, woocommerce, qrcode, bhim upi, paytm upi, india
Requires at least: 4.6
Tested up to: 6.4
Stable tag: 1.0.5
Requires PHP: 5.6
Donate link: https://www.quuantum.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

This plugin automates the payment verification process for WooCommerce orders made through the [UPI QR Code Payment Gateway for WooCommerce](https://wordpress.org/plugins/upi-qr-code-payment-for-woocommerce/#description), facilitating direct and instant payments via UPI apps like BHIM, GooglePay, WhatsApp, Paytm, PhonePe, or any banking UPI app to save payment gateway charges in India.

== Description ==

This plugin automates the payment verification process for WooCommerce orders made through the [UPI QR Code Payment Gateway for WooCommerce](https://wordpress.org/plugins/upi-qr-code-payment-for-woocommerce/#description), facilitating direct and instant payments via UPI apps like BHIM, GooglePay, WhatsApp, Paytm, PhonePe, or any banking UPI app to save payment gateway charges in India.

### Autopilot For UPI QR Code Payment Gateway for WooCommerce

With this plugin installed and configured, WooCommerce shop owners can streamline the order approval process based on payments received via UPI on their mobile devices.

#### Important Notice

* Currently, only UPI IDs registered on the Paytm app are supported because it provides a direct option to enable SMS alerts for received payments. 
* The [Autopilot Android app](https://github.com/toppersdesk/autopilot-android-app/releases) is required on your phone to forward transaction-related messages to your website.

#### Benefits

* Automatic order confirmation and approval
* Instant order updates
* Simple & Easy to Setup.
* Avoid Payment Gateway Fees.
* Instant Settlement.

#### How It Works

1. **Customer Checkout:** Customer selects UPI as the payment option on the WooCommerce checkout page.
2. **QR Code Display:** A page displaying the UPI QR Code with payment details opens. On mobile, a button appears to take the customer to the list of installed UPI mobile applications.
3. **Payment:** The customer scans the QR Code using any UPI app or chooses an app to pay the required order amount.
4. **Transaction ID:** After successful payment, a 12-digit Transaction/UTR ID appears in the customer's UPI app.
5. **Enter Transaction ID:** The customer enters this 12-digit transaction number into the "Enter the Transaction ID" text box and clicks submit.
6. **Order Completion:** The transaction ID is validated, and the corresponding order is marked as "completed."

#### Future Plans

If this plugin receives significant positive feedback, we plan to add the following advanced features:

* One-click app setup for tracking transaction messages
* Detailed logs of tracked messages
* Auto-update settings of UPI QR Code Payment Gateway plugin to the best-suited version of autopilot
* Additional options to set message templates
* Sender filters in the app
* Auto-adjust settings according to the latest tracked message format
* Email notifications for background app closure due to phone restart

#### Rate & Support Us

If you liked Autopilot For UPIWC, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway/reviews/?rate=5#new-post).
It helps to keep development and support going strong. Thank you!

#### Compatibility

* This plugin is fully compatible with WordPress Version 4.6 and beyond and is compatible with any WordPress theme.

#### Support

* Community support is available via the [support forums](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway) at WordPress.org.

#### Contribute

* Active development of this plugin is handled [on GitHub](https://github.com/toppersdesk/autopilot-for-upi-qr-code-payment-for-woocommerce).
* Contributions are welcome via pull requests.

== Installation ==

1. Visit 'Plugins > Add New'.
1. Search for 'Autopilot For UPI QR Code Payment Gateway for WooCommerce' and install it.
1. Alternatively, upload the `autopilot-for-upi-qr-code-payment-for-woocommerce` folder to the `/wp-content/plugins/` directory manually.
1. Activate Autopilot For UPI QR Code Payment Gateway for WooCommerce from your Plugins page.
1. After activation, go to 'Settings > Autopilot For UPIWC' to configure.

== Frequently Asked Questions ==

= Is there any admin interface for this plugin? =

Yes. You can access it from 'Settings > Autopilot For UPIWC'.

= How do I use this plugin? =

Navigate to 'Settings > Autopilot For UPIWC', enable/disable options as needed, and save your changes.

= Is this plugin compatible with any themes? =

Yes, it's compatible with any WordPress theme, including Genesis and Divi themes.

= Can I enable auto-verification after payment? =

Yes, this plugin facilitates auto-verification. Refer to the detailed steps in the description.

= I encountered an issue with the plugin. What should I do? =

Please provide detailed information about the issue in the [support forum](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway), and we'll work on resolving it.

== Screenshots ==

1. Admin Dashboard Settings
2. API Logs
3. Transactions List
4. Instructions - Auto Setup
5. Instructions - Manual Setup
6. Ideal settings to set up the UPI QR Code Payment Gateway plugin according to Autopilot

== Upgrade Notice == 
No upgrades required.

== Changelog ==

If you find Autopilot For UPI QR Code Payment Gateway for WooCommerce helpful, please consider [leaving a 5-star rating](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway/reviews/?rate=5#new-post) to support further development and maintenance. Thank you!

= 1.0.5 =
Release Date: May 24, 2024

* Updated documentation.

= 1.0.4 =
Release Date: Dec 20, 2023

* Updated Android app.

= 1.0.3 =
Release Date: Dec 20, 2023

* Bug fixes.

= 1.0.2 =
Release Date: Nov 8, 2023

* Added auto setup in the Android app using QR code.

= 1.0.1 =
Release Date: Oct 22, 2023

* Initial production-tested version released.

= 1.0.0 =
Release Date: June 14, 2023

* Initial release.

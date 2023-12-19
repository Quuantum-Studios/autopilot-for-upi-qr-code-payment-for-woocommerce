=== Autopilot For UPI QR Code Payment Gateway for WooCommerce ===
Contributors: quuantum
Tags: automatic, upi, upi payment, woocommerce, qrcode, bhim upi, paytm upi, india
Requires at least: 4.6
Tested up to: 6.3
Stable tag: 1.0.1
Requires PHP: 5.6
Donate link: https://www.quuantum.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

This Plugin enables WooCommerce shop owners to automate the payment verification process done in the plugin [UPI QR Code Payment Gateway for WooCommerce](https://wordpress.org/plugins/upi-qr-code-payment-for-woocommerce/#description) (which is used to get direct and instant payments through UPI apps like BHIM, GooglePay, WhatsApp, Paytm, PhonePe or any banking UPI app to save payment gateway charges in India).

== Description ==

This Plugin enables WooCommerce shop owners to automate the payment verification process done in the plugin [UPI QR Code Payment Gateway for WooCommerce](https://wordpress.org/plugins/upi-qr-code-payment-for-woocommerce/#description) (which is used to get direct and instant payments through UPI apps like BHIM, GooglePay, WhatsApp, Paytm, PhonePe or any banking UPI app to save payment gateway charges in India).

### Autopilot For UPI QR Code Payment Gateway for WooCommerce

When this plugin is installed and setup, WooCommerce shop owners will be able to automatically approve the orders based on payments recieved on their phone via UPI.

#### Important Notice

Currently we only support UPI ids registered on paytm app because it's gives direct option to turn on the SMS alerts for payments recieved.
[Autopilot android app](https://github.com/toppersdesk/autopilot-android-app/releases) is required on you phone to forward txn related messages on your website. 

Like Autopilot For UPI QR Code Payment Gateway for WooCommerce plugin? Consider leaving a 5 star review.

#### Benefits

* Automatic order confirmation and approval
* Instant order updates
* Simple & Easy to Setup.
* Avoid Payment Gateway Fees.
* Instant Settlement.

#### Detailed Steps

* Customer will see UPI as a payment option in WooCommerce Checkout page.
* When customer chooses it, it will open a page which shows the UPI QR Code containing the payment details and in mobile it will also show a button which takes customer to the list of installed UPI mobile applications.
* Customer can scan the QR Code using any UPI app or choose an app from mobile to pay the required order amount.
* After successful payment, a 12-digits Transaction/UTR ID will appear in the Customer's UPI app from which he/she made the payment.
* After that, customer needs to enter that 12 digit transaction number to the "Enter the Transaction ID" text box and click submit.
* After successful submission of the ID, it will be validated and the corresponding order will be marked as "completed".

#### Compatibility

* This plugin is fully compatible with WordPress Version 4.6 and beyond and also compatible with any WordPress theme.

#### Future Plans

If this plugin gets a significant positive response, we will add the following advanced features to help users setup the plugin:
* One click app setup for tracking txn messages.
* Detailed logs of which messages has been tracked.
* Auto update the settings of UPI QR Code Payment Gateway plugin to best suited version of autopilot.
* Additional options to set message templates.
* Sender filters in the app
* Auto adjust settings according to the latest tracked message format.
* In case of background app closing due to phone restart, you will get an email notification.

#### Support
* Community support via the [support forums](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway) at WordPress.org.

#### Contribute
* Active development of this plugin is handled [on GitHub](https://github.com/toppersdesk/autopilot-for-upi-qr-code-payment-for-woocommerce).
* Feel free to [fork the project on GitHub](https://github.com/toppersdesk/autopilot-for-upi-qr-code-payment-for-woocommerce) and submit your contributions via pull request.

== Installation ==

1. Visit 'Plugins > Add New'.
1. Search for 'Autopilot For UPI QR Code Payment Gateway for WooCommerce' and install it.
1. Or you can upload the `autopilot-for-upi-qr-code-payment-for-woocommerce` folder to the `/wp-content/plugins/` directory manually.
1. Activate Autopilot For UPI QR Code Payment Gateway for WooCommerce from your Plugins page.
1. After activation go to 'Settings > Autopilot'.

== Frequently Asked Questions ==

= Is there any admin interface for this plugin? =

Yes. You can access this from 'Settings > Autopilot'.

= How to use this plugin? =

Go to 'Settings > Autopilot', enable/disable options as per your need and save your changes.

= Is this plugin compatible with any themes? =

Yes, this plugin is compatible with any theme. Also, compatible with Genesis, Divi themes.

= I want auto verification after payment is done. Is is possible? =

Yes, this plugin is all about auto verification. Check description above.

= The plugin isn't working or have a bug? =

Post detailed information about the issue in the [support forum](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway) and I will work to fix it.

== Screenshots ==

1. Admin Dashboard Settings
2. API logs
3. Transactions list
4. Instructions - auto setup
5. Instructions - manual setup
6. Ideal settings to setup UPI QR Code Payment Gateway plugin according to autopilot

== Upgrade Notice == 
No Upgrades required.

== Changelog ==

If you like Autopilot For UPI QR Code Payment Gateway for WooCommerce, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!

= 1.0.2 =
Release Date: Nov 8, 2023

* Auto setup in android app using QR code added.

= 1.0.1 =
Release Date: Oct 22, 2023

* First production tested version released.

= 1.0.0 =
Release Date: June 14, 2023

* Initial release.
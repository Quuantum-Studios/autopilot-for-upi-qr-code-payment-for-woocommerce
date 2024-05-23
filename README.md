![Autopilot For UPI QR Code Payment Gateway for WooCommerce](.github/banner.png "Plugin Banner")

# Autopilot For UPIWC

This plugin enables WooCommerce shop owners to automate the payment verification process using the [**UPI QR Code Payment Gateway for WooCommerce**](https://wordpress.org/plugins/upi-qr-code-payment-for-woocommerce/#description) plugin. It allows direct and instant payments through UPI apps like BHIM, GooglePay, WhatsApp, Paytm, PhonePe, or any banking UPI app, saving payment gateway charges in India.

When this plugin is installed and set up, WooCommerce shop owners can automatically approve orders based on payments received on their phone via UPI.

## Important Notice

Currently, we only support UPI IDs registered on the Paytm app because it provides a direct option to turn on SMS alerts for payments received.

The [Autopilot Android app](https://github.com/toppersdesk/autopilot-android-app/releases) is required on your phone to forward transaction-related messages to your website.

## Benefits

- Automatic order confirmation and approval
- Instant order updates
- Simple & easy to set up
- Avoid payment gateway fees
- Instant settlement

## How It Works

1. **Customer Checkout:** Customer selects UPI as the payment option on the WooCommerce checkout page.
2. **QR Code Display:** A page displaying the UPI QR Code with payment details opens. On mobile, a button appears to take the customer to the list of installed UPI mobile applications.
3. **Payment:** The customer scans the QR Code using any UPI app or chooses an app to pay the required order amount.
4. **Transaction ID:** After successful payment, a 12-digit Transaction/UTR ID appears in the customer's UPI app.
5. **Enter Transaction ID:** The customer enters this 12-digit transaction number into the "Enter the Transaction ID" text box and clicks submit.
6. **Order Completion:** The transaction ID is validated, and the corresponding order is marked as "completed."

## Installation

### From within WordPress

1. Visit 'Plugins > Add New'.
2. Search for 'Autopilot For UPI QR Code Payment Gateway for WooCommerce' and install it.

### Manually

1. Upload the `autopilot-for-upi-qr-code-payment-for-woocommerce` folder to the `/wp-content/plugins/` directory.
2. Activate Autopilot For UPI QR Code Payment Gateway for WooCommerce from your Plugins page.

### After Activation

1. Go to 'Settings > Autopilot For UPIWC' to configure the plugin.
2. Enable/disable options and save changes.

## Future Plans

If this plugin receives significant positive feedback, we plan to add the following advanced features:

- One-click app setup for tracking transaction messages
- Detailed logs of tracked messages
- Auto-update settings of UPI QR Code Payment Gateway plugin to the best-suited version of autopilot
- Additional options to set message templates
- Sender filters in the app
- Auto-adjust settings according to the latest tracked message format
- Email notifications for background app closure due to phone restart

### Frequently Asked Questions

#### Is there any admin interface for this plugin?

Yes. You can access it from 'Settings > Autopilot For UPIWC'.

#### How to use this plugin?

Go to 'Settings > Autopilot For UPIWC', enable/disable options as per your needs, and save your changes.

#### Is this plugin compatible with any themes?

Yes, this plugin is compatible with any theme, including Genesis and Divi themes.

#### I want auto verification after payment is done. Is it possible?

Yes, this plugin provides auto verification. See the description above for details.

#### The plugin isn't working or has a bug?

Post detailed information about the issue in the [support forum](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway) and we will work to fix it.

## Compatibility

This plugin is fully compatible with WordPress version 4.6 and beyond, and it works with any WordPress theme.

## Rate & Support Us

If you liked Autopilot For UPIWC, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway/reviews/?rate=5#new-post).
It helps to keep development and support going strong. Thank you!

## Support

Community support is available via the [support forums](https://wordpress.org/support/plugin/autopilot-for-upi-qr-code-payment-gateway) at WordPress.org.

## Contribute

Feel free to fork the project on GitHub and submit your contributions via pull request.

## Changelog
[View Changelog](CHANGELOG.md)
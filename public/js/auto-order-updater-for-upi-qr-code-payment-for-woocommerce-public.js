(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	console.log("addon loaded");
	let intervalId = null;
	function sendPostRequestWithPayerId(intervalId) {
		if (intervalId) {
			stopPostRequestWithPayerId(intervalId);
		}

		return setInterval(function () {
			jQuery.ajax({
				url: qaouData.ajax_url,
				data: {
					action: 'check_order_status_upi_paid',
					...upiwcData
				},
				type: 'POST',
				success: function (data) {
					console.log('success');
				},
				error: function (error) {
					console.log(error);
				}
			});
		}, 5000);
	}

	function stopPostRequestWithPayerId(intervalId) {
		clearInterval(intervalId);
	}

	jQuery(document).on('upiwcOnOpenBefore', function (el, data) {
		intervalId = sendPostRequestWithPayerId(intervalId);
	});

	jQuery(document).on('upiwcOnClose', function (el, data) {
		stopPostRequestWithPayerId(intervalId);
	});

	jQuery(document).on('upiwcOnContentReady', function (el, data) {
		console.log(intervalId);
		console.log('upiwcOnContentReady');
		if (!intervalId) {
			intervalId = sendPostRequestWithPayerId(intervalId);
		}
	});
	
})( jQuery );

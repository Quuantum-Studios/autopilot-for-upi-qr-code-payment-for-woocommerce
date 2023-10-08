(function ( $ ) {
    ("use strict");

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
    let order_status_checker_id = null;
    let scan_checker_id = null;
    let upiwcTxnId = null;
    let txnStatusCheckerTimeout = 120000;

    function qaupiwc_check_order_status(order_status_checker_id)
    {
        if (order_status_checker_id) {
            stop_qaupiwc_check_order_status(order_status_checker_id);
        }

        return setInterval(
            function () {
                jQuery.ajax(
                    {
                        url: qaupiwcData.ajax_url,
                        data: {
                            nonce: qaupiwcData.nonce,
                            action: "check_order_status_upi_paid",
                            ...upiwcData,
                            txn_id: upiwcTxnId,
                        },
                        type: "POST",
                        dataType: "json",
                        success: function (data) {
                            if (data.data.status === "payment_success") {
                                stop_qaupiwc_check_order_status(order_status_checker_id);

                                let tran_id = upiwcTxnId;
                                let tran_id_field = "";
                                if (tran_id !== undefined 
                                    && typeof tran_id !== "undefined" 
                                    && tran_id != ""
                                ) {
                                    tran_id_field =
                                    '<input type="hidden" name="wc_transaction_id" value="' +
                                    tran_id +
                                    '"></input>';
                                }

                                $("#upiwc-payment-success-container-pending").html(
                                    '<form method="POST" action="' +
                                    upiwcData.callback_url +
                                    '" id="UPIJSCheckoutForm" style="display: none;"><input type="hidden" name="wc_order_id" value="' +
                                    upiwcData.order_id +
                                    '"><input type="hidden" name="wc_order_key" value="' +
                                    upiwcData.order_key +
                                    '">' +
                                    tran_id_field +
                                    "</form>"
                                );
                                $("body").find("#UPIJSCheckoutForm").submit();
                            } else {
                                console.log(data);
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        },
                    }
                );
            }, 5000
        );
    }

    function stop_qaupiwc_check_order_status(order_status_checker_id)
    {
        clearInterval(order_status_checker_id);
    }

    function qaupiwc_check_qr_scanned(popup = null)
    {
        if (scan_checker_id) {
            stop_qaupiwc_check_qr_scanned();
        }

        return setInterval(
            function () {
                jQuery.ajax(
                    {
                        url: qaupiwcData.ajax_url,
                        data: {
                            nonce: qaupiwcData.nonce,
                            action: "check_qr_scanned",
                            amount: upiwcData.order_amount
                        },
                        type: "POST",
                        dataType: "json",
                        success: function (data) {
                            if (data.data.status === "scan_success") {
                                stop_qaupiwc_check_qr_scanned();
                                popup.buttons.nextStep.setText('Proceed to Next');
                                popup.buttons.nextStep.enable();
                                popup.$_nextStep.trigger('click');
                            } else {
                                console.log(data);
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        },
                    }
                );
            }, 3000
        );
    }

    function stop_qaupiwc_check_qr_scanned()
    {
        clearInterval(scan_checker_id);
    }

    function openPopupForPaymentTimeout(upiwcPopup)
    {
        jQuery.confirm(
            {
                title:
                "<div style='font-size: 0.9em; color:white'>Transaction Timeout</div>",
                content:
                "<div style='font-size: 0.8em;padding: 20px;text-align: justify;'>Seems like either you have provided the wrong transaction Id or the bank is taking too long to verify your transaction at the merchant side. Please update the transaction ID or proceed to submit it so that once the merchant manually verifies it within next 24hrs, your order will be confirmed.</div>",
                useBootstrap: false,
                animation: "scale",
                boxWidth: "375px",
                draggable: false,
                offsetBottom: 0,
                offsetTop: 0,
                closeIcon: false,
                bgOpacity: 0.8,
                // lazyOpen: true,
                theme: upiwcData.theme,
                buttons: {
                    updateTxnId: {
                        text: "Update Transaction ID",
                        btnClass: "btn",
                        action: function () {
                            upiwcPopup.buttons.confirm.enable();
                            upiwcPopup.buttons.confirm.setText("Confirm");
                            upiwcPopup.$content
                            .find("#upiwc-payment-transaction-number")
                            .prop("disabled", false);
                        },
                    },
                    proceed: {
                        text: "Proceed to Submit",
                        btnClass: "btn-blue",
                        action: function () {
                            let tran_id = upiwcPopup.$content
                            .find("#upiwc-payment-transaction-number")
                            .val();

                            let tran_id_field = "";
                            if (tran_id !== undefined 
                                && typeof tran_id !== "undefined" 
                                && tran_id != ""
                            ) {
                                tran_id_field =
                                  '<input type="hidden" name="wc_transaction_id" value="' +
                                  tran_id +
                                  '"></input>';
                            }

                            $("#upiwc-payment-success-container-pending").html(
                                '<form method="POST" action="' +
                                upiwcData.callback_url +
                                '" id="UPIJSCheckoutForm" style="display: none;"><input type="hidden" name="wc_order_id" value="' +
                                upiwcData.order_id +
                                '"><input type="hidden" name="wc_order_key" value="' +
                                upiwcData.order_key +
                                '">' +
                                tran_id_field +
                                "</form>"
                            );
                            $("body").find("#UPIJSCheckoutForm").submit();
                        },
                    },
                },
            }
        );
    }

    jQuery(document).on(
        "upiwcAfterConfirmAction", function (event, self) {
            self.$content
            .find("#upiwc-payment-transaction-number")
            .prop("disabled", true);
            upiwcTxnId = self.$content.find("#upiwc-payment-transaction-number").val();

            upiwcStartTimer(
                txnStatusCheckerTimeout / 1000,
                document.querySelector(".btn.upiwc-confirm")
            );
            setTimeout(
                function () {
                    self.buttons.confirm.setText("Processing..");
                    stop_qaupiwc_check_qr_scanned();
                    stop_qaupiwc_check_order_status(order_status_checker_id);
                    openPopupForPaymentTimeout(self);
                }, txnStatusCheckerTimeout
            );

            order_status_checker_id = qaupiwc_check_order_status(order_status_checker_id);
        }
    );

    jQuery(document).on(
        "upiwcOnClose", function (el, data) {
            stop_qaupiwc_check_order_status(order_status_checker_id);
            stop_qaupiwc_check_qr_scanned();
        }
    );

    jQuery(document).on(
        "upiwcAfterContentReady", function (event, self) {
            console.log("upiwcAfterContentReady");
            self.buttons.nextStep.setText('Awaiting payment..');
            self.buttons.nextStep.disable();
            scan_checker_id = qaupiwc_check_qr_scanned(self);
            // console.log('scanner id', scan_checker_id);
        }
    );

    jQuery(document).on(
        "upiwcBeforeContentReady", function (event, self) {
            console.log("upiwcBeforeContentReady");
            jQuery("#upiwc-payment-success-container").attr(
                "id",
                "upiwc-payment-success-container-pending"
            );
        }
    );

    jQuery(document).on(
        "upiwcOnOpenBefore", function (event, self) {
            console.log("upiwcOnOpenBefore");
            self.$content.find('.upiwc-payment-content').prepend('<h3 class="qaupiwc-steps">Step 1</h3>');
            self.$content.find(".upiwc-payment-info-text").html(qaupiwcData.instructions);
            self.$content.find(".upiwc-payment-confirm-text").html(qaupiwcData.confirm_message);
        }
    );

    jQuery(document).on(
        "upiwcBeforeNextStepAction", function (event, self) {
            console.log("upiwcBeforeNextStepAction");
            self.$content.find('.qaupiwc-steps').text('Step 2');
        }
    );

    jQuery(document).on(
        "upiwcBeforeBackAction", function (event, self) {
            console.log("upiwcBeforeBackAction");
            self.$content.find('.qaupiwc-steps').text('Step 1');
        }
    );
})(jQuery);

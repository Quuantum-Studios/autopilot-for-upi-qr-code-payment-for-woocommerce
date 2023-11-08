(function ( $ ) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
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


    function copyToClipboard(text)
    {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.setAttribute('readonly', '');
        textarea.style.position = 'absolute';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    }

    $(document).ready(
        function () {
            $('pre').each(
                function () {
                    var $pre = $(this);

                    var $copyBtn = $('<button class="copy-btn">Copy</button>');
                    $pre.append($copyBtn);

                    $copyBtn.click(
                        function () {
                            var code = $pre.clone().find('.copy-btn').remove().end().text();
                            copyToClipboard(code);
                            $copyBtn.text('Copied!');
                            setTimeout(
                                function () {
                                    $copyBtn.text('Copy');
                                }, 2000
                            );
                        }
                    );
                }
            );
        }
    );

    $(document).on('click', '.nav-tab-wrapper.instructions a', function () {
        $('section.instructions').hide();
        $('section.instructions').eq($(this).index()).show();
        return false;
    })

})(jQuery);

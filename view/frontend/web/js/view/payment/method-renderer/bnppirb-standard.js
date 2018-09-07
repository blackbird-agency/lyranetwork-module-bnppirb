/**
 * BNPP IRB V2-Payment Module version 2.3.1 for Magento 2.x. Support contact : csp.monetique.afrique@bnpparibas.com.
 *
 * NOTICE OF LICENSE
 *
 * This source file is licensed under the Open Software License version 3.0
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/osl-3.0.php
 *
 * @author    Lyra Network (http://www.lyra-network.com/)
 * @copyright 2014-2018 Lyra Network and contributors
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @category  payment
 * @package   bnppirb
 */

/*browser:true*/
/*global define*/
define(
    [
        'jquery',
        'Lyranetwork_Bnppirb/js/view/payment/method-renderer/bnppirb-abstract',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function ($, Component, fullScreenLoader) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Lyranetwork_Bnppirb/payment/bnppirb-standard',
                bnppirbCcType: window.checkoutConfig.payment.bnppirb_standard.availableCcTypes[0]['value'] || null
            },

            afterPlaceOrder: function () {
                if (this.getEntryMode() == 3) {
                    // iframe mode
                    fullScreenLoader.stopLoader();

                    $('.payment-method._active .payment-method-content .bnppirb-form').hide();
                    $('.payment-method._active .payment-method-content .bnppirb-iframe').show();

                    var iframe = $('.payment-method._active .payment-method-content .bnppirb-iframe.iframe');
                    if (iframe && iframe.length) {
                        var url = this.getCheckoutRedirectUrl() + '?iframe=true';
                        iframe.attr('src', url);
                    }
                } else {
                    this._super();
                }
            }
        });
    }
);

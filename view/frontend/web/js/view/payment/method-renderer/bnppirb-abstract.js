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
        'Magento_Checkout/js/view/payment/default'
    ],
    function ($, Component) {
        'use strict';

        return Component.extend({
            defaults: {
                redirectAfterPlaceOrder: false
            },

            initObservable: function () {
                this._super().observe('bnppirbCcType');
                return this;
            },

            getModuleLogoUrl: function () {
                return window.checkoutConfig.payment[this.item.method].moduleLogoUrl;
            },

            getAvailableCcTypes: function () {
                return window.checkoutConfig.payment[this.item.method].availableCcTypes;
            },

            getEntryMode: function () {
                return window.checkoutConfig.payment[this.item.method].entryMode;
            },

            getData: function () {
                var data = this._super(), additionalData = {};
                additionalData[this.item.method + '_cc_type'] = this.bnppirbCcType();

                return $.extend(true, data, {
                    'additional_data': additionalData
                });
            },

            showLabel: function () {
                return false;
            },

            getIframeLoaderUrl: function () {
                return window.checkoutConfig.payment[this.item.method].iframeLoaderUrl;
            },

            getCheckoutRedirectUrl: function () {
                return window.checkoutConfig.payment[this.item.method].checkoutRedirectUrl;
            },

            afterPlaceOrder: function () {
                // order placed with payment_pending status, redirect to gateway
                $.mage.redirect(this.getCheckoutRedirectUrl());
            }
        });
    }
);
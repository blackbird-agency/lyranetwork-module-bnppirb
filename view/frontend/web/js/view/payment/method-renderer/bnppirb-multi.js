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
        'Lyranetwork_Bnppirb/js/view/payment/method-renderer/bnppirb-abstract'
    ],
    function (Component) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Lyranetwork_Bnppirb/payment/bnppirb-multi',
                bnppirbMultiOption: window.checkoutConfig.payment.bnppirb_multi.availableOptions[0]['key'],
                bnppirbCcType: window.checkoutConfig.payment.bnppirb_multi.availableCcTypes ?
                    window.checkoutConfig.payment.bnppirb_multi.availableCcTypes[0]['value'] : null
            },

            initObservable: function () {
                this._super().observe('bnppirbMultiOption');
                return this;
            },

            getData: function () {
                var data = this._super();
                data['additional_data']['bnppirb_multi_option'] = this.bnppirbMultiOption();

                return data;
            },

            getAvailableOptions: function () {
                return window.checkoutConfig.payment.bnppirb_multi.availableOptions;
            },

            showLabel: function () {
                return true;
            }
        });
    }
);
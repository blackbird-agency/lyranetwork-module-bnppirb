<?php
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
namespace Lyranetwork\Bnppirb\Helper\Payment;

use Magento\Framework\View\LayoutFactory;

class Data extends \Magento\Payment\Helper\Data
{

    /**
     *
     * @var \Lyranetwork\Bnppirb\Helper\Data
     */
    protected $dataHelper;

    /**
     * Construct
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param LayoutFactory $layoutFactory
     * @param \Magento\Payment\Model\Method\Factory $paymentMethodFactory
     * @param \Magento\Store\Model\App\Emulation $appEmulation
     * @param \Magento\Payment\Model\Config $paymentConfig
     * @param \Magento\Framework\App\Config\Initial $initialConfig
     * @param \Lyranetwork\Bnppirb\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        LayoutFactory $layoutFactory,
        \Magento\Payment\Model\Method\Factory $paymentMethodFactory,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Payment\Model\Config $paymentConfig,
        \Magento\Framework\App\Config\Initial $initialConfig,
        \Lyranetwork\Bnppirb\Helper\Data $dataHelper
    ) {
        parent::__construct(
            $context,
            $layoutFactory,
            $paymentMethodFactory,
            $appEmulation,
            $paymentConfig,
            $initialConfig
        );

        $this->dataHelper = $dataHelper;
    }

    /**
     * Retrieve all payment methods.
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $methods = parent::getPaymentMethods();

        $bnppirbMultiTitle = $methods['bnppirb_multi']['title']; // get multi payment general title
        unset($methods['bnppirb_multi']);

        // add BNPP IRB multiple payment virtual methods to the list
        foreach ($this->dataHelper->getMultiPaymentModelConfig() as $config) {
            $code = substr($config['path'], strlen('payment/'), - strlen('/model'));
            $count = substr($code, strlen('bnppirb_multi_'));

            $methods[$code] = [
                'model' => $config['value'],
                'title' => $bnppirbMultiTitle . " ($count)",
                'group' => 'bnppirb'
            ];
        }

        return $methods;
    }
}

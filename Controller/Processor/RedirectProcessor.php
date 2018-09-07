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
namespace Lyranetwork\Bnppirb\Controller\Processor;

class RedirectProcessor
{

    /**
     *
     * @var \Lyranetwork\Bnppirb\Helper\Data
     */
    protected $dataHelper;

    /**
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     *
     * @param \Lyranetwork\Bnppirb\Helper\Data $dataHelper
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(\Lyranetwork\Bnppirb\Helper\Data $dataHelper, \Magento\Framework\Registry $coreRegistry)
    {
        $this->dataHelper = $dataHelper;
        $this->coreRegistry = $coreRegistry;
    }

    public function execute(\Lyranetwork\Bnppirb\Api\RedirectActionInterface $controller)
    {
        try {
            $order = $controller->getAndCheckOrder();

            // add history comment and save it
            $order->addStatusHistoryComment(__('Client sent to BNPP IRB gateway.'), false)
                ->setIsCustomerNotified(false)
                ->save();

            $method = $order->getPayment()->getMethodInstance();
            $this->coreRegistry->register(
                \Lyranetwork\Bnppirb\Block\Constants::PARAMS_REGISTRY_KEY,
                $method->getFormFields($order)
            );
            $this->coreRegistry->register(
                \Lyranetwork\Bnppirb\Block\Constants::URL_REGISTRY_KEY,
                $method->getPlatformUrl()
            );

            // redirect to gateway
            $this->dataHelper->log("Client {$order->getCustomerEmail()} sent to payment page for order #{$order->getId()}.");

            return $controller->forward();
        } catch (\Lyranetwork\Bnppirb\Model\OrderException $e) {
            return $controller->back($e->getMessage());
        }
    }
}

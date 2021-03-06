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
namespace Lyranetwork\Bnppirb\Model\Method;

class Multi extends Bnppirb
{

    protected $_code = \Lyranetwork\Bnppirb\Helper\Data::METHOD_MULTI;

    protected $_formBlockType = \Lyranetwork\Bnppirb\Block\Payment\Form\Multi::class;

    protected $_canRefund = false;

    protected $_canRefundInvoicePartial = false;

    /**
     *
     * @var \Lyranetwork\Bnppirb\Model\System\Config\Source\MultiPaymentCard
     */
    protected $multiCardPayment;

    /**
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Locale\ResolverInterface $localeResolver
     * @param \Lyranetwork\Bnppirb\Model\Api\BnppirbRequest $bnppirbRequest
     * @param \Lyranetwork\Bnppirb\Helper\Data $dataHelper
     * @param \Lyranetwork\Bnppirb\Helper\Payment $paymentHelper
     * @param \Lyranetwork\Bnppirb\Helper\Checkout $checkoutHelper
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Module\Dir\Reader $dirReader
     * @param \Magento\Framework\DataObject\Factory $dataObjectFactory
     * @param \Lyranetwork\Bnppirb\Model\System\Config\Source\MultiPaymentCard $multiCardPayment
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Lyranetwork\Bnppirb\Model\Api\BnppirbRequestFactory $bnppirbRequestFactory,
        \Lyranetwork\Bnppirb\Helper\Data $dataHelper,
        \Lyranetwork\Bnppirb\Helper\Payment $paymentHelper,
        \Lyranetwork\Bnppirb\Helper\Checkout $checkoutHelper,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Module\Dir\Reader $dirReader,
        \Magento\Framework\DataObject\Factory $dataObjectFactory,
        \Lyranetwork\Bnppirb\Model\System\Config\Source\MultiPaymentCard $multiCardPayment,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->multiCardPayment = $multiCardPayment;

        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $localeResolver,
            $bnppirbRequestFactory,
            $dataHelper,
            $paymentHelper,
            $checkoutHelper,
            $productMetadata,
            $messageManager,
            $dirReader,
            $dataObjectFactory,
            $resource,
            $resourceCollection,
            $data
        );
    }

    protected function setExtraFields($order)
    {
        // set payment_src to MOTO for backend payments
        if ($this->dataHelper->isBackend()) {
            $this->bnppirbRequest->set('payment_src', 'MOTO');
        }

        $info = $this->getInfoInstance();

        if ($this->isLocalCcType() && $info->getCcType()) {
            $this->bnppirbRequest->set('payment_cards', $info->getCcType());
        } else {
            // payment_cards is given as csv by magento
            $paymentCards = explode(',', $this->getConfigData('payment_cards'));
            $paymentCards = in_array('', $paymentCards) ? '' : implode(';', $paymentCards);

            $this->bnppirbRequest->set('payment_cards', $paymentCards);
        }

        // set mutiple payment option
        $option = @unserialize($info->getAdditionalInformation(\Lyranetwork\Bnppirb\Helper\Payment::MULTI_OPTION));

        $amount = $this->bnppirbRequest->get('amount');
        $first = ($option['first'] != '') ? round(($option['first'] / 100) * $amount) : null;
        $this->bnppirbRequest->setMultiPayment($amount, $first, $option['count'], $option['period']);
        $this->bnppirbRequest->set('contracts', ($option['contract']) ? 'CB=' . $option['contract'] : null);

        $this->dataHelper->log('Multiple payment configuration is ' . $this->bnppirbRequest->get('payment_config'));
    }

    /**
     * Assign data to info model instance.
     *
     * @param array|\Magento\Framework\DataObject $data
     * @return $this
     */
    public function assignData(\Magento\Framework\DataObject $data)
    {
        // reset payment method specific data
        $this->resetData();

        parent::assignData($data);

        $info = $this->getInfoInstance();

        $bnppirbData = $this->extractBnppirbData($data);

        // load option informations
        $option = $this->getOption($bnppirbData->getData('bnppirb_multi_option'));

        $info->setCcType($bnppirbData->getData('bnppirb_multi_cc_type'))
            ->setAdditionalInformation(\Lyranetwork\Bnppirb\Helper\Payment::MULTI_OPTION, serialize($option));

        return $this;
    }

    /**
     * Return true if the method can be used at this time.
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        if (! parent::isAvailable($quote)) {
            return false;
        }

        $amount = $quote ? $quote->getBaseGrandTotal() : null;
        if ($amount) {
            $options = $this->getAvailableOptions($amount);
            return count($options) > 0;
        }

        return true;
    }

    /**
     * Return available payment options to be displayed on payment method list page.
     *
     * @param double $amount
     *            a given amount
     * @return array[string][array] An array "$code => $option" of availables options
     */
    public function getAvailableOptions($amount = null)
    {
        $configOptions = $this->dataHelper->unserialize($this->getConfigData('multi_payment_options'));

        $options = [];
        if (is_array($configOptions) && ! empty($configOptions)) {
            foreach ($configOptions as $code => $value) {
                if (empty($value)) {
                    continue;
                }

                if ((! $amount || ! $value['minimum'] || $amount > $value['minimum']) &&
                     (! $amount || ! $value['maximum'] || $amount < $value['maximum'])) {
                    // option will be available
                    $options[$code] = $value;
                }
            }
        }

        return $options;
    }

    private function getOption($code)
    {
        $info = $this->getInfoInstance();
        if ($info instanceof \Mage\Sales\Model\Order\Payment) {
            $amount = $info->getOrder()->getBaseGrandTotal();
        } else {
            $amount = $info->getQuote()->getBaseGrandTotal();
        }

        $options = $this->getAvailableOptions($amount);

        if ($code && isset($options[$code])) {
            return $options[$code];
        } else {
            return false;
        }
    }

    /**
     * Return available card types
     *
     * @return string
     */
    public function getAvailableCcTypes()
    {
        // all cards
        $allCards = $this->multiCardPayment->toOptionArray();

        // selected cards from module configuration
        $cards = $this->getConfigData('payment_cards');
        $cards = ! empty($cards) ? explode(',', $cards) : [];

        $availCards = [];
        foreach ($allCards as $card) {
            if (! $card['value']) {
                continue;
            }

            if (empty($cards) || in_array($card['value'], $cards)) {
                $availCards[$card['value']] = $card['label'];
            }
        }

        return $availCards;
    }

    /**
     * Check if the local card type selection option is choosen.
     *
     * @return bool
     */
    public function isLocalCcType()
    {
        if ($this->dataHelper->isBackend()) {
            return false;
        }

        return $this->getConfigData('card_info_mode') == 2;
    }
}

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

class Choozeo extends Bnppirb
{

    protected $_code = \Lyranetwork\Bnppirb\Helper\Data::METHOD_CHOOZEO;

    protected $_formBlockType = \Lyranetwork\Bnppirb\Block\Payment\Form\Choozeo::class;

    protected $_canUseInternal = false;

    protected $currencies = ['EUR'];

    protected function setExtraFields($order)
    {
        // override some form data
        $this->bnppirbRequest->set('validation_mode', '0');
        $this->bnppirbRequest->set('cust_status', 'PRIVATE');

        // send phone number as cell phone
        $this->bnppirbRequest->set('cust_cell_phone', $order->getBillingAddress()->getTelephone());

        // override with selected Choozeo payment card
        $info = $this->getInfoInstance();
        $this->bnppirbRequest->set('payment_cards', $info->getCcType());
    }

    /**
     * Assign data to info model instance.
     *
     * @param \Magento\Framework\DataObject $data
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
        $option = $bnppirbData->getData('bnppirb_choozeo_option');
        $info->setCcType($option)->setAdditionalInformation(\Lyranetwork\Bnppirb\Helper\Payment::CHOOZEO_OPTION, $option);

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
        $configOptions = $this->dataHelper->unserialize($this->getConfigData('choozeo_payment_options'));

        /** @var array[string][string] $options */
        $options = [
            'EPNF_3X' => 'Choozeo 3X CB',
            'EPNF_4X' => 'Choozeo 4X CB'
        ];

        $availOptions = [];
        if (is_array($configOptions) && ! empty($configOptions)) {
            foreach ($configOptions as $code => $value) {
                if (empty($value)) {
                    continue;
                }

                if ((! $amount || ! $value['amount_min'] || $amount > $value['amount_min'])
                    && (! $amount || ! $value['amount_max'] || $amount < $value['amount_max'])) {

                    $value['label'] = $options[$value['code']];

                    // option will be available
                    $availOptions[$code] = $value;
                }
            }
        }

        return $availOptions;
    }
}

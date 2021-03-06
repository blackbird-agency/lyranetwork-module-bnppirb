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
namespace Lyranetwork\Bnppirb\Block\Payment\Form;

class Standard extends Bnppirb
{

    protected $_template = 'Lyranetwork_Bnppirb::payment/form/standard.phtml';

    /**
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Lyranetwork\Bnppirb\Helper\Data $dataHelper
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Lyranetwork\Bnppirb\Helper\Data $dataHelper,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;

        parent::__construct($context, $dataHelper, $data);
    }

    public function getAvailableCcTypes()
    {
        return $this->getMethod()->getAvailableCcTypes();
    }

    public function getCcTypeNetwork($code)
    {
        $cbCards = [
            'CB',
            'VISA',
            'VISA_ELECTRON',
            'MASTERCARD',
            'MAESTRO',
            'E-CARTEBLEUE'
        ];

        if ($code == 'AMEX') {
            return 'AMEX';
        } elseif (in_array($code, $cbCards)) {
            return 'CB';
        } else {
            return null;
        }
    }

    public function getCcTypeImageSrc($card)
    {
        $card = 'cc/' . strtolower($card) . '.png';

        if ($this->dataHelper->isUploadFileImageExists($card)) {
            return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .
                 'bnppirb/images/' . $card;
        } else {
            return $this->getViewFileUrl('Lyranetwork_Bnppirb::images/' . $card);
        }
    }

    public function getLoggedCustomer()
    {
        return $this->customerSession->getCustomer();
    }

    public function isLocalCcType()
    {
        return $this->getMethod()->isLocalCcType();
    }
}

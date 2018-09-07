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
namespace Lyranetwork\Bnppirb\Model\System\Config\Backend\Oney;

class Active extends \Magento\Framework\App\Config\Value
{

    protected $message;

    /**
     *
     * @var \Lyranetwork\Bnppirb\Helper\Checkout
     */
    protected $checkoutHelper;

    /**
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Lyranetwork\Bnppirb\Helper\Checkout $checkoutHelper
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Lyranetwork\Bnppirb\Helper\Checkout $checkoutHelper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->checkoutHelper = $checkoutHelper;

        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function save()
    {
        $this->message = '';

        if ($this->getValue() /* sub-module enabled */) {
            $data = $this->getGroups('bnppirb'); // get data of general config group
            $oneyContract = isset($data['fields']['oney_contract']['value']) && $data['fields']['oney_contract']['value'];

            if (! $oneyContract) {
                $this->setValue(0);

                $this->message = __('Please configure &laquo;ADDITIONAL OPTIONS&raquo; part of &laquo;BNPP IRB&raquo; section.')->render();
            } else {
                try {
                    // check Oney requirements
                    $this->checkoutHelper->checkOneyRequirements($this->getScope(), $this->getScopeId());
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->setValue(0);

                    $this->message = $e->getMessage();
                }
            }
        }

        return parent::save();
    }

    public function afterCommitCallback()
    {
        if (! empty($this->message)) {
            $this->message .= "\n" . __('FacilyPay Oney payment mean cannot be used.')->render();
            throw new \Magento\Framework\Exception\LocalizedException(__($this->message));
        }

        return parent::afterCommitCallback();
    }
}

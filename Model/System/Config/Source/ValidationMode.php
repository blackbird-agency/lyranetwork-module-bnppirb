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
namespace Lyranetwork\Bnppirb\Model\System\Config\Source;

class ValidationMode extends \Magento\Framework\DataObject implements \Magento\Framework\Option\ArrayInterface
{

    public function toOptionArray()
    {
        $options = [
            [
                'value' => '',
                'label' => __('Back Office configuration')
            ],
            [
                'value' => '0',
                'label' => __('Automatic')
            ],
            [
                'value' => '1',
                'label' => __('Manual')
            ]
        ];

        if (stripos($this->getPath(), '/bnppirb_general/') === false) {
            array_unshift($options, [
                'value' => '-1',
                'label' => __('BNPP IRB general configuration')
            ]);
        }

        return $options;
    }
}

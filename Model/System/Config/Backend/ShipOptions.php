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
namespace Lyranetwork\Bnppirb\Model\System\Config\Backend;

class ShipOptions extends \Lyranetwork\Bnppirb\Model\System\Config\Backend\Serialized\ArraySerialized\ConfigArraySerialized
{

    public function beforeSave()
    {
        $data = $this->getGroups('bnppirb'); // get data of general config group
        $oneyContract = isset($data['fields']['oney_contract']['value']) && $data['fields']['oney_contract']['value'];

        if ($oneyContract) {
            $deliveryCompanyRegex = "#^[A-Z0-9ÁÀÂÄÉÈÊËÍÌÎÏÓÒÔÖÚÙÛÜÇ /'-]{1,127}$#ui";

            $values = $this->getValue();

            if (! is_array($values) || empty($values)) {
                $this->setValue([]);
            } else {
                $i = 0;
                foreach ($values as $key => $value) {
                    $i ++;

                    if (empty($value)) {
                        continue;
                    }

                    if (empty($value['oney_label']) || ! preg_match($deliveryCompanyRegex, $value['oney_label'])) {
                        $this->throwException('FacilyPay Oney label', $i);
                    }
                }
            }
        } else {
            $this->setValue([]);
        }

        return parent::beforeSave();
    }
}

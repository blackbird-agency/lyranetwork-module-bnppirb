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

namespace Lyranetwork\Bnppirb\Model\Api\Ws;

class ExtraResponse
{
    /**
     * @var string $paymentOptionCode
     */
    private $paymentOptionCode = null;

    /**
     * @var int $paymentOptionOccNumber
     */
    private $paymentOptionOccNumber = null;

    /**
     * @return string
     */
    public function getPaymentOptionCode()
    {
        return $this->paymentOptionCode;
    }

    /**
     * @param string $paymentOptionCode
     * @return ExtraResponse
     */
    public function setPaymentOptionCode($paymentOptionCode)
    {
        $this->paymentOptionCode = $paymentOptionCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentOptionOccNumber()
    {
        return $this->paymentOptionOccNumber;
    }

    /**
     * @param int $paymentOptionOccNumber
     * @return ExtraResponse
     */
    public function setPaymentOptionOccNumber($paymentOptionOccNumber)
    {
        $this->paymentOptionOccNumber = $paymentOptionOccNumber;
        return $this;
    }
}

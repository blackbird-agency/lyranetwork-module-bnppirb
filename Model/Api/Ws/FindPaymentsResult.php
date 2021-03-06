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

class FindPaymentsResult
{
    /**
     * @var CommonResponse $commonResponse
     */
    private $commonResponse = null;

    /**
     * @var OrderResponse $orderResponse
     */
    private $orderResponse = null;

    /**
     * @var TransactionItem $transactionItem
     */
    private $transactionItem = null;

    /**
     * @return CommonResponse
     */
    public function getCommonResponse()
    {
        return $this->commonResponse;
    }

    /**
     * @param CommonResponse $commonResponse
     * @return FindPaymentsResult
     */
    public function setCommonResponse($commonResponse)
    {
        $this->commonResponse = $commonResponse;
        return $this;
    }

    /**
     * @return OrderResponse
     */
    public function getOrderResponse()
    {
        return $this->orderResponse;
    }

    /**
     * @param OrderResponse $orderResponse
     * @return FindPaymentsResult
     */
    public function setOrderResponse($orderResponse)
    {
        $this->orderResponse = $orderResponse;
        return $this;
    }

    /**
     * @return TransactionItem
     */
    public function getTransactionItem()
    {
        return $this->transactionItem;
    }

    /**
     * @param TransactionItem $transactionItem
     * @return FindPaymentsResult
     */
    public function setTransactionItem($transactionItem)
    {
        $this->transactionItem = $transactionItem;
        return $this;
    }
}

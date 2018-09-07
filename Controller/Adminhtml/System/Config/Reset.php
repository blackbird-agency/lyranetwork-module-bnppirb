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
namespace Lyranetwork\Bnppirb\Controller\Adminhtml\System\Config;

/**
 * BNPP IRB admin configuraion controller.
 */
class Reset extends \Magento\Backend\App\AbstractAction
{
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $resourceConfig;

    /**
     * @var \Magento\Framework\Cache\FrontendInterface
     */
    protected $cache;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     * @param \Magento\Framework\Cache\FrontendInterface $cache
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Magento\Framework\Cache\FrontendInterface $cache
    ) {
        parent::__construct($context);

        $this->resourceConfig = $resourceConfig;
        $this->cache = $cache;
    }

    public function execute()
    {
        // retrieve write connection
        $connection = $this->resourceConfig->getConnection();

        // get config_data table name & execute delete query
        $where = [];
        $where[] = $connection->quoteInto('path REGEXP ?', '^payment/bnppirb_[a-z0-9]+/[a-z0-9_]+$');
        $where[] = $connection->quoteInto('path REGEXP ?', '^bnppirb/general/[a-z0-9_]+$');

        $connection->delete($this->resourceConfig->getMainTable(), implode(' OR ', $where));

        // clear cache
        $this->cache->clean();

        $this->messageManager->addSuccess(
            __('The configuration of the BNPP IRB module has been successfully reset.')
        );

        // redirect to payment config editor
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath(
            'adminhtml/system_config/edit',
            ['section' => 'payment', '_nosid' => true]
        );
    }
}

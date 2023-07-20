<?php

declare(strict_types=1);

namespace Olieinikov\OrderData\Model\Observer;

use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Webapi\Rest\Request;
use Olieinikov\OrderData\Model\Client\Guzzle;
use Olieinikov\OrderData\Model\Helper\OrderData;
use Psr\Log\LoggerInterface as Logger;

class SendOrderData implements ObserverInterface
{
    /**
     * @param Logger $logger
     * @param OrderData $orderData
     * @param Guzzle $client
     */
    public function __construct(
        private readonly Logger    $logger,
        private readonly OrderData $orderData,
        private readonly Guzzle    $client
    )
    {
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        try {
            $order = $observer->getEvent()->getOrder();
            $orderData = $this->orderData->prepareData($order);
            $this->client->doRequest($orderData, Request::HTTP_METHOD_POST);
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }
    }
}

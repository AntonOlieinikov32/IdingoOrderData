<?php

declare(strict_types=1);

namespace Olieinikov\OrderData\Model;

use Magento\Framework\Serialize\Serializer\Serialize;
use Magento\Sales\Api\OrderRepositoryInterface;
use Olieinikov\OrderData\Api\OrderDataInterface;
use Olieinikov\OrderData\Model\Helper\OrderData as Helper;

class OrderData implements OrderDataInterface
{

    /**
     * @param Helper $data
     * @param OrderRepositoryInterface $orderRepository
     * @param Serialize $json
     */
    public function __construct(
        private readonly Helper                   $data,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly Serialize                $json
    )
    {
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function getData(int $orderId): string
    {
        $order = $this->orderRepository->get($orderId);
        $currentTime = time();
        $limitTime = strtotime($order->getCreatedAt()) + 36000;
        if ($currentTime > $limitTime) {
            return "Order Data expired";
        }
        $orderData = $this->data->prepareData($order);
        return $this->json->serialize($orderData);
    }
}

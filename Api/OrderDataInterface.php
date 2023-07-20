<?php

namespace Olieinikov\OrderData\Api;

interface OrderDataInterface
{

    /**
     * @param int $orderId
     * @return string
     */
    public function getData(int $orderId): string;
}

<?php

declare(strict_types=1);

namespace Olieinikov\OrderData\Model\Helper;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class OrderData
{
    /**
     * @param OrderInterface $order
     * @return array
     */
    public function prepareData(OrderInterface $order): array
    {
        $itemsArray = [];
        $items = $order->getItems();
        $shippingAddress = $order->getShippingAddress();

        if (null !== $items) {
            foreach ($items as $orderItem) {
                if ($orderItem->getParentItem()) {
                    // Don't process children here - we will process (or already have processed) them below
                    continue;
                }
                if ($orderItem->getHasChildren() && $orderItem->isShipSeparately()) {
                    foreach ($orderItem->getChildren() as $child) {
                        $itemsArray[] = $this->getItemData($child);
                    }
                } else {
                    // Ship together - count compound item as one solid
                    $itemsArray[] = $this->getItemData($orderItem);
                }
            }
        }

        return [
            "order_id" => $order->getIncrementId(), // there are no order_id yet
            "increment_id" => $order->getIncrementId(),
            "grand_total" => $order->getGrandTotal(),
            "subtotal" => $order->getSubtotal(),
            "shipping_amount" => $order->getShippingAmount(),
            "customer_email" => $order->getCustomerEmail(),
            "customer_firstname" => $order->getCustomerFirstname(),
            "customer_lastname" => $order->getCustomerLastname(),
            "shipping_address" => [
                "city" => $shippingAddress->getCity(),
                "country" => $shippingAddress->getCountryId(),
                "street" => $shippingAddress->getStreet(),
                "region" => $shippingAddress->getRegionCode(),
                "zip" => $shippingAddress->getPostcode(),
            ],
            "items" => $itemsArray,
        ];
    }

    /**
     * @param OrderItemInterface $item
     * @return array
     */
    protected function getItemData(OrderItemInterface $item): array
    {
        return [
            "item_id" => $item->getProductId(),
            "product_name" => $item->getName(),
            "product_sku" => $item->getSku(),
            "qty" => $item->getQtyOrdered(),
            "price" => $item->getPrice(),
            "row_total" => $item->getRowTotal(),
            "discount" => $item->getDiscountAmount(),
        ];
    }

}

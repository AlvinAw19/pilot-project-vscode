<?php
declare(strict_types=1);

namespace App\Mailer;

use Cake\Mailer\Mailer;

/**
 * Order Mailer
 *
 * Handles email notifications for order-related events
 */
class OrderMailer extends Mailer
{
    /**
     * Send order confirmation email to buyer
     *
     * @param \App\Model\Entity\Order $order Order entity
     * @param \App\Model\Entity\User $buyer Buyer entity
     * @return void
     */
    public function buyerOrderConfirmation($order, $buyer)
    {
        $this
            ->setTo($buyer->email)
            ->setSubject('Order Confirmation #' . $order->id)
            ->setEmailFormat('both')
            ->setViewVars([
                'order' => $order,
                'buyer' => $buyer,
            ])
            ->viewBuilder()
            ->setTemplate('buyer_order_confirmation');
    }

    /**
     * Send new order notification email to seller
     *
     * @param \App\Model\Entity\OrderItem $orderItem Order item entity
     * @param \App\Model\Entity\User $seller Seller entity
     * @return void
     */
    public function sellerOrderNotification($orderItem, $seller)
    {
        $this
            ->setTo($seller->email)
            ->setSubject('New Order Received - Item #' . $orderItem->id)
            ->setEmailFormat('both')
            ->setViewVars([
                'orderItem' => $orderItem,
                'seller' => $seller,
            ])
            ->viewBuilder()
            ->setTemplate('seller_order_notification');
    }

    /**
     * Send order status update email to buyer
     *
     * @param \App\Model\Entity\Order $order Order entity
     * @param \App\Model\Entity\User $buyer Buyer entity
     * @return void
     */
    public function orderStatusUpdated($order, $buyer)
    {
        $this
            ->setTo($buyer->email)
            ->setSubject('Order Status Updated #' . $order->id)
            ->setEmailFormat('both')
            ->setViewVars([
                'order' => $order,
                'buyer' => $buyer,
            ])
            ->viewBuilder()
            ->setTemplate('order_status_updated');
    }
}

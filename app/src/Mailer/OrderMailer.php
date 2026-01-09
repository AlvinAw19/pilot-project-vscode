<?php
declare(strict_types=1);

namespace App\Mailer;

use Cake\Mailer\Mailer;

/**
 * Order Mailer
 */
class OrderMailer extends Mailer
{
    /**
     * Send order confirmation email to buyer
     *
     * @param \App\Model\Entity\Order $order Order entity
     * @return void
     */
    public function orderConfirmation($order)
    {
        $this
            ->setTo($order->user->email, $order->user->name)
            ->setSubject('Order Confirmation - Order #' . $order->id)
            ->setEmailFormat('both')
            ->viewBuilder()
            ->setTemplate('order_confirmation')
            ->setLayout('default')
            ->setVar('order', $order);
        $this->deliver();
    }

    /**
     * Send new order notification to seller
     *
     * @param \App\Model\Entity\OrderItem $orderItem Order item entity
     * @return void
     */
    public function sellerNotification($orderItem)
    {
        $this
            ->setTo($orderItem->product->user->email, $orderItem->product->user->name)
            ->setSubject('New Order - Order #' . $orderItem->order_id)
            ->setEmailFormat('both')
            ->viewBuilder()
            ->setTemplate('seller_notification')
            ->setLayout('default')
            ->setVar('orderItem', $orderItem);
        $this->deliver();
    }

    /**
     * Send delivery status update email to buyer
     *
     * @param \App\Model\Entity\OrderItem $orderItem Order item entity
     * @return void
     */
    public function deliveryStatusUpdate($orderItem)
    {
        $this
            ->setTo($orderItem->order->user->email, $orderItem->order->user->name)
            ->setSubject('Delivery Status Update - Order #' . $orderItem->order_id)
            ->setEmailFormat('both')
            ->viewBuilder()
            ->setTemplate('delivery_status_update')
            ->setLayout('default')
            ->setVar('orderItem', $orderItem);
        $this->deliver();
    }
}

<?php
declare(strict_types=1);

namespace App\Controller\Component;

use App\Model\Entity\Order;
use App\Model\Entity\OrderItem;
use Cake\Controller\Component;
use Cake\Mailer\Mailer;
use Cake\Log\Log;

/**
 * OrderMailer Component
 *
 * Centralized email logic for order-related notifications
 */
class OrderMailerComponent extends Component
{
    /**
     * Send order confirmation email to buyer
     *
     * @param \App\Model\Entity\Order $order Order entity with buyer and order_items
     * @return bool Success status
     */
    public function sendOrderConfirmation(Order $order): bool
    {
        if (!$order->buyer || !$order->buyer->email) {
            Log::warning('Cannot send order confirmation: buyer email not available');
            return false;
        }

        try {
            $mailer = new Mailer('default');
            $mailer
                ->setFrom(['noreply@example.com' => 'E-Commerce Platform'])
                ->setTo($order->buyer->email)
                ->setSubject('Order Confirmation - Order #' . $order->id)
                ->setEmailFormat('both')
                ->setViewVars([
                    'order' => $order,
                    'buyer' => $order->buyer,
                    'orderItems' => $order->order_items,
                ])
                ->viewBuilder()
                    ->setTemplate('order_confirmation');

            $mailer->deliver();

            Log::info('Order confirmation email sent to: ' . $order->buyer->email);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send new order notification to sellers
     *
     * @param iterable $orderItems Collection of order items
     * @param \App\Model\Entity\Order $order The order entity
     * @return bool Success status
     */
    public function sendSellerNotification(iterable $orderItems, $order = null): bool
    {
        // Group order items by seller
        $sellerOrders = [];
        foreach ($orderItems as $item) {
            if (!$item->product || !$item->product->user) {
                continue;
            }

            $sellerId = $item->product->seller_id;
            if (!isset($sellerOrders[$sellerId])) {
                $sellerOrders[$sellerId] = [
                    'seller' => $item->product->user,
                    'items' => [],
                    'order' => $order ?? $item->order,
                ];
            }
            $sellerOrders[$sellerId]['items'][] = $item;
        }

        // Send email to each seller
        $allSuccess = true;
        foreach ($sellerOrders as $sellerData) {
            if (!isset($sellerData['seller']) || !$sellerData['seller']) {
                Log::warning('Cannot send seller notification: seller not available');
                $allSuccess = false;
                continue;
            }

            if (!$sellerData['seller']->email) {
                Log::warning('Cannot send seller notification: email not available for seller ID ' . ($sellerData['seller']->id ?? 'unknown'));
                $allSuccess = false;
                continue;
            }

            if (!isset($sellerData['order']) || !$sellerData['order']) {
                Log::warning('Cannot send seller notification: order not available');
                $allSuccess = false;
                continue;
            }

            try {
                $mailer = new Mailer('default');
                $mailer
                    ->setFrom(['noreply@example.com' => 'E-Commerce Platform'])
                    ->setTo($sellerData['seller']->email)
                    ->setSubject('New Order Received - Order #' . ($sellerData['order']->id ?? 'N/A'))
                    ->setEmailFormat('both')
                    ->setViewVars([
                        'seller' => $sellerData['seller'],
                        'orderItems' => $sellerData['items'],
                        'order' => $sellerData['order'],
                    ])
                    ->viewBuilder()
                        ->setTemplate('seller_notification');

                $mailer->deliver();

                Log::info('Seller notification email sent to: ' . $sellerData['seller']->email);
            } catch (\Exception $e) {
                Log::error('Failed to send seller notification email: ' . $e->getMessage());
                $allSuccess = false;
            }
        }

        return $allSuccess;
    }

    /**
     * Send delivery status update notification to buyer
     *
     * @param \App\Model\Entity\OrderItem $orderItem Order item with order and buyer
     * @return bool Success status
     */
    public function sendDeliveryStatusUpdate(OrderItem $orderItem): bool
    {
        if (!$orderItem->order || !$orderItem->order->buyer || !$orderItem->order->buyer->email) {
            Log::warning('Cannot send delivery status update: buyer email not available');
            return false;
        }

        try {
            $mailer = new Mailer('default');
            $mailer
                ->setFrom(['noreply@example.com' => 'E-Commerce Platform'])
                ->setTo($orderItem->order->buyer->email)
                ->setSubject('Delivery Status Update - Order #' . $orderItem->order->id)
                ->setEmailFormat('both')
                ->setViewVars([
                    'orderItem' => $orderItem,
                    'order' => $orderItem->order,
                    'buyer' => $orderItem->order->buyer,
                    'product' => $orderItem->product,
                ])
                ->viewBuilder()
                    ->setTemplate('delivery_status_notification');

            $mailer->deliver();

            Log::info('Delivery status update email sent to: ' . $orderItem->order->buyer->email);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send delivery status update email: ' . $e->getMessage());
            return false;
        }
    }
}

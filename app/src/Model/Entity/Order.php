<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Order Entity
 *
 * @property int $id
 * @property int $buyer_id
 * @property string $total_amount
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $buyer
 * @property \App\Model\Entity\OrderItem[] $order_items
 * @property \App\Model\Entity\Payment $payment
 */
class Order extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'buyer_id' => true,
        'total_amount' => true,
        'created' => true,
        'modified' => true,
        'buyer' => true,
        'order_items' => true,
        'payment' => true,
    ];
}

<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Review Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $order_item_id
 * @property int $rating
 * @property string|null $comment
 * @property string|null $image_link
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\OrderItem $order_item
 */
class Review extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'user_id' => true,
        'product_id' => true,
        'order_item_id' => true,
        'rating' => true,
        'comment' => true,
        'image_link' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'product' => true,
        'order_item' => true,
    ];
}

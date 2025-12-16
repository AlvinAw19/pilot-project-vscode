<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property int $id
 * @property int $category_id
 * @property int $seller_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $image_link
 * @property int $stock
 * @property string $price
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 *
 * @property \App\Model\Entity\Category $category
 * @property \App\Model\Entity\User $user
 */
class Product extends Entity
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
        'category_id' => true,
        'seller_id' => true,
        'name' => true,
        'slug' => true,
        'description' => true,
        'image_link' => true,
        'stock' => true,
        'price' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'category' => true,
        'user' => true,
    ];
}

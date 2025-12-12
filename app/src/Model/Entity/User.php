<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

/**
 * User Entity
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $address
 * @property string|null $description
 * @property string $role
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $deleted
 */
class User extends Entity
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
        'name' => true,
        'email' => true,
        'password' => true,
        'address' => true,
        'description' => true,
        'role' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    /**
     * User role constants
     */
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SELLER = 'seller';
    public const ROLE_BUYER = 'buyer';

    /**
     * Get all available user roles as an associative array.
     *
     * Returns an array where keys are the role values stored in the database
     * and values are the human-readable labels for display in forms.
     *
     * @return array<string, string> Array of role values => labels
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_SELLER => 'Seller',
            self::ROLE_BUYER => 'Buyer',
        ];
    }

    /**
     * Mutator for password to hash it.
     *
     * @param string $password Password to hash.
     * @return string|null
     */
    protected function _setPassword(string $password): ?string
    {
        if (strlen($password) > 0) {
            $hashed = (new DefaultPasswordHasher())->hash($password);
            return $hashed ?: null;
        }
        return null;
    }
}

<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\FrozenTime;
use Cake\Log\Log;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Exception;

/**
 * Users Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\HasMany $Products
 * @property \App\Model\Table\CartItemsTable&\Cake\ORM\Association\HasMany $CartItems
 * @property \App\Model\Table\OrderItemsTable&\Cake\ORM\Association\HasMany $OrderItems
 * @property \App\Model\Table\LogsTable&\Cake\ORM\Association\HasMany $Logs
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->addBehavior('Muffin/Trash.Trash');

        $this->hasMany('Products');
        $this->hasMany('CartItems', ['foreignKey' => 'buyer_id']);
        $this->hasMany('Orders', ['foreignKey' => 'buyer_id']);
        $this->hasMany('Logs', ['foreignKey' => 'user_id']);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->allowEmptyString('password');

        $validator
            ->scalar('address')
            ->allowEmptyString('address');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('role')
            ->maxLength('role', 255)
            ->requirePresence('role', 'create')
            ->notEmptyString('role');

        $validator
            ->scalar('google_id')
            ->maxLength('google_id', 255)
            ->allowEmptyString('google_id');

        $validator
            ->scalar('provider')
            ->maxLength('provider', 255)
            ->allowEmptyString('provider');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        $validator
            ->dateTime('created')
            ->notEmptyDateTime('created');

        $validator
            ->dateTime('modified')
            ->allowEmptyDateTime('modified');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }

    /**
     * Generate a password reset token for the user with the given email.
     *
     * @param string $email The user's email.
     * @return string|null The generated token, or null if user not found.
     */
    public function generateResetToken(string $email): ?string
    {
        /** @var \App\Model\Entity\User|null $user */
        $user = $this->find()
            ->where(['email' => $email])
            ->first();

        if (!$user) {
            return null;
        }

        $token = bin2hex(random_bytes(32)); // generate random token
        $expiry = FrozenTime::now()->addHour(); // Expire in 1 hour

        $user->password_reset_token = $token;
        $user->password_reset_token_expiry = $expiry;

        try {
            $this->saveOrFail($user);
        } catch (Exception $e) {
            Log::error('Failed to save password reset token for user ' . $user->id . ': ' . $e->getMessage());

            return null;
        }

        return $token;
    }

    /**
     * Validate and reset the password using the token.
     *
     * @param string $token The reset token.
     * @param string $newPassword The new password.
     * @return bool True if reset successful, false otherwise.
     */
    public function resetPassword(string $token, string $newPassword): bool
    {
        /** @var \App\Model\Entity\User|null $user */
        $user = $this->find()
            ->where(['password_reset_token' => $token])
            ->first();

        // Invalid or expired token
        if (
            $user === null ||
            $user->password_reset_token_expiry === null ||
            $user->password_reset_token_expiry < FrozenTime::now()
        ) {
            return false;
        }

        $user->password = $newPassword;
        $user->password_reset_token = null;
        $user->password_reset_token_expiry = null;

        try {
            $this->saveOrFail($user);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to reset password for user ' . $user->id . ': ' . $e->getMessage());

            return false;
        }
    }
}

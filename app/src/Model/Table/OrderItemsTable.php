<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\OrderItem;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OrderItems Model
 *
 * @property \App\Model\Table\OrdersTable&\Cake\ORM\Association\BelongsTo $Orders
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsTo $Products
 * @method \App\Model\Entity\OrderItem newEmptyEntity()
 * @method \App\Model\Entity\OrderItem newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\OrderItem[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrderItem get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrderItem findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\OrderItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrderItem[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrderItem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderItem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrderItem[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrderItem[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrderItem[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\OrderItem[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrderItemsTable extends Table
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

        $this->setTable('order_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Orders', [
            'foreignKey' => 'order_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER',
        ]);
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
            ->integer('order_id')
            ->notEmptyString('order_id');

        $validator
            ->integer('product_id')
            ->notEmptyString('product_id');

        $validator
            ->decimal('price')
            ->requirePresence('price', 'create')
            ->notEmptyString('price')
            ->greaterThan('price', 0);

        $validator
            ->integer('quantity')
            ->requirePresence('quantity', 'create')
            ->notEmptyString('quantity')
            ->greaterThan('quantity', 0);

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount')
            ->greaterThanOrEqual('amount', 0);

        $validator
            ->scalar('delivery_status')
            ->maxLength('delivery_status', 50)
            ->notEmptyString('delivery_status')
            ->inList('delivery_status', [
                OrderItem::STATUS_PENDING,
                OrderItem::STATUS_DELIVERING,
                OrderItem::STATUS_DELIVERED,
                OrderItem::STATUS_CANCELED,
            ]);

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
        $rules->add($rules->existsIn('order_id', 'Orders'), ['errorField' => 'order_id']);
        $rules->add($rules->existsIn('product_id', 'Products'), ['errorField' => 'product_id']);

        return $rules;
    }

    /**
     * Custom finder for order items by seller
     *
     * @param \Cake\ORM\Query $query
     * @param array<string, mixed> $options
     * @return \Cake\ORM\Query
     */
    public function findBySeller($query, $options)
    {
        $sellerId = $options['seller_id'] ?? null;
        if (!$sellerId) {
            throw new \InvalidArgumentException('seller_id is required');
        }

        return $query->contain(['Orders' => ['Buyers'], 'Products'])
            ->matching('Products', function ($q) use ($sellerId) {
                return $q->where(['Products.seller_id' => $sellerId]);
            })
            ->order(['OrderItems.created' => 'DESC']);
    }

    /**
     * Custom finder for order items by delivery status
     *
     * @param \Cake\ORM\Query $query
     * @param array<string, mixed> $options
     * @return \Cake\ORM\Query
     */
    public function findByDeliveryStatus($query, $options)
    {
        $status = $options['status'] ?? null;
        if (!$status) {
            throw new \InvalidArgumentException('status is required');
        }

        return $query->where(['OrderItems.delivery_status' => $status]);
    }
}

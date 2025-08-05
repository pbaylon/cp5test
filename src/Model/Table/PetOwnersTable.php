<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PetOwners Model
 *
 * @property \App\Model\Table\PetsTable&\Cake\ORM\Association\BelongsTo $Pets
 * @property \App\Model\Table\ClientsTable&\Cake\ORM\Association\BelongsTo $Clients
 *
 * @method \App\Model\Entity\PetOwner newEmptyEntity()
 * @method \App\Model\Entity\PetOwner newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PetOwner> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PetOwner get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PetOwner findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PetOwner patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PetOwner> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PetOwner|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PetOwner saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PetOwner>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PetOwner>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PetOwner>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PetOwner> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PetOwner>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PetOwner>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PetOwner>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PetOwner> deleteManyOrFail(iterable $entities, array $options = [])
 */
class PetOwnersTable extends Table
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

        $this->setTable('pet_owners');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Pets', [
            'foreignKey' => 'pet_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER',
        ]);
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_on' => 'new',
                    'modified_on' => 'always',
                ],
            ],
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
            ->integer('pet_id')
            ->notEmptyString('pet_id');

        $validator
            ->integer('client_id')
            ->notEmptyString('client_id');

        $validator
            ->boolean('is_active')
            ->allowEmptyString('is_active');

        $validator
            ->integer('created_by')
            ->allowEmptyString('created_by');

        $validator
            ->dateTime('created_on')
            ->notEmptyDateTime('created_on');

        $validator
            ->dateTime('modified_on')
            ->notEmptyDateTime('modified_on');

        $validator
            ->integer('deleted_by')
            ->allowEmptyString('deleted_by');

        $validator
            ->dateTime('deleted_on')
            ->allowEmptyDateTime('deleted_on');

        $validator
            ->boolean('is_deleted')
            ->allowEmptyString('is_deleted');

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
        $rules->add($rules->existsIn(['pet_id'], 'Pets'), ['errorField' => 'pet_id']);
        $rules->add($rules->existsIn(['client_id'], 'Clients'), ['errorField' => 'client_id']);

        return $rules;
    }
}

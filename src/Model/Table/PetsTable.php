<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pets Model
 *
 * @property \App\Model\Table\ClientsTable&\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\BreedsTable&\Cake\ORM\Association\BelongsTo $Breeds
 * @property \App\Model\Table\PetOwnersTable&\Cake\ORM\Association\HasMany $PetOwners
 *
 * @method \App\Model\Entity\Pet newEmptyEntity()
 * @method \App\Model\Entity\Pet newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Pet> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Pet get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Pet findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Pet patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Pet> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Pet|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Pet saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Pet>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pet>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pet>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pet> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pet>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pet>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Pet>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Pet> deleteManyOrFail(iterable $entities, array $options = [])
 */
class PetsTable extends Table
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

        $this->setTable('pets');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Breeds', [
            'foreignKey' => 'breed_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('PetOwners', [
            'foreignKey' => 'pet_id',
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
            ->integer('client_id')
            ->notEmptyString('client_id');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('species')
            ->maxLength('species', 255)
            ->requirePresence('species', 'create')
            ->notEmptyString('species');

        $validator
            ->scalar('gender')
            ->allowEmptyString('gender');

        $validator
            ->date('dob')
            ->requirePresence('dob', 'create')
            ->notEmptyDate('dob');

        $validator
            ->integer('breed_id')
            ->notEmptyString('breed_id');

        $validator
            ->scalar('gents')
            ->maxLength('gents', 255)
            ->allowEmptyString('gents');

        $validator
            ->allowEmptyString('status');

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
            ->boolean('is_deleted')
            ->allowEmptyString('is_deleted');

        $validator
            ->integer('deleted_by')
            ->allowEmptyString('deleted_by');

        $validator
            ->dateTime('deleted_on')
            ->allowEmptyDateTime('deleted_on');

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
        $rules->add($rules->existsIn(['client_id'], 'Clients'), ['errorField' => 'client_id']);
        $rules->add($rules->existsIn(['breed_id'], 'Breeds'), ['errorField' => 'breed_id']);

        return $rules;
    }
}

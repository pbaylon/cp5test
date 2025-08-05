<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PetRecords Model
 *
 * @property \App\Model\Table\PetsTable&\Cake\ORM\Association\BelongsTo $Pets
 *
 * @method \App\Model\Entity\PetRecord newEmptyEntity()
 * @method \App\Model\Entity\PetRecord newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PetRecord> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PetRecord get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PetRecord findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PetRecord patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PetRecord> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PetRecord|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PetRecord saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PetRecord>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PetRecord>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PetRecord>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PetRecord> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PetRecord>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PetRecord>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PetRecord>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PetRecord> deleteManyOrFail(iterable $entities, array $options = [])
 */
class PetRecordsTable extends Table
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

        $this->setTable('pet_records');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Pets', [
            'foreignKey' => 'pet_id',
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
            ->integer('pet_id')
            ->notEmptyString('pet_id');

        $validator
            ->integer('type')
            ->allowEmptyString('type');

        $validator
            ->date('date')
            ->allowEmptyDate('date');

        $validator
            ->scalar('remarks')
            ->maxLength('remarks', 50)
            ->allowEmptyString('remarks');

        $validator
            ->integer('vet_id')
            ->allowEmptyString('vet_id');

        $validator
            ->scalar('details')
            ->maxLength('details', 255)
            ->allowEmptyString('details');

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
        $rules->add($rules->existsIn(['pet_id'], 'Pets'), ['errorField' => 'pet_id']);

        return $rules;
    }
}

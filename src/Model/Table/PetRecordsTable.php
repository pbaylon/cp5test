<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\EntityInterface;
use DateTimeImmutable;
use DateTimeZone;

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

      public function fetchAllActivePetRecords(): array
    {
        return $this->find()
        ->whereNull('deleted_on')
        ->orderBy(['modified_on' => 'DESC'])
        ->toArray();
    }
    public function fetchPetRecordById(int $id): ?EntityInterface
    {
        try {
            $petRecord = $this->get($id);
            return $petRecord->deleted_on === null ? $petRecord : null;
        } catch (RecordNotFoundException) {
            return null;
        }
    }
    public function createPetRecord(array $data): array
    {
        $data['is_active'] = true;
        $petRecord = $this->newEntity($data);
        $petRecord = $this->patchEntity($petRecord, $data);

        if ($this->save($petRecord)) {
            return [
                'success' => true,
                'message' => 'Pet Record created successfully.',
                'data' => $petRecord
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to create Pet Record.',
            'errors' => $petRecord->getErrors()
        ];
    }
    public function updatePetRecord(int $id, array $data): array
    {
        try {
            $petRecord = $this->get($id);

            if ($petRecord->deleted_on !== null) {
                return [
                    'success' => false,
                    'message' => 'Cannot update a deleted Pet Record.',
                ];
            }

            $petRecord = $this->patchEntity($petRecord, $data);
            $petRecord->modified_on = (new DateTimeImmutable('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');

            if ($this->save($petRecord)) {
                return [
                    'success' => true,
                    'message' => 'Pet Record updated successfully.',
                    'data' => $petRecord
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to update Pet Record.',
                'errors' => $petRecord->getErrors()
            ];

        } catch (RecordNotFoundException $error) {
            return [
                'success' => false,
                'message' => 'Pet Record not found.',
                'error' => $error->getMessage()
            ];
        }
    }
    public function softDeletePetRecord(int $id): array
    {
        try {
            $petRecord = $this->get($id);

            if ($petRecord->deleted_on !== null) {
                return [
                    'success' => false,
                    'message' => 'Pet Record already deleted.'
                ];
            }

            $petRecord->deleted_on = (new DateTimeImmutable('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');
            $petRecord->is_deleted = true;

            if ($this->save($petRecord)) {
                return [
                    'success' => true,
                    'message' => 'Pet Record deleted successfully.',
                    'data' => $petRecord
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to delete pet Record.',
                'errors' => $petRecord->getErrors()
            ];
        } catch(RecordNotFoundException $error) {
            return [
                'success' => false,
                'message' => 'Pet Record not found.',
                'error' => $error->getMessage()
            ];
        }
    }
}

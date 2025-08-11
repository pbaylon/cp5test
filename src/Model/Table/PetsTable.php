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
 * Pets Model
 *
 * @property \App\Model\Table\ClientsTable&\Cake\ORM\Association\BelongsTo $Clients
 * @property \App\Model\Table\BreedsTable&\Cake\ORM\Association\BelongsTo $Breeds
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
        $this->hasMany('PetRecords', [
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

    public function fetchAllActivePets(): array
    {
        return $this->find()
            ->whereNull('deleted_on')
            ->orderBy(['modified_on' => 'DESC'])
            ->toArray();
    }
    //GET PETS BY ID (IF NOT DELETED)
    public function fetchPetsById(int $id): ?EntityInterface
    {
        try {
            $pet = $this->get($id);
            return $pet->deleted_on === null ? $pet : null;
        } catch (RecordNotFoundException) {
            return null;
        }
    }

    //CREATE PET
    public function createPet(array $data): array
    {
        $data['is_active'] = true;

        $pet = $this->newEmptyEntity();
        $pet = $this->patchEntity($pet, $data);

        if ($this->save($pet)) {
            return [
                'success' => true,
                'message' => 'Pet created successfully.',
                'pet' => $pet,
            ];
        }

        return [
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $pet->getErrors(),
        ];
    }

    //UPDATE PET
    public function updatePet(int $id, array $data): array
    {
        try {
            $pet = $this->get($id);

            if ($pet->deleted_on !== null) {
                return [
                    'success' => false,
                    'message' => 'Pet is already deleted.'
                ];
            }

            $pet = $this->patchEntity($pet, $data);
            $pet->modified_on = (new DateTimeImmutable('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');

            if ($this->save($pet)) {
                return [
                    'success' => true,
                    'message' => 'Pet updated successfully.',
                    'pet' => $pet,
                ];
            }

            return [
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $pet->getErrors(),
            ];
        } catch (RecordNotFoundException $error) {
            return [
                'success' => false,
                'message' => 'Pet not found.',
                'error'=> $error->getMessage(),
            ];
        }
    }

    //SOFT DELETE PET
    public function softDeletePet(int $id): array
    {
        try {
            $pet = $this->get($id);

            if ($pet->deleted_on !== null) {
                return [
                    'success' => false,
                    'message' => 'Pet already deleted.'
                ];
            }

            $pet->deleted_on = (new DateTimeImmutable('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');

            if ($this->save($pet)) {
                return [
                    'success' => true,
                    'message' => 'Pet deleted successfully.',
                    'pet' => $pet
                ];
            }

            return [
                'success' => false, 
                'message' => 'Failed to delete pet.'
            ];
        } catch (RecordNotFoundException $error) {
            return [
                'success' => false,
                'message' => 'Pet not found.',
                'errors' => $error->getMessage(),
            ];
        }
    }
}

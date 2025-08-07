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
 * Breeds Model
 *
 * @method \App\Model\Entity\Breed newEmptyEntity()
 * @method \App\Model\Entity\Breed newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Breed> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Breed get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Breed findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Breed patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Breed> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Breed|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Breed saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Breed>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Breed>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Breed>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Breed> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Breed>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Breed>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Breed>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Breed> deleteManyOrFail(iterable $entities, array $options = [])
 */
class BreedsTable extends Table
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

        $this->setTable('breeds');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_on' => 'new',
                    'modified_on' => 'always',
                ],
            ],
        ]);
          $this->hasMany('Pets', [
            'foreignKey' => 'breed_id',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->allowEmptyString('is_active');

        $validator
            ->allowEmptyString('is_cat');

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

    //GET ALL ACTIVE BREEDS
    public function fetchAllActiveBreeds(): array
    {
        return $this->find()
            ->whereNull('deleted_on')
            ->orderBy(['modified_on' => 'DESC'])
            ->toArray();
    }

    //GET BREED BY ID (IF NOT DELETED)
    public function fetchBreedById(int $id): ?EntityInterface
    {
        try {
            $breed = $this->get($id);
            return $breed->is_deleted === null ? $breed : null;
        } catch (RecordNotFoundException $e) {
            return null;
        }
    }

    //CREATE BREED
    public function createBreed(array $data): array
    {
        $breed = $this->newEmptyEntity();
        $breed = $this->patchEntity($breed, $data);
        $data['is_active'] = true;

        if ($this->save($breed)) {
            return [
                'success' => true,
                'message' => 'Breed created successfully.',
                'breed' => $breed,
            ];
        }
            return [
                'success'=> false,
                'message' => 'Failed to create breed. Please check the data and try again.',
                'errors' => $breed->getErrors(),
            ];
    }

    //UPDATE BREED
    public function updateBreed(int $id, array $data): array
    {
        try {
            $breed = $this->get($id);

            if ($breed->deleted_on !== null) {
                return [
                    'success' => false,
                    'message' => 'Cannot update a deleted breed.',
                ];
            }

            $breed = $this->patchEntity($breed, $data);
            $breed->modified_on = (new DateTimeImmutable('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');

            if ($this->save($breed)) {
                return [
                    'success' => true,
                    'message' => 'Breed updated successfully.',
                    'breed' => $breed,
                ];
            }

            return [
                'success'=> false,
                'message'=> 'Failed to update breed. Please check the data and try again.',
                'errors' => $breed->getErrors(),
            ];
        } catch (RecordNotFoundException $error) {
            return [
                'success' => false,
                'message' => 'Breed not found.',
                'error' => $error->getMessage(),
            ];
        }
    }

    //SOFT DELETE BREED
    public function softDeleteBreed(int $id): array
    {
        try {
            $breed = $this->get($id);

            if ($breed->deleted_on !== null) {
                return [
                    'success' => false,
                    'message' => 'Breed is already deleted.',
                ];
            }

            $breed->is_deleted = true;
            $breed->deleted_on = (new DateTimeImmutable('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');

            if ($this->save($breed)) {
                return [
                    'success' => true,
                    'message' => 'Breed deleted successfully.',
                    'breed' => $breed,
                ];
            }
            return [
                'success' => false,
                'message' => 'Failed to delete breed.',
            ];
        } catch (RecordNotFoundException $error) {
            return [
                'success' => false,
                'message' => 'Breed not found.',
                'error' => $error->getMessage(),
            ];
        }
    }
}

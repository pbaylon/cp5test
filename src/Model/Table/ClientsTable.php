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
 * Clients Model
 *
 * @method \App\Model\Entity\Client newEmptyEntity()
 * @method \App\Model\Entity\Client newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Client> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Client get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Client findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Client patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Client> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Client|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Client saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Client>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Client>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Client>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Client> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Client>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Client>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Client>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Client> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ClientsTable extends Table
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

        $this->setTable('clients');
        $this->setDisplayField('fname');
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
            'foreignKey' => 'client_id',
        ]);
        $this->hasMany('PetOwners', [
            'foreignKey' => 'client_id',
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
            ->scalar('fname')
            ->maxLength('fname', 255)
            ->requirePresence('fname', 'create')
            ->notEmptyString('fname');

        $validator
            ->scalar('mname')
            ->maxLength('mname', 255)
            ->allowEmptyString('mname');

        $validator
            ->scalar('lname')
            ->maxLength('lname', 255)
            ->requirePresence('lname', 'create')
            ->notEmptyString('lname');

        $validator
            ->requirePresence('phone_number', 'create')
            ->notEmptyString('phone_number');

        $validator
            ->allowEmptyString('phone_number2');

        $validator
            ->allowEmptyString('is_new');

        $validator
            ->allowEmptyString('is_member');

        $validator
            ->allowEmptyString('is_vip');

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

        $validator
            ->integer('cnd_pts')
            ->allowEmptyString('cnd_pts');

        $validator
            ->dateTime('created_on')
            ->notEmptyDateTime('created_on');

        $validator
            ->dateTime('modified_on')
            ->notEmptyDateTime('modified_on');

        $validator
            ->dateTime('deleted_on')
            ->allowEmptyDateTime('deleted_on');

        return $validator;
    }
       //GET ALL ACTIVE CLIENTS
    public function fetchAllActiveClients(): array
    {
        return $this->find()
            ->whereNull('deleted_on')
            ->orderBy(['modified_on' => 'DESC'])
            ->toArray();
    }
    //GET CLIENT BY ID (IF NOT DELETED)
    public function fetchClientById(int $id): ?EntityInterface
    {
        try {
            $client = $this->get($id);
            return $client->deleted_on === null ? $client : null;
        } catch (RecordNotFoundException) {
            return null;
        }
    }

    //CREATE CLIENT
    public function createClient(array $data): array
    {
        $data['is_active'] = true;

        $client = $this->newEmptyEntity();
        $client = $this->patchEntity($client, $data);

        if ($this->save($client)) {
            return [
                'success' => true,
                'message' => 'Client created successfully.',
                'client' => $client,
            ];
        }

        return [
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $client->getErrors(),
        ];
    }

    //UPDATE CLIENT
    public function updateClient(int $id, array $data): array
    {
        try {
            $client = $this->get($id);

            if ($client->deleted_on !== null) {
                return [
                    'success' => false,
                    'message' => 'Client is already deleted.'
                ];
            }

            $client = $this->patchEntity($client, $data);
            $client->modified_on = (new DateTimeImmutable('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');

            if ($this->save($client)) {
                return [
                    'success' => true,
                    'message' => 'Client updated successfully.',
                    'client' => $client,
                ];
            }

            return [
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $client->getErrors(),
            ];
        } catch (RecordNotFoundException $error) {
            return [
                'success' => false,
                'message' => 'Client not found.',
                'error'=> $error->getMessage(),
            ];
        }
    }

    //SOFT DELETE CLIENT
    public function softDeleteClient(int $id): array
    {
        try {
            $client = $this->get($id);

            if ($client->deleted_on !== null) {
                return [
                    'success' => false,
                    'message' => 'Client already deleted.'
                ];
            }

            $client->deleted_on = (new DateTimeImmutable('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');

            if ($this->save($client)) {
                return [
                    'success' => true,
                    'message' => 'Client deleted successfully.',
                    'client' => $client
                ];
            }

            return [
                'success' => false, 
                'message' => 'Failed to delete client.'
            ];
        } catch (RecordNotFoundException $error) {
            return [
                'success' => false,
                'message' => 'Client not found.',
                'errors' => $error->getMessage(),
            ];
        }
    }
}

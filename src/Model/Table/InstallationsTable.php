<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Installations Model
 *
 * @method \App\Model\Entity\Installation newEmptyEntity()
 * @method \App\Model\Entity\Installation newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Installation> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Installation get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Installation findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Installation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Installation> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Installation|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Installation saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Installation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Installation>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Installation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Installation> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Installation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Installation>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Installation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Installation> deleteManyOrFail(iterable $entities, array $options = [])
 */
class InstallationsTable extends Table
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

        $this->setTable('installations');
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
            ->maxLength('name', 50)
            ->allowEmptyString('name');

        $validator
            ->scalar('tin')
            ->maxLength('tin', 15)
            ->allowEmptyString('tin');

        $validator
            ->scalar('series')
            ->maxLength('series', 5)
            ->allowEmptyString('series');

        $validator
            ->scalar('address')
            ->maxLength('address', 50)
            ->allowEmptyString('address');

        $validator
            ->scalar('printer_name')
            ->maxLength('printer_name', 50)
            ->allowEmptyString('printer_name');

        $validator
            ->scalar('printer_port')
            ->maxLength('printer_port', 50)
            ->allowEmptyString('printer_port');

        $validator
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
            ->allowEmptyString('deleted_by');

        $validator
            ->dateTime('deleted_on')
            ->allowEmptyDateTime('deleted_on');

        return $validator;
    }
}

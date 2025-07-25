<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PersonalTokens Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\PersonalToken newEmptyEntity()
 * @method \App\Model\Entity\PersonalToken newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PersonalToken> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PersonalToken get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PersonalToken findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PersonalToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PersonalToken> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PersonalToken|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PersonalToken saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PersonalToken>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PersonalToken>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PersonalToken>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PersonalToken> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PersonalToken>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PersonalToken>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PersonalToken>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PersonalToken> deleteManyOrFail(iterable $entities, array $options = [])
 */
class PersonalTokensTable extends Table
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

        $this->setTable('personal_tokens');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->requirePresence('token', 'create')
            ->notEmptyString('token');

        $validator
            ->dateTime('expires_at')
            ->requirePresence('expires_at', 'create')
            ->notEmptyDateTime('expires_at');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}

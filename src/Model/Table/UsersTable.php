<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Entity\User;

/**
 * Users Model
 *
 * @property \App\Model\Table\PersonalTokensTable&\Cake\ORM\Association\HasMany $PersonalTokens
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\User> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\User> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User> deleteManyOrFail(iterable $entities, array $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');

        $this->hasMany('PersonalTokens', [
            'foreignKey' => 'user_id',
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
            ->scalar('fname')
            ->maxLength('fname', 255)
            ->allowEmptyString('fname');

        $validator
            ->scalar('lname')
            ->maxLength('lname', 255)
            ->allowEmptyString('lname');

        $validator
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->notEmptyString('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->requirePresence('role', 'create')
            ->notEmptyString('role');

        $validator
            ->allowEmptyString('has_pic');

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }

      public function registerUser(array $data): array
    {
        $data['username'] = strtolower(trim($data['username']));
        $data['email'] = strtolower(trim($data['email']));
        $data['password'] = password_hash(trim($data['password']), PASSWORD_DEFAULT);
        $data['is_active'] = true;

        $user = $this->newEmptyEntity();
        $user = $this->patchEntity($user, $data);

        if ($user->hasErrors() || !$this->save($user)) {
            return [
                'status' => false,
                'message' => 'Failed to register user.',
                'errors' => $user->getErrors()
            ];
        }

        return [
            'status' => true,
            'user' => $user
        ];
    }

    public function authenticateUser(string $username, string $password): ?User
    {
        $user = $this->find()
            ->where(['username' => $username])
            ->first();

        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }

        if (!$user->is_active) {
            $user->is_active = true;
            $this->save($user);
        }

        return $user;
    }

    public function logoutUser(User $user, string $plainToken): void
    {
        $user->is_active = false;
        $this->save($user);

        $tokens = $this->getAssociation('PersonalTokens')->getTarget();
        $tokens->revokeToken($plainToken, $user->id);
    }

    public function getUserProfile(int $userId): ?User
    {
        return $this->find()
            ->select(['id', 'username', 'email', 'fname', 'lname', 'role', 'has_pic'])
            ->where(['id' => $userId])
            ->first();
    }
}

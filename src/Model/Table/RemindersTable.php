<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reminders Model
 *
 * @property \App\Model\Table\TasksTable&\Cake\ORM\Association\BelongsTo $Tasks
 *
 * @method \App\Model\Entity\Reminder newEmptyEntity()
 * @method \App\Model\Entity\Reminder newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Reminder> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Reminder get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Reminder findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Reminder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Reminder> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Reminder|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Reminder saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Reminder>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Reminder>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Reminder>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Reminder> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Reminder>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Reminder>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Reminder>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Reminder> deleteManyOrFail(iterable $entities, array $options = [])
 */
class RemindersTable extends Table
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

        $this->setTable('reminders');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Tasks', [
            'foreignKey' => 'task_id',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('task_id')
            ->notEmptyString('task_id');

        $validator
            ->dateTime('date')
            ->requirePresence('date', 'create')
            ->notEmptyDateTime('date');

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
        $rules->add($rules->existsIn(['task_id'], 'Tasks'), ['errorField' => 'task_id']);

        return $rules;
    }
}

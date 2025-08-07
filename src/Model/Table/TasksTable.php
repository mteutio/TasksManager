<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Task;
use Cake\Database\Query;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use function React\Promise\all;

/**
 * Tasks Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PrioritiesTable&\Cake\ORM\Association\BelongsTo $Priorities
 * @property \App\Model\Table\TasksTable&\Cake\ORM\Association\BelongsTo $ParentTasks
 * @property \App\Model\Table\NotesTable&\Cake\ORM\Association\HasMany $Notes
 * @property \App\Model\Table\RemindersTable&\Cake\ORM\Association\HasMany $Reminders
 * @property \App\Model\Table\SubtasksTable&\Cake\ORM\Association\HasMany $Subtasks
 * @property \App\Model\Table\TasksTable&\Cake\ORM\Association\HasMany $ChildTasks
 *
 * @method \App\Model\Entity\Task newEmptyEntity()
 * @method \App\Model\Entity\Task newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Task> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Task get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Task findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Task patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Task> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Task|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Task saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Task>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Task>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Task>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Task> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Task>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Task>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Task>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Task> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TasksTable extends Table
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

        $this->setTable('tasks');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Priorities', [
            'foreignKey' => 'priority_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('ParentTasks', [
            'className' => 'Tasks',
            'foreignKey' => 'parent_id',
             'propertyName' => 'parent_task',
        ]);
        $this->hasMany('Notes', [
            'foreignKey' => 'task_id',
            'dependent' => true,
            'cascadeCallbacks' => true, 
            'saveStrategy' => 'append', //permit me to add a new notes to the task form
        ]);
        $this->hasMany('Reminders', [
            'foreignKey' => 'task_id',
            'dependent' => true,
            'cascadeCallbacks' => true, 
            'saveStrategy' => 'append', //permit me to add a new reminders to the task form
        ]);
        $this->hasMany('ChildTasks', [
            'className' => 'Tasks',
            'foreignKey' => 'parent_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->belongsTo('AssignedByUser', [
            'className' => 'Users',
            'foreignKey' => 'user_id',
            'propertyName' => 'created_by_user', 
       ]);
       $this->belongsTo('AssignedToUser', [
            'className' => 'Users',
            'foreignKey' => 'assigned_to',
            'propertyName' => 'assigned_to_user'
        ]);
        $this->belongsTo('CreatedByUser', [
            'className' => 'Users',
            'foreignKey' => 'user_id',
            'propertyName' => 'created_by_user'
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

       /* $validator
            ->scalar('status')
            ->maxLength('status', 255)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');
*/
        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        $validator
            ->integer('priority_id')
            ->notEmptyString('priority_id');
        $validator
            ->boolean('deleted')
            ->notEmptyString('deleted');
        $validator
            ->dateTime('deleted_at')
            ->allowEmptyDateTime('deleted_at');
        return $validator;
    }

    public function findActive(Query $query,array $options) : Query {
            return $query->where(['deleted'=> false]);  
    }
     public function findDeleted(Query $query,array $options) : Query {
            return $query->where(['deleted'=> true]);  
    }
    public function moveToTrash($id)  {
        $task= $this->get($id);
        $task->deleted=true;
        $task->deleted_at=new \DateTime();
            return $this->save($task);  
    }
     public function restoreFromTrash($id)  {
        $task= $this->get($id);
        $task->deleted=false;
        $task->deleted_at=null;
            return $this->save($task);  
    }
    public function deleteForever($id)  {
        $task= $this->get($id);
            return $this->delete($task);  
    }
    public function emptyTrash($id)  {
        $task= $this->get($id);
            return $this->deleteAll(['deleted'=>true]);  
    }
     public function cleanOldDelete($daysOld=30)  {
        $cutoffdate= new \DateTime();
        $cutoffdate->modify("-{$daysOld} days");
            return $this->deleteAll(['deleted'=>true,
                                    'deleted_at <'=> $cutoffdate
                                               ]);  
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
        $rules->add($rules->existsIn(['priority_id'], 'Priorities'), ['errorField' => 'priority_id']);
        $rules->add($rules->existsIn(['parent_id'], 'ParentTasks'), ['errorField' => 'parent_id']);

        return $rules;
    }
}

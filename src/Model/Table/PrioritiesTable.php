<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Priorities Model
 *
 * @property \App\Model\Table\TasksTable&\Cake\ORM\Association\HasMany $Tasks
 *
 * @method \App\Model\Entity\Priority newEmptyEntity()
 * @method \App\Model\Entity\Priority newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Priority> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Priority get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Priority findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Priority patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Priority> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Priority|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Priority saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Priority>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Priority>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Priority>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Priority> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Priority>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Priority>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Priority>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Priority> deleteManyOrFail(iterable $entities, array $options = [])
 */
class PrioritiesTable extends Table
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

        $this->setTable('priorities');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->belongsTo('Users');


        $this->hasMany('Tasks', [
            'foreignKey' => 'priority_id',
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
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        return $validator;
    }
}

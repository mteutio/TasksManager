<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 * @property string $status
 * @property string $description
 * @property int $user_id
 * @property int $priority_id
 * @property int $parent_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Priority $priority
 * @property \App\Model\Entity\Task $parent_task
 * @property \App\Model\Entity\Note[] $notes
 * @property \App\Model\Entity\Reminder[] $reminders
 * @property \App\Model\Entity\Subtask[] $subtasks
 * @property \App\Model\Entity\Task[] $child_tasks
 */
class Task extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
    'user_id' => true,
    'priority_id' => true,
    'created' => true,
    'modified' => true,
    '*'=>true,
        'id'=>false,
    ];
}

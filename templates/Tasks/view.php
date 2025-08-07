<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Task'), ['action' => 'edit', $task->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Task'), ['action' => 'delete', $task->id], ['confirm' => __('Are you sure you want to delete # {0}?', $task->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Tasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Task'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tasks view content">
            <h3><?= h($task->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= h($task->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Priority') ?></th>
                    <td><?= h($task->priority->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Parent Task') ?></th>
                    <td><?= h($task->parent_task) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($task->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($task->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($task->description)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Notes') ?></h4>

                 <?=  $this->Html->link(
                            'Add a note',
                            ['controller' => 'Notes', 'action' => 'add', '?' => ['task_id' => $task->id]],
                            ['class' => 'btn btn-sm btn-primary']
                        ); ?>


                <?php if (!empty($task->notes)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Description') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($task->notes as $note) : ?>
                        <tr>
                            <td><?= h($note->description) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Notes', 'action' => 'edit', $note->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Notes', 'action' => 'delete', $note->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $note->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Reminders') ?></h4>
                <?=  $this->Html->link(
                            'Add Reminder',
                            ['controller' => 'Reminders', 'action' => 'add', '?' => ['task_id' => $task->id]],
                            ['class' => 'btn btn-sm btn-primary']
                        ); ?>
                <?php if (!empty($task->reminders)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th>#</th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('remind date') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php $count=1; foreach ($task->reminders as $reminder) : ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?= h($reminder->name) ?></td>
                            <td><?= h($reminder->date->nice()) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Reminders', 'action' => 'edit', $reminder->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Reminders', 'action' => 'delete', $reminder->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete reminder {0}?', $reminder->name),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php $count++; endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Tasks') ?></h4>

                  <?=  $this->Html->link(
                            'Add a subtask',
                            ['controller' => 'Tasks', 'action' => 'add', '?' => ['task_id' => $task->id]],
                            ['class' => 'btn btn-sm btn-primary']
                        ); ?>
                <?php if (!empty($task->child_tasks)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th>#</th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Priority') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php $count = 1; foreach ($task->child_tasks as $childTask) : ?>
                        <tr>
                            <td><?= $count ?></td>
                            <td><?= h($childTask->name) ?></td>
                            <td><?= h($childTask->created) ?></td>
                            <td><?= h($childTask->modified) ?></td>
                            <td><?= h($childTask->status) ?></td>
                            <td><?= h($childTask->description) ?></td>
                            <td><?= h($childTask->priority->name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Tasks', 'action' => 'edit', $childTask->id]) ?>
                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['controller' => 'Tasks', 'action' => 'delete', $childTask->id],
                                    [
                                        'method' => 'delete',
                                        'confirm' => __('Are you sure you want to delete # {0}?', $childTask->id),
                                    ]
                                ) ?>
                            </td>
                        </tr>
                        <?php $count++; endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
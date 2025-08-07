<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Reminder $reminder
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Reminder'), ['action' => 'edit', $reminder->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Reminder'), ['action' => 'delete', $reminder->id], ['confirm' => __('Are you sure you want to delete # {0}?', $reminder->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Reminders'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Reminder'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="reminders view content">
            <h3><?= h($reminder->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($reminder->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Task') ?></th>
                    <td><?= $reminder->hasValue('task') ? $this->Html->link($reminder->task->name, ['controller' => 'Tasks', 'action' => 'view', $reminder->task->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($reminder->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Date') ?></th>
                    <td><?= h($reminder->date) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
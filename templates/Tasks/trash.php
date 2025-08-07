<?php
/**
 * Créez ce fichier : templates/Tasks/trash.php
 */
?>

<div class="tasks index content">
    <?= $this->Html->link((' back to tasks'), ['action' => 'index']) ?>
    
    <h3><?= __('BIN') ?></h3>
    
    <?php if (!empty($deletedTasks->toArray())): ?>
        <div class="trash-actions">
            <?= $this->Form->postLink(
                __(' Empty the bin'), 
                ['action' => 'emptyTrash'], 
                [
                    'confirm' => __('are you sure you wish to delete this task from the bin ?'),
                ]
            ) ?>
        </div>
        
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= __('name') ?></th>
                        <th><?= __('Priorities') ?></th>
                        <th><?= __('deleted on') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deletedTasks as $task): ?>
                    <tr class="deleted-task">
                        <td>
                            <strong><?= h($task->name) ?></strong>
                            <?php if (!empty($task->description)): ?>
                                <br><small class="text-muted"><?= h($task->description) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($task->priority): ?>
                                <span class="priority-badge priority-<?= h(strtolower($task->priority->name)) ?>">
                                    <?= h($task->priority->name) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">None</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($task->deleted_at): ?>
                                <?= $task->deleted_at->format('d/m/Y à H:i') ?>
                                <br><small class="text-muted">
                                     <?= $this->Time->timeAgoInWords($task->deleted_at) ?> 
                                </small>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <?= $this->Form->postLink(
                                __('Restore'), 
                                ['action' => 'restore', $task->id], 
                                [
                                    'title' => 'Restaurer cette tâche'
                                ]
                            ) ?>
                            
                            <?= $this->Form->postLink(
                                __('Delete'), 
                                ['action' => 'deleteForever', $task->id], 
                                [
                                    'confirm' => __('are you sure you want to delete "{0}" ? .', $task->title),
                                    'title' => 'delete'
                                ]
                            ) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination si nécessaire -->
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(('next') . ' >') ?>
                <?= $this->Paginator->last(('last') . ' >>') ?>
            </ul>
            <!-- <p><?= $this->Paginator->counter(('Page {{page}} sur {{pages}}, montrant {{current}} enregistrement(s) sur {{count}} au total')) ?></p> -->
        </div>
        
    <?php else: ?>
        <div class="empty-trash">
            <p>the bin is empty !</p>
            <p>All deleted tasks will be shown here.</p>
            <?= $this->Html->link(('Retour aux tâches'), ['action' => 'index'], ['class' => 'button']) ?>
        </div>
    <?php endif; ?>
</div>

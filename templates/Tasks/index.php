<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Task> $createdTasks
 * @var iterable<\App\Model\Entity\Task> $assignedTasks
 */
?>

<h3>Tasks</h3>

<table class="table table-bordered table-striped">
    <thead>
         <?= $this->Html->link(__('New Task'), ['action' => 'add'], ['class' => 'button float-right']) ?>
        <tr>
            <th>name</th>
            <th>properties</th>
            <th>creation date</th>
            <th>Actions</th>
           <strong> <?= $this->Html->link(__('BIN'), ['action' => 'trash']) ?></strong>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($createdTasks as $task): ?>
        <tr>
            <td><?= h($task->name) ?></td>
            <td><?= $task->priority->name ?? 'Non défini' ?> | <small> 
                                                                  <?php if ($task->done_at): ?>
                                                                        Done at <?= $task->done_at->format('d_m_Y H:i:s') ?>
                                                                    <?php else: ?>
                                                                        <?= $this->Form->postLink(
                                                                            'Mark as Done',
                                                                            ['action' => 'markAsDone', $task->id],
                                                                            ['confirm' => 'Are you sure?', 'class' => 'btn btn-success']
                                                                        ) ?>
                                                                    <?php endif; ?>                                                                
                                                               </small> 
            </td>
            <td><?= h($task->created) ?></td>
            <td class="actions">
                <?php if (!empty($task->id)): ?>
                    <!-- <td class="actions"> -->
                        <?= $this->Html->link(__('View'), ['action' => 'view', $task->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $task->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $task->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to place # {0} in the bin ?',$task->id),
                            ]
                        ) ?>
                        <?= $this->Html->link(__('assign'), ['action' => 'assign', $task->id]) ?>
             
                <?php else: ?>
                    <span class="text-muted">missing ID</span>
                <?php endif; ?>
            </td>
        </tr>

     <?php endforeach; ?>
    </tbody>
</table>

<!-- <?= $this->Paginator->numbers(['model' => 'created']) ?>
<?= $this->Paginator->prev('<< Précédent', ['model' => 'created']) ?>
<?= $this->Paginator->next('Suivant >>', ['model' => 'created']) ?>
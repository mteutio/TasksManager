<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Task $task
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $priorities
 * @var \Cake\Collection\CollectionInterface|string[] $parentTasks
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Tasks'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="tasks form content">
            <?= $this->Form->create($task) ?>
            <fieldset>
                <legend><?= __('Add Task') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('description');
                   echo $this->Form->control('priority_id', [
                    'type' => 'select',
                    'options' => $priorities,
                    ]);
                    
                    echo $this->Form->control('parent', ['options' => $parentTasks,
                                                         'empty' => 'None',
                                                         'type' => 'select',
                                                        ]);
                    echo $this->Form->control('note', [
                                               'type' => 'textarea',
                                               'label' => 'Note (facultatif)']);

                    echo $this->Form->control('reminder', [
                             'type' => 'datetime',
                             'label' => 'Rappel (facultatif)']);

                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

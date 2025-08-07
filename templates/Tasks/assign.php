<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Task> $tasks
 */
?> 
 
<h2>Assign the task : <?= h($task->name) ?></h2>

<?= $this->Form->create($task) ?>
<fieldset>
    <legend>choose a user</legend>
    <?= $this->Form->control('user_id', [
        'label' => 'User',
        'options' => $users
    ]) ?>
</fieldset>
<?= $this->Form->button('Assign') ?>
<?= $this->Form->end() ?>

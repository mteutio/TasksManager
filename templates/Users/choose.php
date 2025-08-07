<h2>Assign Task: <?= h($task->name) ?></h2>

<?= $this->Form->create($task) ?>
<fieldset>
    <legend>Choose a User</legend>

    <!-- Dropdown pour choisir l'utilisateur -->
    <?= $this->Form->control('user_id', ['options' => $users, 'label' => 'Assign to']) ?>
</fieldset>
<?= $this->Form->button(__('assign')) ?>
<?= $this->Form->end() ?>

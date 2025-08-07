<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Priority $priority
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $priority->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $priority->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Priorities'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="priorities form content">
            <?= $this->Form->create($priority) ?>
            <fieldset>
                <legend><?= __('Edit Priority') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('description');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

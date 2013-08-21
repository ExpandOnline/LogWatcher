<div class="container">
    <div class="inline">
        <?php echo $this->Html->link(__('&laquo; Back'), Configure::read('LogWatcher.homepage'), array('escape' => false)); ?>
    </div>
    <div class="inline">
        <?php
        echo $this->Form->create('LogWatcher', array('class' => 'inline form-inline'));
        echo $this->Form->input('LogWatcher.log', array(
            'options' => array_combine($logFiles, $logFiles),
            'empty' => '-',
            'label' => false,
            'div' => false,
            'class' => 'lw-switch',
            'id' => false
        ));
        echo $this->Form->button('load', array('class' => 'lw-refresh btn'));
        echo $this->Form->end();
        ?>
    </div>
    <div class="inline">
        <span class="lw-hash-changed label label-important" style="display: none;"><?php echo __('New messages available!'); ?></span>
        <span class="lw-last-refresh"><?php echo __('Last refresh: <span>%s</span>', date('H:i:s')); ?></span>
    </div>
</div>

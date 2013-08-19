<div class="container">
    <div class="inline">
        <?php echo $this->Html->link(__('&laquo; Back'), Configure::read('LogWatcher.homepage'), array('escape' => 'false')); ?>
    </div>
    <div class="overflow-x inline">
        <?php
        $list = array();
        foreach ($logFiles as $fileName) {
            $list[] = $this->Html->link(
                $fileName,
                array('plugin' => 'log_watcher', 'controller' => 'log_watchers', 'action' => 'index', 'log' => $fileName),
                array('data-log-name' => $fileName)
            );
        }
        echo $this->Html->nestedList(
            $list,
            array('class' => 'inline'),
            array('class' => 'lw-log-switch')
        );
        ?>
    </div>
</div>

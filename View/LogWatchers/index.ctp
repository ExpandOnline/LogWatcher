<?php
/**
 * @var View $this
 */

# some variables we need for compatibility with "foldered" projects
echo $this->Html->scriptBlock('
        var logWatcher = {
            webroot: "' . $this->webroot . 'log_watcher/"
        };
    ', array('inline' => false));
echo $this->Html->script('LogWatcher.log', array('inline' => false));

# if another layout is set, echo the header here
if (Configure::read('LogWatcher.layout')) {
    echo $this->element('LogWatcher.header');
}
?>
    <div class="lw-log-container">
        <?php
        if ($this->get('content')) {
            echo $this->Html->tag('pre', $content);
        } else {
            echo __('Choose a log to watch from the navigation above');
        }
        ?>
        <a name="bottom"></a>
    </div>
<?php
# if another layout is set, echo the header here as well
if (Configure::read('LogWatcher.layout')) {
    echo $this->element('LogWatcher.header');
}
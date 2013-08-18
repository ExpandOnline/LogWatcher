<?php
/**
 * The default route
 */
Router::connect('/log_watcher/log_watchers/:log/*', array('plugin' => 'log_watcher', 'controller' => 'log_watchers', 'action' => 'index'));
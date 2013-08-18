<?php
/**
 * Some configuration settings
 */
Configure::write('LogWatcher', array(
    # insert an array to whitelist logs. Other logs will not be accessible
    'whitelist' => false,
    # the location of the logfiles
    'logDir' => LOGS,
    # the amount of kilobytes to load from the end of the file. Reduces loading times and overflows.
    'kiloBytes' => 100,
    # the url to link to with the "back" button
    'homepage' => '/',
    # the layout to use, by default it will use the LogWatch layout (floating headers and stuff)
    'layout' => false
));
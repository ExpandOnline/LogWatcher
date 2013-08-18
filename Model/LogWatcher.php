<?php
app::uses('AppModel', 'Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Class LogWatcher
 *
 * @author Jelmer Dröge <jelmer@avolans.nl>
 */
class LogWatcher extends AppModel {

    public $useTable = false;

    /**
     * Get all the logfiles accessible and whitelisted
     *
     * @return array
     */
    public function getLogFiles(){
        $dir = new Folder(Configure::read('LogWatcher.logDir'));
        # if a whitelist is set, only show those
        if ($whitelist = Configure::read('LogWatcher.whitelist')){
            $allowed = implode('|', $whitelist);
        } else {
            $allowed = '.*';
        }
        $logs = $dir->find($allowed . '\.log');
        foreach ($logs as &$log){
            $log = substr($log, 0, -4);
        }
        return $logs;
    }

    /**
     * Check if a log exists
     *
     * @param $logName
     *
     * @return bool
     */
    public function fileExists($logName){
        return file_exists(Configure::read('LogWatcher.logDir') . $logName . '.log');
    }

    /**
     * Get the contents of a log
     *
     * @param $logName
     *
     * @return string
     */
    public function getContent($logName){
        $fp = fopen(Configure::read('LogWatcher.logDir') . $logName . '.log', 'r');
        $bytes = Configure::read('LogWatcher.kiloBytes') * 1024; # in the config the amount of kb is set
        fseek($fp, -$bytes, SEEK_END);
        $text = fread($fp, $bytes);
        return substr( $text, strpos($text, "\n")+1 );
    }

    /**
     * Get the hash of a log
     *
     * @param $logName
     *
     * @return string
     */
    public function getHash($logName){
        return md5_file(Configure::read('LogWatcher.logDir') . $logName . '.log');
    }

}
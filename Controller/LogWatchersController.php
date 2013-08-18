<?php
app::uses('AppController', 'Controller');
/**
 * Class LogWatchersController
 *
 * @property LogWatcher $LogWatcher
 *
 * @author Jelmer Dröge <jelmer@avolans.nl>
 */
class LogWatchersController extends AppController {

    /**
     * An overview of all the logs as well as the page where the log is loaded
     *
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     */
    public function index(){
        # if the default layout is overwritten..
        if (!$this->layout = Configure::read('LogWatcher.layout')){
            $this->layout = 'LogWatcher.default';
        }

        # get all the logfile names
        $logFiles = $this->LogWatcher->getLogFiles();

        # if the log parameter is set .. (noscript access)
        if (isset($this->request->params['log'])){
            $log = $this->request->params['log'];

            # if the file doesn't even exist ..
            if (!$this->LogWatcher->fileExists($log)){
                throw new NotFoundException('Unknown log');
            }

            # if the accessed log isn't in the whitelist
            if (!in_array($log, $logFiles)){
                throw new MethodNotAllowedException(__('This log may not be watched'));
            }

            # if a json formatted output is requested
            if (isset($this->request->params['named']['json'])){
                $this->autoRender = false;
                echo json_encode(array(
                    'content' => $this->LogWatcher->getContent($log),
                    'hash' => $this->LogWatcher->getHash($log)
                ));
                $this->shutdownProcess();
                $this->_stop();
            }

            # otherwise, simply output the log
            $content = $this->LogWatcher->getContent($log);
        }

        $this->set(compact('logFiles', 'content'));
    }

}
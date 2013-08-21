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
        if ($this->request->is('post') && $log = $this->request->data('LogWatcher.log')){
            if (!$this->request->query('json')){
                $this->redirect(array('log' => $log, '#' => 'bottom'));
            }
        }
        # if the default layout is overwritten..
        if (!$this->layout = Configure::read('LogWatcher.layout')){
            $this->layout = 'LogWatcher.default';
        }

        # get all the logfile names
        $logFiles = $this->LogWatcher->getLogFiles();

        # if the log parameter is set .. (noscript access)
        if (isset($this->request->params['log'])){
            $this->request->data['LogWatcher']['log'] = $log = $this->request->params['log'];

            # if the file doesn't even exist ..
            if (!$this->LogWatcher->fileExists($log)){
                throw new NotFoundException(__('Unknown log'));
            }

            # if the accessed log isn't in the whitelist
            if (!in_array($log, $logFiles)){
                throw new MethodNotAllowedException(__('This log may not be watched'));
            }

            # if a json formatted output is requested
            if ($this->request->query('json')){
                $this->autoRender = false;
                $hash = $this->LogWatcher->getHash($log);
                if ($this->request->query('hashOnly')){
                    echo $hash;
                } else {
                    echo json_encode(array(
                        'content' => $this->LogWatcher->getContent($log),
                        'hash' => $hash,
                        'time' => date('H:i:s')
                    ));
                }
                $this->shutdownProcess();
                $this->_stop();
            }

            # otherwise, simply output the log
            $content = $this->LogWatcher->getContent($log);
        }

        $this->set(compact('logFiles', 'content'));
    }

}
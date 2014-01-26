<?php
class LogsController extends SecuredController
{

    /**
     * Action for url /logs/
     * @param array $params Request params
     * @param string $method Request method
     */
    public function actionIndex($params, $method)
    {
        if ('GET' == $method) {
            // load and render list of all logs
            $logs = LogModel::findAll();
            $this->render('log/all', $logs);
        }
        if ('DELETE' == $method) {
            if (LogModel::deleteAll()) {
                http_response_code(204);
            } else {
                $this->error(['Can\'t clear logs']);
            }
        }
    }

}
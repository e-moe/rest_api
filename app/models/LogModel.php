<?php
class LogModel extends Model
{
    /**
     * @var int Primary key
     */
    public $id;

    /**
     * @var string Timestamp
     */
    public $timestamp;

    /**
     * @var string IP address
     */
    public $ip;

    /**
     * @var string Endpoint (e.g. /users/123)
     */
    public $endpoint;

    /**
     * @var string Session token
     */
    public $token;

    /**
     * @var int Response result code
     */
    public $result;

    /**
     * @return string Table name
     */
    public static function tableName()
    {
        return 'logs';
    }
    
    /**
     * @return string Primary key name
     */
    public static function primaryKey()
    {
        return 'id';
    }
    
    /**
     * @return array List of model attributes
     */
    public function getAttributes()
    {
        return ['id', 'timestamp', 'ip', 'endpoint', 'token', 'result'];
    }
    
    /**
     * @return array Unique fileds list
     */
    public function getUniqueFields()
    {
        return ['id'];
    }
    
    /**
     * @return array Required fileds list
     */
    public function getRequiredFields()
    {
        return ['ip', 'endpoint', 'result'];
    }

    /**
     * @return array Field types list
     */
    public function getFieldTypes()
    {
        return [
            'string' => ['timestamp', 'ip', 'endpoint', 'token'],
            'numeric' => ['result'],
        ];
    }

    public static function log($data)
    {
        $model = new static();
        $model->populate($data);
        $model->save();
    }

}
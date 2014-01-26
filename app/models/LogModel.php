<?php
class LogModel extends Model
{
    public $id;
    public $timestamp;
    public $ip;
    public $endpoint;
    public $token;
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
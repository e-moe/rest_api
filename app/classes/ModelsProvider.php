<?php

class ModelsProvider extends AppAware
{
    /**
     * @var string
     */
    protected $modelClass;
    
    /**
     * @var DB
     */
    protected $db;

    public function __construct(App $app, $modelClass, DB $db)
    {
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException(sprintf('Can\'t create ModelsProvider for class "%s"', $modelClass));
        }
        parent::__construct($app);
        $this->modelClass = $modelClass;
        $this->db = $db;
    }
    
    /**
     * Creates model
     * 
     * @return Model
     */
    public function create()
    {
        return new $this->modelClass($this->app, $this->db, $this, $this->app['validator']);
    }
    
    /**
     * @return string Table name
     */
    public function tableName()
    {
        return call_user_func($this->modelClass .'::tableName');
    }
    
    /**
     * @return string Primary key
     */
    public function primaryKey()
    {
        return call_user_func($this->modelClass .'::primaryKey');
    }

    /**
     * Finds a single row with the specified condition.
     *
     * @param string $condition Where SQL condition
     * @param array $params List of parameters
     * @return Model|null
     */
    public function find($condition = '', array $params = [])
    {
        $data = $this->db->select($this->tableName(), ['*'], $condition, $params);
        $model = null;
        if (count($data)) {
            $model = $this->create();
            $model->populate(reset($data));
            $model->setIsNewRecord(false);
        }
        return $model;
    }
    
    /**
     * Finds all rows with the specified condition.
     *
     * @param string $condition Where SQL condition
     * @param array $params List of parameters
     * @return Model[]
     */
    public function findAll($condition = '', array $params = [])
    {
        $models = [];
        $data = $this->db->select($this->tableName(), ['*'], $condition, $params);
        if (count($data)) {
            foreach ($data as $row) {
                $model = $this->create();
                $model->populate($row);
                $model->setIsNewRecord(false);
                $models[] = $model;
            }
        }
        return $models;
    }
    
    /**
     * Finds a row record with the specified primary key.
     *
     * @param int $pk
     * @return Model|null
     */
    public function findByPk($pk)
    {
        return $this->find($this->primaryKey() . ' = ?', [$pk]);
    }
    
    
    /**
     * Deletes rows with the specified condition.
     *
     * @param string $condition Where SQL condition
     * @param array $params List of parameters
     * @return int The number of rows deleted
     */
    public function deleteAll($condition = '', array $params = [])
    {
        $deleted = 0;
        $models = $this->findAll($condition, $params);
        foreach ($models as $model) {
            if ($model->delete()) {
                $deleted++;
            }
        }
        return $deleted;
    }

    /**
     * Deletes row with the specified primary key.
     *
     * @param int $pk
     * @return boolean
     */
    public function deleteByPk($pk)
    {
        $model = $this->findByPk($pk);
        if ($model) {
             return $model->delete();
        }
        return false;
    }
    
}
<?php
abstract class Model extends AppAware
{
    /**
     * @var ModelsProvider
     */
    protected $provider = null;
    
    /**
     * @var Validator
     */
    protected $validator = null;
    
    /**
     * @var DB
     */
    protected $db = null;

    /**
     * @var array List of errors 
     */
    protected $errors = [];
    
    /**
     * @var bool It is new record or not
     */
    protected $isNewRecord = true;
    
    public function __construct(App $app, DB $db, ModelsProvider $provider, Validator $validator)
    {
        parent::__construct($app);
        $this->db = $db;
        $this->provider = $provider;
        $this->validator = $validator;
    }

    /**
     * @return bool Is new record or existing
     */
    public function getIsNewRecord()
    {
        return $this->isNewRecord;
    }
    
    /**
     * @param bool $value
     * @return Model
     */
    public function setIsNewRecord($value)
    {
        $this->isNewRecord = $value;
        return $this;
    }

    /**
     * @return string Table name
     */
    abstract public static function tableName();
    
    /**
     * @return string Primary key name
     */
    abstract public static function primaryKey();
    
    /**
     * @return array List of model attributes
     */
    abstract public function getAttributes();
    
    /**
     * @return array Required fileds list
     */
    abstract public function getRequiredFields();

    /**
     * @return array Field types list
     */
    abstract public function getFieldTypes();
    
    /**
     * @return array Unique fileds list
     */
    abstract public function getUniqueFields();

    /**
     * Populates record with the given attributes.
     *
     * @param array $data
     * @return Model
     */
    public function populate(array $data, $attributes = null)
    {
        $list = $this->getAttributes();
        if (is_array($attributes)) {
            $list = array_intersect($list, $attributes);
        }
        foreach ($data as $key => $value) {
            if (in_array($key, $list)) {
                $this->{$key} = $value;
            }
        }
        return $this;
    }
    
    /**
     * Get record's array representation
     *
     * @return array Associative array representation of current model 
     */
    public function toArray($attributes = null)
    {
        $arr = [];
        $list = $this->getAttributes();
        if (is_array($attributes)) {
            $list = array_intersect($list, $attributes);
        }
        foreach ($list as $attribute) {
            $arr[$attribute] = $this->{$attribute};
        }
        return $arr;
    }

    /**
     * Before save event
     *
     * @return bool
     */
    protected function beforeSave()
    {
        return true;
    }

    /**
     * After save event
     *
     * @return bool
     */
    protected function afterSave()
    {
        return true;
    }

    /**
     * Saves the current record.
     *
     * @param bool $runValidation Whether to perform validation before saving the record
     * @param array|null $attributes List of attributes that need to be saved
     */
    public function save($runValidation = true, $attributes = null)
    {
         if (!$runValidation || $this->validate($attributes)) {
             if ($this->beforeSave() && $this->checkUnique($attributes)) {
                 $saved = $this->getIsNewRecord() ? $this->insert($attributes) : $this->update($attributes);
                 if ($saved) {
                     $this->setIsNewRecord(false);
                     $this->afterSave();
                 }
                 return $saved;
             }
         } else {
             return false;
         }
    }
    
    /**
     * Inserts a row into the table based on this record attributes.
     *
     * @param array|null $attributes List of attributes that need to be saved
     * @return bool
     */
    protected function insert($attributes = null)
    {
        $data = $this->toArray($attributes);
        $inserted = $this->db->insert(
            $this->tableName(),
            $data
        );
        if ($inserted > 0) {
            $this->{$this->primaryKey()} = $this->db->insertId();
        }
        return $inserted > 0;
    }
    
    /**
     * Check current record attributes to be unique per table
     * 
     * @param array|null $attributes Attributes list to check
     * @return bool
     */
    protected function checkUnique($attributes = null)
    {
        $uniqueFields = $this->getUniqueFields();
        if (is_array($attributes) && count($attributes)) {
            $uniqueFields = array_intersect($uniqueFields, $attributes);
        }
        if (count($uniqueFields)) {
            $sql = sprintf(
                ' (%s = ?) ',
                implode(' = ? OR ', $uniqueFields)
            );
            $params = $this->toArray($uniqueFields);
            if (!$this->getIsNewRecord()) {
                $sql .= sprintf(
                    ' AND %s != ?',
                    $this->primaryKey()
                );
                $params[] = $this->{$this->primaryKey()};
            }
            $models = $this->provider->findAll($sql, $params);
            if (count($models)) {
                $this->errors[] = sprintf(
                    'One of this fields is not unique: %s',
                     implode(', ', $uniqueFields)
                );
                return false;
            }
        }
        return true;
    }

    /**
     * Updates the row represented by this record.
     *
     * @param array|null $attributes List of attributes that need to be saved
     * @return bool
     */
    protected function update($attributes = null)
    {
        $data = $this->toArray($attributes);
        $updated = $this->db->update(
            $this->tableName(),
            $data,
            $this->primaryKey() . ' = ?',
            [$this->{$this->primaryKey()}]
        );
        return $updated > 0;
    }
    
    /**
     * Deletes corresponding row.
     *
     * @return bool
     */
    public function delete()
    {
        $deleted = $this->db->delete(
            $this->tableName(),
            $this->primaryKey() . ' = ?',
            [$this->{$this->primaryKey()}]
        );
        return $deleted > 0;
    }
    

    /**
     * Validate property values
     * 
     * @param array|null $attributes List of attributes that need to be checked
     * @return bool
     */
    protected function validate($attributes = null)
    {
        $required = $this->getRequiredFields();
        $types = $this->getFieldTypes();
        $data = $this->toArray($attributes);
        $this->errors = [];
        if ($this->validator->checkRequired($required, $data) && $this->validator->checkTypes($types, $data)) {
            return true;
        } else {
            $this->errors = array_merge($this->errors, $this->validator->getErrors());
            return false;
        }
    }

    /**
     * Get errors
     * 
     * @return array List of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
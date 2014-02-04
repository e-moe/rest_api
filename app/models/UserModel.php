<?php
class UserModel extends Model
{
    /**
     * @var int Primary key
     */
    public $id;

    /**
     * @var string Email address
     */
    public $email;

    /**
     * @var string Password hash
     */
    public $password;

    /**
     * @return string Table name
     */
    public static function tableName()
    {
        return 'users';
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
        return ['id', 'email', 'password'];
    }
    
    /**
     * @return array Unique fileds list
     */
    public function getUniqueFields()
    {
        return ['id', 'email'];
    }
    
    /**
     * @return array Required fileds list
     */
    public function getRequiredFields()
    {
        return ['email', 'password'];
    }

    /**
     * @return array Field types list
     */
    public function getFieldTypes()
    {
        return [
            'string' => ['password'],
            'email' => ['email'],
        ];
    }


}
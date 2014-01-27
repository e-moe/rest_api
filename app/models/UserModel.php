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
     * @var string Session token value
     */
    public $session_token;

    /**
     * @var int Session token expire time
     */
    public $session_expire;

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
        return ['id', 'email', 'password', 'session_token', 'session_expire'];
    }
    
    /**
     * @return array Unique fileds list
     */
    public function getUniqueFields()
    {
        return ['id', 'email', 'session_token'];
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
            'string' => ['password', 'session_token'],
            'numeric' => ['session_expire'],
            'email' => ['email'],
        ];
    }

    /**
     * Calculate password hash
     */
    protected function hashPassword()
    {
        if (password_needs_rehash($this->password, PASSWORD_BCRYPT)) {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        }
    }

    /**
     * Before save event
     *
     * @return bool
     */
    protected function beforeSave()
    {
        $this->hashPassword();
        return parent::beforeSave();
    }
    
    /**
     * Generate unique token for each user
     * 
     * @param int $id
     * @return string
     */
    protected static function generateToken($id)
    {
        return $id . md5(rand());
    }

    /**
     * Try to log in based on request data
     * 
     * @param stdClass $request Request data
     * @return boolean Returns true on success login
     */
    public static function login($request)
    {
        $email = $request->email;
        $user = static::find('`email` = ?', [$email]);
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                $token = static::generateToken($user->id);
                $user->session_token = $token;
                $user->session_expire = static::renewExpireTime();
                if ($user->save()) {
                    return $token;
                }
            }
        }
        return false;
    }
    
    /**
     * Return new sessions expire time
     * 
     * @return int
     */
    public static function renewExpireTime()
    {
        return time() + SESSION_LIFETIME;
    }

}
<?php
class UserModel extends Model
{
    public $id;
    public $email;
    public $password;
    public $session_token;
    public $session_expire;

    /**
     * @return string Table name
     */
    public function tableName()
    {
        return 'users';
    }
    
    /**
     * @return string Primary key name
     */
    public function primaryKey()
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
            'string' => ['email', 'password', 'session_token'],
            'numeric' => ['session_expire'],
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

    protected function beforeSave()
    {
        $this->hashPassword();
        return parent::beforeSave();
    }
    
    protected static function generateToken($id)
    {
        return $id . md5(rand());
    }

    public static function login($request)
    {
        $email = $request->email;
        $user = $this->find('`email` = ?', [$email]);
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                $token = self::generateToken($user->id);
                $user->session_token = $token;
                $user->session_expire = self::renewExpireTime();
                if ($user->save()) {
                    return $token;
                }
            }
        }
        return false;
    }
    
    public static function renewExpireTime()
    {
        return time() + SESSION_LIFETIME;
    }

}
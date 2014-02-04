<?php
class Validator extends AppAware
{
    protected $errors = array();

    /**
     * Check required fields in given data
     *
     * @param array $required Array of required field names
     * @param array $data Data for checking
     * @return bool
     */
    public function checkRequired(array $required, array $data)
    {
        $result = true;
        foreach ($required as $r) {
            if (!isset($data[$r])) {
                $result = false;
                $this->errors[] = "Missed required field '$r'";
            }
        }
        return $result;
    }

    /**
     * Check field types
     *
     * @param array $types Array of field types and names: array('{type}' => array('{field name}', ...), ...)
     * @param array $data Data for checking
     * @return bool
     */
    public function checkTypes(array $types, array $data)
    {
        $result = true;
        foreach ($types as $t => $fields) {
            foreach ($fields as $f) {
                if (isset($data[$f])) {
                    switch ($t) {
                        case 'string':
                            if (!is_string($data[$f])) {
                                $result = false;
                                $this->errors[] = "Field '$f' must be '$t'";
                            }
                            break;
                        case 'numeric':
                            if (!is_numeric($data[$f])) {
                                $result = false;
                                $this->errors[] = "Field '$f' must be '$t'";
                            }
                            break;
                        case 'email':
                            if (false === filter_var($data[$f], FILTER_VALIDATE_EMAIL)) {
                                $result = false;
                                $this->errors[] = "Field '$f' must be valid email address";
                            }
                            break;
                    }
                }
            }
        }
        return $result;
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
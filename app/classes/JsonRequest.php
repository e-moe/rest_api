<?php
class JsonRequest
{
    protected $errors = array();

    /**
     * Parse request string in JSON format
     * @param string $request Request string
     * @return stdClass|null Parsed data object or NULL
     */
    public function parse($request)
    {
        $data = json_decode($request);
        $isError = true;
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                // No errors
                $isError = false;
                break;
            case JSON_ERROR_DEPTH:
                $this->errors[] = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $this->errors[] = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $this->errors[] = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $this->errors[] = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $this->errors[] = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $this->errors[] = 'Unknown error';
                break;
        }
        return $isError ? NULL : $data;
    }

    /**
     * Get errors
     * @return array List of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

}
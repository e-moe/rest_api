<?php
class JsonParser extends DIAble
{
    /**
     * Parse request string in JSON format
     *
     * @param string $request Request string
     * @return mixed|null Parsed data object or NULL
     */
    public function parse($request)
    {
        $error = null;
        $data = json_decode($request);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                // No errors
                $isError = false;
                break;
            case JSON_ERROR_DEPTH:
                $error = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $error = 'Unknown error';
                break;
        }
        if (null !== $error) {
            throw new Exception($error);
        }
        return $data;
    }

}
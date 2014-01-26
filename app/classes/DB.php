<?php

class DB
{
    /**
     * @var mysqli
     */
    protected $link = null;
    protected $connected = false;
    protected static $instance; // object instance

    private function __construct()
    {
        global $_APP_CONFIG;
        $this->link = new mysqli($_APP_CONFIG['db_host'], $_APP_CONFIG['db_user'], $_APP_CONFIG['db_pass'], $_APP_CONFIG['db_base']);
        /* check connection */
        if ($this->link->connect_errno) {
            App::getInstance()->error(500, 'DB error: ' . $this->link->connect_error);
        } else {
            $this->connected = true;
        }
    }
    private function __clone() {}
    private function __wakeup() {}
    public static function getInstance()
    { // returns single class instance. @return DB
        if (is_null(self::$instance)) {
            self::$instance = new DB;
        }
        return self::$instance;
    }

    /**
     * Insert SQL query
     * @param string $table Table name
     * @param array $values Associative array of values
     * @return int Num of inserted rows
     */
    public function insert($table, $values)
    {
        $mysqli = $this->link;
        $stmt = $mysqli->stmt_init();
        $columns = array_map(
            function($v) use($mysqli) { return '`' . $mysqli->real_escape_string($v) . '`'; },
            array_keys($values)
        );
        $placeholders = array_fill(0, count($values), '?');
        $params = array_values($values);
        $sql = 'INSERT INTO `' . $mysqli->real_escape_string($table) . '` (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';
        $stmt->prepare($sql);
        if (count($values)) {
            call_user_func_array(array($stmt, 'bind_param'), $this->prepareParams($params));
        }
        $status = $stmt->execute();
        return $stmt->affected_rows;
    }

    /**
     * @return mixed Last insert ID
     */
    public function insertId()
    {
        return $this->link->insert_id;
    }
    
    /**
     * Update SQL query
     * @param string $table Table name
     * @param array $values Associative array of values
     * @param string $where WHERE SQL clause
     * @return int Num of updated rows
     */
    public function update($table, $values, $condition = '', array $params = [])
    {
        $mysqli = $this->link;
        $stmt = $mysqli->stmt_init();
        $columns = array_map(
            function($v) use($mysqli) { return '`' . $mysqli->real_escape_string($v) . '`=?'; },
            array_keys($values)
        );
        $where = empty($condition) ? '' : ' WHERE ' . $condition;
        $sql = sprintf(
            'UPDATE `%s` SET %s %s',
            $mysqli->real_escape_string($table),
            implode(', ', $columns),
            $where
        );
        $stmt->prepare($sql);
        $params = array_merge(array_values($values), $params);
        if (count($params)) {
            call_user_func_array(array($stmt, 'bind_param'), $this->prepareParams($params));
        }
        $status = $stmt->execute();
        return $stmt->affected_rows;
    }

    /**
     * Delete SQL query
     * @param string $table Table name
     * @param string $where WHERE SQL clause
     * @return int Num of deleted rows
     */
    public function delete($table, $condition = '', array $params = [])
    {
        $mysqli = $this->link;
        $stmt = $mysqli->stmt_init();
        $where = empty($condition) ? '' : ' WHERE ' . $condition;
        $sql = sprintf(
            'DELETE FROM `%s` %s',
            $mysqli->real_escape_string($table),
            $where
        );
        $stmt->prepare($sql);
        if (count($params)) {
            call_user_func_array(array($stmt, 'bind_param'), $this->prepareParams($params));
        }
        $status = $stmt->execute();
        return $stmt->affected_rows;
    }

    /**
     * Select SQL query
     * @param string $table Table name
     * @param array $columns Array of column names
     * @param string $where WHERE SQL clause
     * @return mixed Selected rows
     */
    public function select($table, $columns = array('*'), $condition = '', array $params = [])
    {
        $mysqli = $this->link;
        $stmt = $mysqli->stmt_init();
        $where = empty($condition) ? '' : ' WHERE ' . $condition;
        $cols = array_map(
            function($v) use($mysqli) { return $mysqli->real_escape_string($v); },
            $columns
        );
        $sql = sprintf(
            'SELECT %s FROM `%s` %s',
            implode(', ', $cols),
            $mysqli->real_escape_string($table),
            $where
        );
        $stmt->prepare($sql);
        if (count($params)) {
            call_user_func_array(array($stmt, 'bind_param'), $this->prepareParams($params));
        }
        $status = $stmt->execute();
        return $this->getAssocResults($stmt);
    }
    
    protected function getAssocResults($stmt)
    {
        $result = [];
        $row = [];
        $meta = $stmt->result_metadata(); 
        while ($field = $meta->fetch_field()) 
        { 
            $params[] = &$row[$field->name]; 
        } 
        call_user_func_array(array($stmt, 'bind_result'), $params); 
        while ($stmt->fetch()) { 
            foreach($row as $key => $val) 
            { 
                $c[$key] = $val; 
            } 
            $result[] = $c; 
        }
        return $result;
    }

    /**
     * Create array of references from array of values for bind_param function
     * @param array $params Array of values
     * @return array Array of references
     */
    protected function prepareParams($params)
    {
        $types = '';
        $refs = array();
        foreach ($params as $k => $p) {
            $refs[$k] = & $params[$k];
            $types .= is_int($p) ? 'i' : 's';
        }
        return array_merge((array)$types, $refs);
    }

}
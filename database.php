<?php

class DB extends mysqli {

    public function __construct() {
        parent::__construct();
        $this->host = 'localhost';
        $this->user = 'root';
        $this->pass = '';
        $this->db = 'nurakan';
    }

    public function _connect() {
        try {
            $this->obj = new mysqli($this->host, $this->user, $this->pass);
            if (!$this->obj) {
                throw new Exception('Could not connect to mysql');
            }
            $this->db_select();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /*
     * Select the default database
     * This function can also be called from within the _connect() function as i have done
     * or, even is not necessary
     * but i wanted the database connection in a separate function and mysql connection
     * in a separate function
     */
    public function db_select() {
        if (!$this->obj)
            return false;
        return $this->obj->select_db($this->db);
    }
    /*
     * Get datas stored in the table
     * Data return will be an array if the table has datas else boolean false value will be returned
     */
    public function _select($tbName, $param='*', $condition=null, $order = NULL, $limit=NULL) {
        $query = "SELECT {$param} FROM `{$tbName}` " . ($condition != NULL ? " WHERE " . $condition : NULL);
        $query .= ($order != NULL ? " ORDER BY {$order} " : NULL);
        $query .= ($limit != NULL ? " LIMIT $limit" : NULL);
        if (!$this->obj)
            throw new Exception('Could not connect to database');
        try {
            $this->res = $this->obj->query($query);
            if (!$this->res)
                throw new Exception("Data query failure.");
            if ($this->res->num_rows == 0)
                throw new Exception("No records exist.");
            $row = array();
            while ($rows = $this->res->fetch_assoc())
                array_push($row, $rows);
            return $row;
        } catch (Exception $e) {
            //echo $e->getMessage();
            return false;
        }
    }

}

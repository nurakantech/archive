<?php
class archiveModel {

    function __construct() {
        if(file_exists('database.php'))
            require_once 'database.php';
        $this->db = new DB();
    }
    public function getArchivePost($user = null){     
        $this->db->_connect();
        if ($user != null) {
            $userid = $this->getColumn('users', 'id', array('username' => $user));
            $date = $this->db->_select('blog', 'dateposted,title', " userid = '{$userid}'", ' dateposted DESC ');
        } else {
            $date = $this->db->_select('blog', 'dateposted,title', null, ' dateposted DESC ');
        }
        return $date;
    }
    public function getColumn($tbName, $param, $condition) {
        $this->db->_connect();
        if (!is_array($condition)) {
            $row = $this->db->_select($tbName,$param,$condition);//assumed the condition of selection is a string
        } else {
            if (count($condition) == 1) {
                $key = array_keys($condition);
                if (is_array($key))
                    $key = $key[0];
                $row = $this->db->_select($tbName, $param, " {$key} = '{$condition[$key]}' ");
            } 
        }
        if ($row == false)
            return false;
        if ($param == '*')
            return $row[0];
        return $row[0][$param];
    }

}

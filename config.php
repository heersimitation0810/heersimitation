<?php
class imitation
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "imitation";

    public function __construct() {
        $this->PDO = new PDO("mysql:host=$this->servername;dbname=$this->dbname", "$this->username", "$this->password");
    }

    public function insert($table, $arra) {
        $array = array();
        foreach ($arra as $col => $value) {
            $array[] = ' :' . $col . '';
        }
        $sqlQuery = 'INSERT INTO ' . $table . ' (' . implode(',', array_keys($arra)) . ') VALUES (' . implode(' , ', $array) . ')';
        $statement = $this->PDO->prepare($sqlQuery);
        foreach ($arra as $key => &$value) {
            $statement->bindParam($key, $value, PDO::PARAM_STR);
        }
        $statement->execute();
        $count = $statement->rowCount();
        return $count;
    }

    public function get($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null, $like = null, $custom = null) {
        $sqlQuery = 'SELECT ' . $rows . ' FROM ' . $table;
        
        if ($join != null) {
            $sqlQuery .=  " " . $join;
        }
        if ($where != null) {
            if (count($where) == 1) {
                $sqlQuery .= ' WHERE ' . array_keys($where)[0] . " =:" . array_keys($where)[0];
            }
            if (count($where) > 1) {
                foreach ($where as $key => $value) {
                    $temp[] = $key . "=:" . $key;
                }
                $sqlQuery .= ' WHERE ' . implode(" AND ", $temp);
            }
        }
        if ($order != null) {
            $sqlQuery .= ' ORDER BY ' . $order;
        }
        if ($limit != null) {
            $sqlQuery .= ' LIMIT ' . $limit;
        }
        if ($like != null) {
            $sqlQuery .= 'LIKE %' . $like . '%';
        }
        if($custom != null) {
            $sqlQuery .=  " " . $custom;
        }
        
        $statement = $this->PDO->prepare($sqlQuery);
        if ($where != null) {
            foreach ($where as $key => &$value) {
                $statement->bindParam($key, $value, PDO::PARAM_STR);
            }
        }
        $statement->execute();
        $records = $statement->fetchAll();
        return $records;
    }

    public function update($table, $arra, $whereArray) {
        $setArray = array();
        foreach ($arra as $col => $value) {
            $setArray[] = $col . ' = :' . $col . '';
        }
        $whereClause = '';
        foreach ($whereArray as $key => $where) {
            if ($whereClause != '') {
                $whereClause .= ' AND ';
            }
            $whereClause .= $key . " =:" . $key;
        }
        $sqlQuery = 'UPDATE ' . $table . ' SET ' . implode(',', $setArray) . ' WHERE ' . $whereClause;
        $statement =  $this->PDO->prepare($sqlQuery);
        foreach ($arra as $key => &$value) {
            $statement->bindParam($key, $value, PDO::PARAM_STR);
        }
        foreach ($whereArray as $key => &$value) {
            $statement->bindParam($key, $value, PDO::PARAM_STR);
        }
        $statement->execute();
        $count = $statement->rowCount();
    
        return $count;
    }
    

    public function delete($table, $whereArray) {
        $whereClause = '';
        foreach ($whereArray as $key => $value) {
            if ($whereClause != '') {
                $whereClause .= ' AND ';
            }
            $whereClause .= $key . '= :' . $key;
        }
        $deleteQuery = 'DELETE FROM ' . $table . ' WHERE ' . $whereClause;
        $statement = $this->PDO->prepare($deleteQuery);
        foreach ($whereArray as $key => &$value) {
            $statement->bindParam(":" . $key, $value, PDO::PARAM_INT);
        }
        $statement->execute();
        $count = $statement->rowCount();
        return $count;
    }    
}

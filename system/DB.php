<?php
namespace system;

class DB {
    public $db;
    public $query;
    public $datas = [];

    public function __construct($dsn, $username = null, $password = null)
    {
        try {
            $this->db = new \PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function select($tableName, $columns = '*')
    {
        $columnsToString = '';
        if (is_array($columns)) {
            foreach ($columns as $column) {
                $columnsToString .= $column . ',';
            }
            $columns = trim($columnsToString, ',');
        }
        $this->query = 'SELECT ' . $columns . ' FROM ' . $tableName;
        return $this;
    }

    public function insert($tableName, $values, $updateOnDuplicate = false)
    {
        $columns = '(';
        $datas = '(';
        $update = '';

        $this->datas = $values;

        foreach ($values as $key => $value) {
            $columns .= $key . ",";
            $datas .= " :" . $key . ",";
            $update .= $key . "=" . "VALUES(" . $key . "),";
        }
        $columns = trim($columns, ",") . ')';
        $datas = trim($datas, ",") . ')';
        if ($updateOnDuplicate) {
            $update = trim($update, ',');
            $this->query = 'INSERT INTO ' . $tableName . ' ' . $columns . ' VALUES ' . $datas . ' ON DUPLICATE KEY UPDATE ' . $update;
        } else {
            $this->query = 'INSERT INTO ' . $tableName . ' ' . $columns . ' VALUES ' . $datas;
        }
        return $this;
    }

    public function update($tableName, $values)
    {
        $dataString = '';
        $this->datas = $values;
        foreach ($values as $key => $value) {
            if ($value != null)
                $dataString .= $key . "=:" . $key . ",";
        }
        $this->query = 'UPDATE ' . $tableName . ' SET ' . trim($dataString, ",");
        return $this;
    }

    public function delete($tableName)
    {
        $this->query = 'DELETE FROM ' . $tableName;
        return $this;
    }

    public function where($values)
    {
        $valueString = '';
        // source: http://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
        if (count(array_filter(array_keys($values), 'is_string'))) {
            foreach ($values as $key => $value) {
                $this->datas[$key] = $value;
                $valueString .= $key . "= :" . $key . " AND ";
            }
        } else {
            $this->datas[$values[0]] = $values[2];
            $valueString .= $values[0] . $values[1] . " :" . $values[0] . " AND ";
        }
        $this->query .= ' WHERE ' . substr($valueString, 0, -5);
        return $this;
    }

    public function andWhere($values)
    {
        $valueString = '';
        if (count(array_filter(array_keys($values), 'is_string'))) {
            foreach ($values as $key => $value) {
                $this->datas[$key] = $value;
                $valueString .= $key . "= :" . $key . " AND ";
            }
        } else {
            $this->datas[$values[0]] = $values[2];
            $valueString .= $values[0] . $values[1] . " :" . $values[0] . " AND ";
        }
        $this->query .= ' AND ' . substr($valueString, 0, -5);
        return $this;
    }

    public function orWhere($values)
    {
        $valueString = '';
        if (count(array_filter(array_keys($values), 'is_string'))) {
            foreach ($values as $key => $value) {
                $this->datas[$key] = $value;
                $valueString .= $key . "= :" . $key . " OR ";
            }
        } else {
            $this->datas[$values[0]] = $values[2];
            $valueString .= $values[0] . $values[1] . " :" . $values[0] . " OR ";
        }
        $this->query .= ' OR ' . substr($valueString, 0, -4);
        return $this;
    }

    public function order($value)
    {
        $this->query .= ' ORDER BY ' . $value . ' ';
        return $this;
    }

    public function limit($value)
    {
        $this->query .= ' LIMIT ' . $value . ' ';
        return $this;
    }

    public function offset($value)
    {
        $this->query .= ' OFFSET ' . $value . ' ';
        return $this;
    }

    public function query($query)
    {
        $this->query = $query;
        return $this;
    }

    public function execute()
    {
        $query = $this->query;
        $datas = $this->datas;
        $this->query = null;
        $this->datas = null;

        $obj = $this->db->prepare($query);

        if (stripos($query, 'INSERT') === 0) {
            $obj->execute($datas);
            return $this->db->lastInsertId();
        } elseif (stripos($query, 'SHOW') === 0) {
            return $this->db->query($query);
        }

        $obj->execute($datas);
        return $obj;
        
    }
}

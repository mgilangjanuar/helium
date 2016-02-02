<?php
namespace system;

class BaseRecord extends BaseModel {

    public $_cols = [];
    public $isNewRecord;
    private $db;

    public function __construct($options = [])
    {
        $this->_cols = array_flip($this->fields());
        foreach ($this->_cols as $key => $value) {
            $this->_cols[$key] = App::$request->post($this->tableName())[$key];
        }
        
        $this->db = App::$db;
        $this->isNewRecord = true;
        parent::__construct($options);
    }

    public function __get($key)
    {
        $attribute = 'get' . $key;
        if (array_key_exists($key, $this->_cols)) {
            return $this->_cols[$key];
        } elseif (method_exists($this, $attribute)) {
            return $this->$attribute();
        } else {
            throw new \Exception("Can't get $key");
        }
    }

    public function __set($key, $value)
    {
        $attribute = 'set' . $key;
        if (array_key_exists($key, $this->_cols)) {
            $this->_cols[$key] = $value;
        } elseif (method_exists($this, $attribute)) {
            $this->$attribute($value);
        } else {
            throw new \Exception("Can't set $key");
        }
    }

    public function fields()
    {
        $fields = [];
        $datas = App::$db->query("SHOW columns FROM " . $this->tableName() . ";")->execute()->fetchAll();
        foreach ($datas as $data) {
            if ($data['Key'] == 'PRI')
                $fields[] = $data['Field'];
        }
        foreach ($datas as $data) {
            if (! in_array($data['Field'], $fields))
                $fields[] = $data['Field'];
        }
        return $fields;
    }

    public function primaryKeys()
    {
        $fields = [];
        $datas = App::$db->query("SHOW columns FROM " . $this->tableName() . ";")->execute()->fetchAll();
        foreach ($datas as $data) {
            if ($data['Key'] == 'PRI')
                $fields[] = $data['Field'];
        }
        return $fields;
    }

    public function tableName()
    {
        return '';
    }

    public function load($values)
    {
        if ($values == null) return false;

        foreach ($values[$this->tableName()] as $key => $value) {
            if (array_key_exists($key, $this->_cols) || in_array($key, array_keys(get_object_vars($this))) ) {
                $this->$key = $value;
            }
        }
        return true;
    }

    public function beforeSave()
    {
        return true;
    }

    public function save($validation = true)
    {
        if ($validation && $this->validate() == null) return false;

        if ($this->beforeSave()) {
            $lastId = $this->db->insert($this->tableName(), $this->_cols, true)->execute();
            $priCol = key( array_slice( $this->_cols, 0, 1, true ));
            $model = static::find()->where([$priCol => $lastId])->one();
            if ($model != null)
                $this->_cols = $model->_cols;
            return $this;
        }
        return false;
    }

    public function beforeDelete()
    {
        return true;
    }

    public function delete()
    {
        if ($this->beforeDelete()) {
            $this->db->delete($this->tableName())->where($this->_cols)->execute();
            return true;
        }
        return false;
    }

    public static function find()
    {
        $model = static::className();
        $model = new $model;
        $model->db->select($model->tableName());
        return $model;
    }

    public function where($where)
    {
        $this->db->where($where);
        return $this;
    }

    public function andWhere($where)
    {
        $this->db->andWhere($where);
        return $this;
    }

    public function orWhere($where)
    {
        $this->db->orWhere($where);
        return $this;
    }

    public function limit($num)
    {
        $this->db->limit($num);
        return $this;
    }

    public function order($value)
    {
        $this->db->order($value);
        return $this;
    }

    public function offset($num)
    {
        $this->db->offset($num);
        return $this;
    }

    public function one()
    {
        $datas = $this->db->execute()->fetch();
        if ($datas == null) return null;
        foreach ($datas as $key => $value) {
            if (array_key_exists($key, $this->_cols))
                $this->_cols[$key] = $value;
        }
        $this->isNewRecord = false;
        return $this;
    }

    public function all()
    {
        $model = static::className();
        $models = [];
        $datas = $this->db->execute()->fetchAll();
        if ($datas == null) return [];
        foreach ($datas as $row) {
            $appendModel = new $model;
            foreach ($row as $key => $value) {
                if (array_key_exists($key, $appendModel->_cols))
                    $appendModel->_cols[$key] = $value;
            }
            $appendModel->isNewRecord = false;
            $models[] = $appendModel;
        }
        return $models;
    }

    public function validate($datas = [])
    {
        if ($datas == null)
            $datas = array_merge($this->_cols, get_object_vars($this));
        return parent::validate($datas);
    }
    
}

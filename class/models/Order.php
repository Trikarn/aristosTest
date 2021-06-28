<?php

namespace Classes\Models;

use Classes\Database\Database;

class Order extends Database
{
    private $id;
    private $created_at;
    public $params = [];

    function __construct($params = [])
    {
        $this->created_at = time();
        $this->params = $params;
    }

    public function saveDataDB()
    {
        $created_at = $this->created_at;
        $params = json_encode($this->params);

        $db = $this->setConnection();
        $db->exec('CREATE TABLE if not exists orders (id INTEGER PRIMARY KEY AUTOINCREMENT , created_at INTEGER, params text)');

        $stmt = $db->prepare("INSERT INTO orders (created_at, params) VALUES (:created_at, :params)");
        $stmt->bindValue(':created_at', $created_at, SQLITE3_INTEGER);
        $stmt->bindValue(':params', $params);
        $result = $stmt->execute();

        return;
    }

    public function getDataDB()
    {
        $db = $this->setConnection();

        $query = $db->query('SELECT * FROM orders');

        $result = [];
        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $row['params'] = json_decode(json_encode($row['params']),true);
            $result[] = $row;
        }

        return $result;
    }

    public function removeParams()
    {
        unset($this->params);
        return;
    }

    public function setParam($path, $value)
    {
        //Dot access - был очень сложен для меня, => скопировал с StackOverFlow
        $arr = &$this->params; 
        $keys = explode('.', $path);
    
        foreach ($keys as $key) {
            $arr = &$arr[$key];
        }
        $arr = $value;

        return;
    }

    public function unsetParam($path)
    {
        
        $arr = &$this->params; 
        $keys = explode('.', $path);
        $lastKey = array_pop($keys);
    
        foreach ($keys as $key) {
            $arr = &$arr[$key];
        }
        unset($arr[$lastKey]);
        
        return;
    }

    public function unsetParams($path)
    {
        $deletions = [];
        
        if(is_array($path)) {
            //Массив значений
            $deletions = $path;
        } else {
            $keys = explode(',',$path);
            if(count($keys) != 0) {
                //Несколько удалений
                $deletions = $keys;
            } 
        }

        foreach($deletions as $deletion) {
            $this->unsetParam($deletion);
        }

        return;
    }

    public function getParam($path)
    {
        $arr = &$this->params; 
        $keys = explode('.', $path);
    
        foreach ($keys as $key) {
            $arr = &$arr[$key];
        }

        return $arr;
    }

    public function getData()
    {
        $result = [
            'createad_at' => $this->created_at,
            'params' => $this->params
        ];

        return $result;
    }

    
}
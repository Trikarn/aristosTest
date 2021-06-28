<?php

namespace Classes\Models;

use Classes\Database\Database;

class Order extends Database
{
    private $id;
    private $created_at;
    public $params = [];

    function __construct($created_at, $params)
    {
        $this->created_at = $created_at;
        $this->params = $params;
    }

    public function saveDataDB()
    {
        $created_at = $this->created_at;
        $params = $this->params;

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
        $deletions = [];
        
        if(is_array($path)) {
            //Массив значений
            $deletions = $path;
        } else {
            $keys = explode(',',$path);
            if(count($keys) != 0) {
                //Несколько удалений
                $deletions = $keys;
            } else {
                // Одно удаление
                $deletions = $path;
            }
        }

        foreach($deletions as $deletion) {
            $arr = &$this->params; 
            $keys = explode('.', $deletion);
            $lastKey = array_pop($keys);
        
            foreach ($keys as $key) {
                $arr = &$arr[$key];
            }
            unset($arr[$lastKey]);
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
<?php

namespace Classes\Models;

use Classes\Database\Database;

class Product extends Database
{
    private $id;
    private $title;
    private $created_at;
    private $params;

    function __construct($title, $created_at, $params)
    {
        $this->title = $title;
        $this->created_at = $created_at;
        $this->params = $params;
    }

    public function saveDataDB()
    {
        $title = $this->title;
        $created_at = $this->created_at;
        $params = $this->params;

        $db = $this->setConnection();
        $db->exec('CREATE TABLE if not exists products (id INTEGER PRIMARY KEY AUTOINCREMENT , title STRING, created_at INTEGER, params text)');

        $stmt = $db->prepare("INSERT INTO products (title, created_at, params) VALUES (:title, :created_at, :params)");
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':created_at', $created_at, SQLITE3_INTEGER);
        $stmt->bindValue(':params', $params);
        $result = $stmt->execute();

        return;
    }

    public function getDataDB()
    {
        $db = $this->setConnection();

        $query = $db->query('SELECT * FROM products');

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
}
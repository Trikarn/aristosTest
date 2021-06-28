<?php

namespace Classes\Database;

abstract class Database

{
    final public function setConnection($db = 'database/testTask.sqlite')
    {
        $database = new \SQLite3($db);
        if (!$database) exit("Не удалось создать базу данных!");
        return $database;
    }

    abstract public function saveDataDB();

    abstract public function getDataDB();

    abstract function removeParams();


}


?>
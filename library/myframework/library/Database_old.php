<?php

namespace sys\core;

abstract class Database extends Config
{
    protected $conn;
    protected $conn_str;
    protected $db_prefix = self::DB_PREFIX;
    protected $charset = self::DB_CHARSET;
    protected $error_supress = false;
    protected $last_query;
    protected $fetch_query;
    protected $fetch_methods = [
        'FETCH_ASSOC' => \PDO::FETCH_ASSOC,
        'FETCH_BOTH' => \PDO::FETCH_BOTH,
        'FETCH_NUM' => \PDO::FETCH_NUM
    ];

    abstract public function directQuery($query);

    abstract public function query($query, $params = array());

    abstract public function fetch($query = null, $method = 'FETCH_ASSOC');

    abstract public function fetchObject($query = null);

    abstract public function fetchAll($query = null, $method = 'FETCH_ASSOC');

    abstract public function result($column = null, $query = null, $method = 'FETCH_ASSOC');

    abstract public function resultObject($column = null, $query = null);

    abstract public function numRows($query = null);

    abstract public function lastInsertId();

    abstract protected function setupParams($params);

    abstract protected function cleanParams($query, $params);

    abstract protected function findKey($params, $val);

    abstract protected function errorSupress($state = true);

    abstract protected function errorHandler($error);
}

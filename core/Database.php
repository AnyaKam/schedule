<?php

class DB
{
    private static $_instance = null;

    private static $host = 'localhost';
    private static $database = 'schedule_local';
    private static $user = 'root';
    private static $password = '';
    
    private function __construct () {        
        $this->_instance = new PDO(
            'mysql:host=' . self::$host . ';dbname=' . self::$database,
            self::$user,
            self::$password,
            [PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }

    private function __clone () {}
    private function __wakeup () {}

    public static function getInstance()
    {
        if (self::$_instance != null) {
            return self::$_instance;
        }

        return new self;
    }


    /**
    * @param string $query SQL-запрос
    * @param string $const нужный режим 
    * @return array
    */

    public function get_db_data($query, $const = '') {
        if ($const == 'FETCH_COLUMN') {
            $result = $this->_instance->query($query)->fetchAll(PDO::FETCH_COLUMN);
        } elseif ($const == 'FETCH_UNIQUE') {
            $result = $this->_instance->query($query)->fetchAll(PDO::FETCH_UNIQUE);
        }

        return $result;
    }

    /**
    * @param string $query SQL-запрос
    * @param array $query_array параметры запроса
    * @param string $const нужный режим 
    * @return array
    */

    public function get_db_data_with_array($query, $query_array, $const = '') {
        $data = $this->_instance->prepare($query);
        $data->execute($query_array);
        if ($const == 'FETCH_COLUMN') {
            $result = $data->fetchAll(PDO::FETCH_COLUMN);
        } elseif ($const == 'FETCH_UNIQUE') {
            $result = $data->fetchAll(PDO::FETCH_UNIQUE);
        }
    }

    /**
    * @param string $query SQL-запрос
    * @param array $params параметры запроса
    * @return array
    */

    public function insert_into_db($query, $params) {
        $data = $this->_instance->prepare($query);  
        $data->execute($params);
    }
}
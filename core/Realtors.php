<?php

class Realtors {
    public $id = '';
    public $customer_name = '';

    function __construct() {

    }

    static function get_realtors() {
        global $db;
        $query = 'SELECT * FROM `realtors`';
        $realtors_db = $db->get_db_data($query, 'FETCH_UNIQUE');

        return $realtors_db;
    }
}

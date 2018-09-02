<?php

class Customers {
    public $id = '';
    public $customer_name = '';

    function __construct() {

    }

    static function get_customers() {
        global $db;
        $query = 'SELECT * FROM `customers`';
        $customers_db = $db->get_db_data($query, 'FETCH_UNIQUE');

        return $customers_db;
    }
}
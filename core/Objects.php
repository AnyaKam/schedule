<?php
namespace Objects;

class Objects {
    public $id = '';
    public $customer_name = '';

    function __construct() {

    }

    static function get_objects_name() {
        global $db;
        $query = 'SELECT * FROM `objects`';
        $objects_db = $db->get_db_data($query, 'FETCH_UNIQUE');

        return $objects_db;
    }
}

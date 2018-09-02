<?php

class Schedule {
    public $id = '';
    public $customer_name = '';

    function __construct() {

    }

    static function get_schedule() {
        global $db;
        $query = 'SELECT * FROM `schedule` WHERE timestamp >= ' . strtotime('8:00') . ' ORDER BY `timestamp` ASC';
        $schedule_db = $db->get_db_data($query, 'FETCH_UNIQUE');

        return $schedule_db;
    }
}

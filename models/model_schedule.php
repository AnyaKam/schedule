<?php

class Model_Schedule extends Model
{
    /**
    * Получаем все необходимые данные для вывода страницы с расписанием
    * @return array Данные для шаблона
    */
    public function get_data()
    {   
        $objects_list = Objects::get_objects_name();
        $data[] = array();
        $data['object_names'] = '';
        $data['object_options'] = '';

        foreach ($objects_list as $key => $customer_data) {
            $data['object_names'] .= fn_load_template('views/table_header.html', array(       
                '{table_header}' => '<th>' . $customer_data['object_name'] . '</th>'
            ));
            $object_array[$key] = $customer_data['object_name'];
            $data['object_options'] .= '<option value="' . $key . '">' . $customer_data['object_name'] . '</options>';
        }
        
        $realtor_array = Realtors::get_realtors();
        $customers_array = Customers::get_customers();
        $schedule_array = Schedule::get_schedule();

        $data['realtor_options'] = '';
        foreach ($realtor_array as $realtor_id => $realtor_data) {
            $data['realtor_options'] .= '<option value=' . $realtor_id . ' name="schedule_data[realtor]">' . $realtor_data['realtor_name'] . '</option>';
        }

        $data['customer_options'] = '';
        foreach ($customers_array as $key => $customer_data) {
            $data['customer_options'] .= '<option value="' . $key .'" name="schedule[customer_id][]">' . $customer_data['customer_name'] . '</option>';
        }

        $week = fn_get_week_with_dates();
        foreach ($week as $day => $day_slot) {
            $time_slots[$day] = '';
            foreach ($day_slot as $slot_id => $timeslot) {
                if ($slot_id != 11) {
                    $time_slots[$day] .= '<option value="' . $timeslot . '">' . date('H:i', $timeslot) . '-' . date('H:i', $day_slot[$slot_id+1]) . '</option>';
                }
            }
        }

        foreach ($schedule_array as $schedule_id => $schedule_data) {
            $schedule_objects[$schedule_data['object_id']] = $schedule_data['object_id'];
        }

        if (isset($schedule_array) && !empty($schedule_array)) {
            foreach ($schedule_array as $item_id => $schedule_data) {
                $schedule_tmpl[$schedule_data['object_id']][$schedule_data['timestamp']] = '<div class="schedule_data"><p>' . date('H:i', $schedule_data['timestamp']) . '-' . date('H:i', ($schedule_data['timestamp'] + 3600)) . '</p><p>Realtor: ' . $realtor_array[$schedule_data['realtor_id']]['realtor_name'] . '</p>';
                $customer_ids = explode(',', $schedule_data['customer_ids']);
                $customer_tmpl = 'Customers:<br><ul>';
                foreach ($customer_ids as $key => $customer_id) {
                    $customer_tmpl .= '<li>' . $customers_array[$customer_id]['customer_name'] . '</li>';
                }
                $customer_tmpl .= '</ul>';
                $schedule_tmpl[$schedule_data['object_id']][$schedule_data['timestamp']] .= $customer_tmpl . '</div>';
            }
        }

        $data['table_content'] = '';
        foreach ($week as $day => $day_slot) {
            $data['table_content'] .= '<tr><td>' . $day . '</td>';
            foreach ($object_array as $object_id => $object_name) {
                $data['table_content'] .= '<td>';
                $obj_tmpl = '';
                if (isset($schedule_objects)) {
                    if (in_array($object_id, $schedule_objects)) {
                        foreach ($schedule_tmpl[$object_id] as $timestamp => $tmpl_data) {
                            if (in_array($timestamp, $day_slot)) {
                                $obj_tmpl .= $tmpl_data;
                            }
                        }
                    }
                }
                $data['table_content'] .= $obj_tmpl;
                $data['table_content'] .= '</td>';
            }
        }

        $timeslots = fn_get_time_slots();
        $data['timeslots'] = '';
        foreach ($timeslots as $key => $time) {
            if ($key != 10) {
                $data['timeslots'] .= '<option value="' . $time . '">' . $time . '-' . $timeslots[$key+1] . '</option>';
            }
        }

        return $data;
        
    }

    /**
    * @param array $params array 
    * @return array Список ошибок/ bool Без ошибок
    */
    
    public function save_data($params) {

        $timestamp = strtotime($params['calendar'] . ' ' . $params['time']);
        global $db;

        $schedule_query = 'SELECT `id` FROM `schedule` WHERE `timestamp` = :timestamp AND `object_id` = :object';
        $schedule_query_array = array(
            'timestamp' => $timestamp,
            'object' => $params['object']
        );
        $is_empty_object = $db->get_db_data_with_array($schedule_query, $schedule_query_array, 'FETCH_UNIQUE');

        if($is_empty_object != NULL) {
            $errors['reserved_object'] = true;
        } else {

            $realtor_query = 'SELECT `id` FROM  `schedule` WHERE `timestamp` = :timestamp AND `realtor_id` = :realtor_id';
            $realtor_array = array(
                'timestamp' => $timestamp,
                'realtor_id' => $params['realtor_id']
            );
            $is_empty_realtor = $db->get_db_data_with_array($realtor_query, $realtor_array, 'FETCH_UNIQUE');

            if ($is_empty_realtor != NULL) {
                $errors['reserved_realtor'] = true;var_dump('Realtor has another meeting for this time');
            } else {
                $customer_query = 'SELECT `customer_ids` FROM `schedule` WHERE `timestamp` = :timestamp';
                $customer_query_array = array(
                    'timestamp' => $timestamp
                );
                $customers_db = $db->get_db_data_with_array($customer_query, $customer_query_array, 'FETCH_UNIQUE');
                $customers_array = array();
                if ($customers_db != NULL) {
                    foreach ($customers_db as $key => $customer_ids) {
                        $customers_array = array_merge($customers_array, explode(',', $customer_ids));
                    }
                    $customers_array = array_unique($customers_array);
                }

                $params['customers'] = array_unique($params['customers']);
                $customers_for_new_item = array();
                foreach ($params['customers'] as $key => $customer_id) {
                    if (!in_array($customer_id, $customers_array)) {
                        $customers_for_new_item[] = $customer_id;
                    } else {
                        $declined_customers[] = $customer_id;
                    }
                }
                
                if (isset($declined_customers) && !empty($declined_customers)) {
                    $errors['declined_customers'] = $declined_customers;
                }

                if (!empty($customers_for_new_item)) {
                    $customers_for_new_item = '"' . implode(',', $customers_for_new_item) . '"';
                    $insert_keys = array('timestamp', 'realtor_id', 'object_id', 'customer_ids');

                    $insert_data = array(
                        'timestamp' => $timestamp, 
                        'realtor_id' => $params['realtor_id'], 
                        'object_id' => $params['object'], 
                        'customer_ids' => $customers_for_new_item
                    );  
 
                    $query = 'INSERT INTO `schedule` (' . implode(',', $insert_keys) . ') VALUES (:timestamp, :realtor_id, :object_id, :customer_ids)';
                    $customers_db = $db->insert_into_db($query, $insert_data);
                }
            }
        }

        if (isset($errors)) {
            return ($errors);
        } else {
            return true;
        }
    }
}
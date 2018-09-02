<?php

class Model_Add_customer extends Model
{
    public function get_data()
    {   
        global $db;

        $customers_array = Customers::get_customers();

        $data['customers'] = '';
        foreach ($customers_array as $customer_id => $customer_data) {
            $data['customers'] .= '<option value="' . $customer_id .'" name="schedule[customer_id][]">' . $customer_data['customer_name'] . '</option>';
        }
        
        return $data['customers'];
        
    }
}
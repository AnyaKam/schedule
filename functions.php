<?php

/**
* @param string $templatename Имя необходимого шаблона
* @param array $tpltags плейсхолдеры
* @return $out строка для вывода шаблона
*/

if (!function_exists('fn_load_template')) {
    function fn_load_template($templatename, $tpltags) {
        $out = file_get_contents($templatename);
        $out = strtr($out, $tpltags);

        return $out;
    }
}

/**
* Получим массив с датами
* @return $week_with_slots массив с датами
*/

if (!function_exists('fn_get_week_with_dates')) {
    function fn_get_week_with_dates() {
        $id = 0;
        $week_array = array(strtotime('8:00'));
        while ($id < 6) {
            $id +=1;
            $week_array[] = strtotime('+' . $id . ' day 8:00');
        }
        foreach ($week_array as $day => $day_time) {
            $id = 1;
            $day_id = date('D d.m.y', $day_time);
            $week_with_slots[$day_id][$id] = $day_time;

            while (count($week_with_slots[$day_id]) != 11) {
                $id += 1;
                $day_time += 3600;
                $week_with_slots[$day_id][$id] = $day_time;
            }
        }

        return $week_with_slots;
    }
}

/**
* @return $time_slots Массив с временем
*/
if (!function_exists('fn_get_time_slots')) {
    function fn_get_time_slots() {
        $time_slots[] = '8:00';
        $time_slots[] = '9:00';
        $time_slots[] = '10:00';
        $time_slots[] = '11:00';
        $time_slots[] = '12:00';
        $time_slots[] = '13:00';
        $time_slots[] = '14:00';
        $time_slots[] = '15:00';
        $time_slots[] = '16:00';
        $time_slots[] = '17:00';
        $time_slots[] = '18:00';

        return $time_slots;
    }
}

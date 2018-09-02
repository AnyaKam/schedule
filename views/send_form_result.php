<?php 

if (is_bool($data)) {
    echo('<p>A new item was added to the schedule</p>');
} else {
    if (isset($data['reserved_object'])) {
        echo('<p>The object is reserved for chosen time. Please, back to the form and choose another time</p>');
    } elseif (isset($data['reserved_realtor'])) {
        echo('<p>The realtor has another meeting in chosen time. Please, choose another realtor</p>');
    } elseif (isset($data['declined_customers'])) {
        echo('<p>Some of the users have another meetings in this time. They were not added to this meeting.</p>');
    }
}

?>
<a href="/schedule">Back to the schedule</a>
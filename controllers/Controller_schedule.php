<?php

class Controller_Schedule extends Controller
{

    function __construct()
    {
        $this->model = new Model_Schedule();
        $this->view = new View();
    }
    
    function action_index()
    {
        $data = $this->model->get_data();       
        $this->view->generate('schedule_view.php', 'template_view.php', $data);
    }

    function action_send_form() {
        $params = $_REQUEST;
        $data = $this->model->save_data($params);
        $this->view->generate('send_form_result.php', 'template_view.php', $data);
    }
}
<?php

class Controller_add_customer extends Controller
{

    function __construct()
    {
        $this->model = new Model_Add_customer();
        $this->view = new View();
    }
    
    function action_index()
    {
        $data = $this->model->get_data();     
        
        $this->view->generate('add_customer_view.php', 'template_view.php', $data);
    }
}
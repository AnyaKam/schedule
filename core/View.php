<?php
namespace View;

class View
{
    
    function generate($content_view, $template_view, $data = null)
    {   
        include 'views/'.$template_view;
    }
}
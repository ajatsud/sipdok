<?php

// coding pass

class login_page_view
{
    function output($model)
    {
        $errors = $model->get_errors();
        $messages = $model->get_messages();
        
        include __DIR__ . DIRECTORY_SEPARATOR . 'login_page_view_html.php';
    }
}

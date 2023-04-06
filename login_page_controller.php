<?php

// coding pass

class login_page_controller
{
    function index($model)
    {
        return $model;
    }
    
    function login($model)
    {
        return $model->login($_POST['login']['username'], $_POST['login']['password']);
    }
    
    function logout($model)
    {
        return $model->logout();
    }
}

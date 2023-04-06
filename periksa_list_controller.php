<?php

// coding pass

class periksa_list_controller
{
	function index($model)
	{
		return $model;
	}

    function search($model)
    {
        return $model->search($_POST['periksa']['keyword'],
                              $_POST['periksa']['jenkel'],
                              $_POST['periksa']['period']);
    }

    function delete($model)
    {
        return $model->delete($_POST['periksa']['id']);
    }
}

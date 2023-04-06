<?php

// coding pass

class antrian_list_controller
{
	function index($model)
	{
		return $model;
	}

    function search($model)
    {
        return $model->search($_POST['antrian']['keyword'], $_POST['antrian']['jenkel'], $_POST['antrian']['period']);
    }

    function delete($model)
    {
        return $model->delete($_POST['antrian']['id']);
    }
}

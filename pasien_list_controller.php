<?php

// coding pass

class pasien_list_controller
{
	function index($model)
	{
		return $model;
	}

    function search($model)
    {
        return $model->search($_POST['pasien']['keyword'],
                              $_POST['pasien']['jenkel'],
                              $_POST['pasien']['period']);
    }

    function delete($model)
    {
        return $model->delete($_POST['pasien']['id']);
    }
}

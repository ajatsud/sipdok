<?php

// coding pass

class pendaftaran_list_controller
{
	function index($model)
	{
		return $model;
	}

    function search($model)
    {
        return $model->search($_POST['pendaftaran']['keyword'],
                              $_POST['pendaftaran']['jenkel'],
                              $_POST['pendaftaran']['period']);
    }

    function delete($model)
    {
        return $model->delete($_POST['pendaftaran']['id']);
    }
}

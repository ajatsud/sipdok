<?php

// coding pass

class periksa_entry_controller
{
	function index($model)
	{
		return $model;
	}
	
	function edit($model)
	{
        return $model->load($_POST['periksa']['id']);
    }

	function save($model)
	{
		if(empty($_POST['periksa']['id']) && empty($_POST['periksa']['id_pendaftaran']))
			return $model->next();
		else
		    return $model->save($_POST['periksa']);
	}
}

<?php

// coding pass

class pendaftaran_entry_controller
{
    function add($model)
    {
        return $model;
    }

    function edit($model)
    {
        return $model->load($_POST['pendaftaran']['id']);
    }

    function save($model)
    {
        return $model->save($_POST['pendaftaran']);
    }
}

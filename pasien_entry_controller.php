<?php

// coding pass

class pasien_entry_controller
{
    function add($model)
    {
        return $model;
    }

    function edit($model)
    {
        return $model->load($_POST['pasien']['id']);
    }

    function save($model)
    {
        return $model->save($_POST['pasien']);
    }
}

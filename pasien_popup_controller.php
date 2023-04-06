<?php

// coding pass

class pasien_popup_controller
{
    function search($model)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        return $model->search($data['nama']);
    }
}

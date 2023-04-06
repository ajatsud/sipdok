<?php

// coding pass

class pasien_popup_view
{
    function output($model)
    {
        echo json_encode($model->get_records());
    }
}

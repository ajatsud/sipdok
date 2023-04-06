<?php

// coding pass

class pasien_entry_view
{
    function output($model)
    {
        view::render(get_class($this), [
            'record' => $model->get_record(),
            'errors' => $model->get_errors(),
            'messages' => $model->get_messages()
        ]);
    }
}

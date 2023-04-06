<?php

// coding pass

class dashboard_page_view
{
    function output($model)
    {
        view::render(get_class($this), [
            'errors' => $model->get_errors(),
            'messages' => $model->get_messages()
        ]);
    }
}

<?php

// coding pass

class antrian_list_view
{
    function output($model)
    {
		view::render(get_class($this), [
			'keyword' => $model->get_keyword(),
			'jenkel' => $model->get_jenkel(),
			'period' => $model->get_period(),
			'records' => $model->get_records(),
			'errors' => $model->get_errors(),
            'messages' => $model->get_messages()
		]);
    }
}

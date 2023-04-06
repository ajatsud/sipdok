<?php

// coding pass

class dashboard_page_model
{
	private $pdo;
	private $errors;
    private $message;
	
	function __construct($pdo, $errors = [], $messages = [])
	{
		$this->pdo = $pdo;
		$this->errors = $errors;
        $this->messages = $messages;
	}
	
	function get_errors()
	{
		return $this->errors;
	}

    function get_messages()
    {
        return $this->messages;
    }
}

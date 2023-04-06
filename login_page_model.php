<?php

// coding pass

class login_page_model
{
    private $pdo;
    private $errors;
    private $messages;
    
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
    
    function login($username, $password)
    {
        $errors = $this->validate($username, $password);
        
        if($errors)
            return new self($this->pdo, $errors, $this->messages);
            
        try{
            $st = $this->pdo->prepare('
                select username, password
                  from user
                 where username = :username
            ');
            
            $st->execute([
                'username' => $username    
            ]);
            
            $rs = $st->fetch(PDO::FETCH_ASSOC);
            
            if($rs){
                if(password_verify($password, $rs['password'])){
                    $_SESSION['username'] = $username;
                    header('location: /dashboard');
                    exit;
                }else{
                    return new self($this->pdo, ['Invalid user info']);
                }
            }else{
                return new self($this->pdo, ['Invalid user'], $this->messages);
            }
            
        }catch(PDOException $e){
            return new self($this->pdo, [$e->getMessage()], $this->messages);
        }
    }
    
    function validate($username, $password)
    {
        $errors = [];
        
        if(empty($username))
            $errros[] = 'Username tidak boleh kosong';
            
        if(empty($password))
            $errors[] = 'Password tidak boleh kosong';
            
        return $errors;
    }
    
    function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('location: /');
        exit;
    }
}

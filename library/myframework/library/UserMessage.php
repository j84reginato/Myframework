<?php

namespace sys\core;

class UserMessage {

    private $error;
    private $error_message;

    public function __construct()
    {
        $this->error = array();
        $this->error_message = '';
    }

    public function getError()
    {
        if (count($this->error) == 0) {
            return false;
        }
        return true;
    }

    public function getErrorMessage()
    {
        $this->setErrorMessage();
        return $this->error_message;
    }

    public function setError($message)
    {
        $this->error[] = $message;
    }

    public function setErrorMessage()
    {
        $this->error_message = '';
        if (count($this->error) > 0) {
            foreach ($this->error as $value) {
                $this->error_message = $this->error_message . $value . '<br>';
            }
        }
    }    
}

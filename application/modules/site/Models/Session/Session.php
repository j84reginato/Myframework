<?php

namespace objects\session;

use core\Object;
use objects\session\Session;

class Session extends Object
{
    public $nick;
    public $password;

    public function stripVars(Session $obj)
    {
        $this->user = $obj->nick = trim($obj->nick);
        $this->pass = $obj->password = trim($obj->password);
    }

    public function checkPass(Session $obj)
    {
        if (isset($obj->password)) {
            if ($this->pass == $obj->password) {
                $_SESSION['user'] = $obj->nick;
                header('location:' . APP_ROOT . 'admin/home/index');
            } else {
                // Senha incorreta
                header('location:' . APP_ROOT . 'admin/session/index');
            }
        } else {
            // Usuário não encontrado
            header('location:' . APP_ROOT . 'admin/session/index');
        }
    }

    public function logout()
    {
        unset($_SESSION['user']);
        header('location:' . APP_ROOT . 'admin/session/index');
        exit();
    }    
}


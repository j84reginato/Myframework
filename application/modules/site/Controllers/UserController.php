<?php

namespace Application\Site\Controllers;

use sys\core\Controller;
use app\model\UserModel;
use app\object\User;

class UserController extends Controller
{
    public function save()
    {
        $modelUser = new UserModel();
        print_r($modelUser->save(new User('POST')));
    }

    protected function registerUser($userType)
    {
        // Se foi submetido o formulario pelo usuário
        if ($this->getAction() == 'first') {
            if (!$this->hasError()) {
                $this->checkData();
                if (!$this->hasError()) {
                    $this->recordData($userType);
                    $this->unsetSession($userType);
                    $this->assingnTemplateVars(false, $userType);
                    $this->generateHtmlPage(false);
                    exit();
                }
            }
        }
        /* Se inicialização da pagina ou se ocorreu algum erro durante o preencimento dos dados do formulário */
        $this->assingnTemplateVars(true, $userType);
        $this->generateHtmlPage(true);
    }

}
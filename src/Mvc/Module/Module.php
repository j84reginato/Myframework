<?php

/**
 * @package MyFramework
 * @subpackage MVC
 * @category Module
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Mvc\Module;

use j84Reginato\MyFramework\Request\UrlRequest;
use const CONFIG_PATH;

/**
 * Classe abstrata responsável pelo tratamento dos módulos da aplicação.
 */
abstract class Module extends UrlRequest
{
    /**
     * Representa o Módulo contido na url requisitada.
     *
     * @var string
     */
    protected $sModule;

    /**
     * Atributo para controle de decisão do módulo a utilizar.
     *
     * false - Se o usuário informou um módulo durante a requisição;
     * true  - Se o usuário não informou um módulo durante a requisição e
     *         portanto, a aplicação deverá utilizar o módulo padrão;
     *
     * @var boolean
     */
    protected $bOnDefaultModule = true;

    /**
     * Recupera o valor do atributo.
     *
     * @return string
     */
    public function getModule()
    {
        return $this->sModule;
    }

    /**
     * Configura o valor do atributo.
     */
    protected function setModule()
    {
        // Recupera os módulos presentes na aplicação.
        $aApplicationModules = require CONFIG_PATH . 'modules.php';

        foreach ($aApplicationModules as $sModule) {
            $this->findRequestedModule($sModule);
        }

        $this->sModule = empty($this->sModule)
            ? $aApplicationModules[0]
            : $this->sModule;

        if (! defined('APP_MODULE')) {
            define('APP_MODULE', $this->sModule);
        }
    }

    /**
     * Verifica se o usuário informou um módulo durante a requisição e, se este
     * for o caso, configura o atributo sModule com este valor, em seguida,
     * define o atributo bOnDefaultModule como false.
     *
     * @param string $sModule
     */
    private function findRequestedModule($sModule)
    {
        if ($this->bOnDefaultModule && $this->aUrl[0] == mb_strtolower($sModule, 'UTF-8')) {
            $this->sModule = $sModule;
            $this->bOnDefaultModule = false;
        }
    }

}

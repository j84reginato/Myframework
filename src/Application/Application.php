<?php

/**
 * @package MyFramework
 * @subpackage Application
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Application;

use j84Reginato\MyFramework\Auth\Auth;
use j84Reginato\MyFramework\Mvc\Controller\Controller;
use j84Reginato\MyFramework\Mvc\View\View;
use j84Reginato\MyFramework\Request\Request;

/**
 * Classe responsável por configurar e realizar o carregamento da aplicação.
 */
class Application
{
    /**
     * Representa uma única instancia desta clase (Singleton instance).
     *
     * @var self
     */
    private static $oInstance;

    /**
     * Este construtor usa o Design Pattern Singleton.
     * Sua declaração como privada previne que uma instância desta classe seja
     * criada externamente à classe através do operador "new".
     */
    private function __construct()
    {
    }

    /**
     * Método clone do tipo privado previne a clonagem da instância da classe.
     */
    private function __clone()
    {
    }

    /**
     * Método unserialize do tipo privado para prevenir a desserialização da
     * instância dessa classe.
     */
    private function __wakeup()
    {
    }

    /**
     * Retorna uma instância única desta classe (Singleton instance).
     *
     * @return self
     */
    public static function getInstance()
    {
        if (empty(self::$oInstance)) {
            self::$oInstance = new self;
        }
        return self::$oInstance;
    }

    /**
     * Realiza as configurações gerais do sistema.
     */
    public function setup()
    {
        // Instancia os objetos necessários.
        $this->oRequest = Request::getInstance();
        $this->oAuth = Auth::getInstance();

        // Realiza a inicialização do MVC.
        $this->oController = Controller::getInstance();
        $this->oView = View::getInstance();
    }

    /**
     * Roda a aplicação.
     */
    public function run()
    {
        $this->oController->run($this);
    }

    /**
     * Recupera o objeto solicitado.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->oRequest;
    }

    /**
     * Recupera o objeto solicitado.
     *
     * @return Auth
     */
    public function getAuth()
    {
        return $this->oAuth;
    }

    /**
     * Recupera o objeto solicitado.
     *
     * @return Controller
     */
    public function getController()
    {
        return $this->oController;
    }

    /**
     * Recupera o objeto solicitado.
     *
     * @return View
     */
    public function getView()
    {
        return $this->oView;
    }

}

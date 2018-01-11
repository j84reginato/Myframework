<?php

namespace Myframework\MMVC;

/**
 * Classe que manipula a requisição url do usuário e envia a chamada para a
 * execução do Controller/action correspondente.
 */
class Controller extends Module
{
    private $url;
    private $urlArray;
    private $currentController;

    protected $module;
    protected $controller;
    protected $action;
    protected $params;

    // ------------------------------------------------------------------------

    /**
     * Roda a aplicação
     */
    public function run()
    {
        //Bootstrap::setup();
        $this->setUrl();
        $this->setUrlArray();
        $this->setModule();
        $this->setController();
        $this->setAction();
        $this->setParams();

        $controllerName = 'Application\\'
                          . ucfirst($this->module)
                          . '\\Controllers\\'
                          . ucfirst($this->controller) . 'Controller';

        $actionName = $this->action;

        // Realiza a validação do Controller e da Action
        $this->controllerValidation($controllerName);
        $this->actionValidation($controllerName, $actionName);

        // Instancia o Controller e executa a action solicitada
        $this->currentController = new $controllerName();
        $this->currentController->$actionName();
    }

    // ------------------------------------------------------------------------

    /**
     *
     */
    private function setUrl()
    {
        $urlRequest = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING);
        $this->url = isset($urlRequest) ? $urlRequest : 'home/index';
    }

    // ------------------------------------------------------------------------

    /**
     *
     */
    private function setUrlArray()
    {
        $this->urlArray = explode('/', $this->url);
    }

    // ------------------------------------------------------------------------

    /**
     *
     */
    private function setModule()
    {
        foreach ($this->modules as $key => $value) {
            if ($this->onDefaultModule && $this->urlArray[0] == $key) {
                $this->module = $value;
                $this->onDefaultModule = false;
            }
        }
        $this->module = empty($this->module)
                        ? $this->defaultModule
                        : $this->module;

        if (!defined('APP_MODULE')) {
            define('APP_MODULE', $this->module);
        }
    }

    // ------------------------------------------------------------------------

    /**
     *
     * @return type
     */
    public function getModule()
    {
        return $this->module;
    }

    // ------------------------------------------------------------------------

    /**
     *
     */
    private function setController()
    {
        $this->controller = $this->onDefaultModule
            ? $this->urlArray[0]
            : (!isset($this->urlArray[1]) || is_null($this->urlArray[1]) || empty($this->urlArray[1])
                ? 'home'
                : $this->urlArray[1]);
    }

    // ------------------------------------------------------------------------

    /**
     *
     * @return type
     */
    public function getController()
    {
        return $this->controller;
    }

    // ------------------------------------------------------------------------

    /**
     *
     */
    private function setAction()
    {
        $this->action = $this->onDefaultModule
            ? (!isset($this->urlArray[1]) || is_null($this->urlArray[1]) || empty($this->urlArray[1])
                ? 'index'
                : $this->urlArray[1])
            : (!isset($this->urlArray[2]) || is_null($this->urlArray[2]) || empty($this->urlArray[2])
                ? 'index'
                : $this->urlArray[2]);
    }

    // ------------------------------------------------------------------------

    /**
     *
     * @return type
     */
    public function getAction()
    {
        return $this->action;
    }

    // ------------------------------------------------------------------------

    /**
     *
     */
    private function setParams()
    {
        if ($this->onDefaultModule) {
            unset($this->urlArray[0], $this->urlArray[1]);
        } else {
            unset($this->urlArray[0], $this->urlArray[1], $this->urlArray[2]);
        }
        if (end($this->urlArray) == null) {
            array_pop($this->urlArray);
        }
        if (empty($this->urlArray)) {
            $this->params = array();
        } else {
            foreach ($this->urlArray as $value) {
                $params[] = $value;
            }
            $this->params = $params;
        }
    }

    // ------------------------------------------------------------------------

    /**
     *
     * @param type $index
     * @return type
     */
    public function getParams($index)
    {
        return isset($this->params[$index]) ? $this->params[$index] : 'NULL';
    }

    // ------------------------------------------------------------------------

    /**
     *
     * @param type $class
     */
    private function controllerValidation($class)
    {
        if (!class_exists($class)) {
            header('HTTP/1.0 404 Not Found');
            define('ERROR', 'O controller ' . ucfirst($this->controller) . 'Controller não existe');
            include "application/{$this->module}/views/_error/404_error.php";
            exit();
        }
    }

    // ------------------------------------------------------------------------

    /**
     *
     * @param type $class
     * @param type $method
     */
    private function actionValidation($class, $method)
    {
        if (!method_exists($class, $method)) {
            header('HTTP/1.0 404 Not Found');
            define('ERROR', 'O método ' . ucfirst($this->controller) . 'Controller/' . $this->action . ' não existe');
            include "application/{$this->module}/views/_error/404_error.php";
            exit();
        }
    }

    protected function view($render = null)
    {
        $pageView = new View();
        $pageView->view($render);
    }
}

<?php

/**
 * @package MyFramework
 * @subpackage MVC
 * @category Controller
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Mvc\Controller;

use j84Reginato\MyFramework\Application\Application;
use j84Reginato\MyFramework\Mvc\Module\Module;
use const APP_PATH;

/**
 * Classe responsável por tratar a requisição url do usuário e enviar a chamada
 * para a execução do "Module/Controller/action" correspondente.
 */
class Controller extends Module
{
    /**
     * Representa o "Controller" contido na url requisitada.
     *
     * @var string
     */
    protected $sController;

    /**
     * Representa a "action" contida na url requisitada.
     *
     * @var string
     */
    protected $sAction;

    /**
     * Representa os parâmetros contidos na url requisitada.
     *
     * @var string | array
     */
    protected $aParams;

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
        $this->setup();
        $this->validator();
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
     * Método responsável por realizar a configuração da camada Controller.
     */
    private function setup()
    {
        $this->setUrlArray();
        $this->setModule();
        $this->setController();
        $this->setAction();
        $this->setParams();
    }

    /**
     * Método responsável por realizar a validação do controller e da action.
     */
    private function validator()
    {
        // Configura o namespace do controller requisitado
        $sClassNameSpace = ucfirst($this->sModule)
                         . '\\Controller\\'
                         . ucfirst($this->sController) . 'Controller';

        // Configura o nome completo da action requisitada
        $sActionName = $this->sAction . 'Action';

        // Realiza a validação do Controller e da Action
        $this->controllerValidation($sClassNameSpace);
        $this->actionValidation($sClassNameSpace, $sActionName);
    }

    /**
     * Método responsável por rodar a aplicação:
     * Instancia o Controller e executa a action requisitada.
     */
    public function run(Application $oApp)
    {
        $sClassFactory = ucfirst($this->sModule)
                       . '\\Controller\\Factory\\'
                       . ucfirst($this->sController) . 'ControllerFactory';

        $sActionName = $this->sAction . 'Action';

        $oController = $sClassFactory::create($oApp);
        $oController->$sActionName();
    }

    /**
     * Configura o valor do atributo.
     */
    private function setController()
    {
        $this->sController = $this->bOnDefaultModule
            ? $this->aUrl[0]
            : ! isset($this->aUrl[1]) || is_null($this->aUrl[1]) || empty($this->aUrl[1])
                ? 'home'
                : $this->aUrl[1];

        if (! defined('APP_CONTROLLER')) {
            define('APP_CONTROLLER', $this->sController);
        }
    }

    /**
     * Recupera o valor do atributo.
     *
     * @return string
     */
    public function getController()
    {
        return $this->sController;
    }

    /**
     * Configura o valor do atributo.
     */
    private function setAction()
    {
        $this->sAction = $this->bOnDefaultModule
            ? ! isset($this->aUrl[1]) || is_null($this->aUrl[1]) || empty($this->aUrl[1])
                ? 'index'
                : $this->aUrl[1]
            : ! isset($this->aUrl[2]) || is_null($this->aUrl[2]) || empty($this->aUrl[2])
                ? 'index'
                : $this->aUrl[2];

        if (! defined('APP_ACTION')) {
            define('APP_ACTION', $this->sAction);
        }
    }

    /**
     * Recupera o valor do atributo.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->sAction;
    }

    /**
     * Configura o valor do atributo.
     */
    private function setParams()
    {
        if ($this->bOnDefaultModule) {
            unset($this->aUrl[0], $this->aUrl[1]);
        } else {
            unset($this->aUrl[0], $this->aUrl[1], $this->aUrl[2]);
        }
        if (end($this->aUrl) == null) {
            array_pop($this->aUrl);
        }
        if (empty($this->aUrl)) {
            $this->aParams = [];
        } else {
            foreach ($this->aUrl as $mValue) {
                $aParams[] = $mValue;
            }
            $this->aParams = $aParams;
        }
    }

    /**
     * Recupera o valor do atributo.
     *
     * @param integer $iIndex
     * @return string
     */
    public function getParams($iIndex = null)
    {
        if (isset($iIndex)) {
            return isset($this->aParams[$iIndex]) ? $this->aParams[$iIndex] : 'null';
        }
        return $this->aParams;
    }

    /**
     * Realiza a validação do "controller" requisistado.
     *
     * @param string $sClass
     */
    private function controllerValidation($sClass)
    {
        if (! class_exists($sClass)) {
            header('HTTP/1.0 404 Not Found');
            define(
                'ERROR',
                'O controller ' . ucfirst($this->sController) . 'Controller ' . 'não existe!'
            );
            include APP_PATH . "/{$this->sModule}/view/_error/404_error.phtml";
            exit();
        }
    }

    /**
     * Realiza a validação da "action" requisistada.
     *
     * @param string $sClass
     * @param string $sMethod
     */
    private function actionValidation($sClass, $sMethod)
    {
        if (! method_exists($sClass, $sMethod)) {
            header('HTTP/1.0 404 Not Found');
            define(
                'ERROR',
                'O método ' . ucfirst($this->sController) . 'Controller/' . $this->sAction . ' não existe!'
            );
            include APP_PATH . "/{$this->sModule}/view/_error/404_error.phtml";
            exit();
        }
    }

}

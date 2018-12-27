<?php

/**
 * @package MyFramework
 * @subpackage Auth
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Auth;

/**
 * Classe responsável por realizar a verificação de autenticação do usuário.
 */
class Auth
{
    /**
     * Atributo indicador do status de login do usuário.
     *
     * @var boolean
     */
    private $bLoggedIn;

    /**
     * Representa uma única instancia desta clase (Singleton instance).
     *
     * @var self
     */
    private static $oInstance;

    /**
     * Verifica se o usuário está logado.
     *
     * Este construtor usa o Design Pattern Singleton.
     * Sua declaração como privada previne que uma instância desta classe seja
     * criada externamente à classe através do operador "new".
     */
    private function __construct()
    {
        if (! $this->checkLoginSession()) {
            $this->rememberMeLogin();
        }
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
     * Retorna o valor do atributo.
     *
     * @return boolean
     */
    public function getLoggedIn()
    {
        return $this->bLoggedIn;
    }

    /**
     * Verifica se há um usuário logado e se a sessão é válida.
     *
     * @return boolean
     */
    public function checkLoginSession()
    {
        $this->bLoggedIn = false;

        if (
            isset($_SESSION['csrfToken']) &&
            isset($_SESSION['loggedUserId']) &&
            isset($_SESSION['loggedUserName']) &&
            isset($_SESSION['loggedUserType'])
        ) {
            $this->bLoggedIn = true;
            return true;
        }

        $this->clearUserSession();
        return false;
    }

    /**
     * Verifica se o usuário foi lembrado e deve entrar sem necessidade de
     * inserir dados de loggin novamente.
     */
    private function rememberMeLogin()
    {
        $loggedUserId = filter_input(INPUT_COOKIE, 'LOGGED_USER_ID');
        $loggedUserName = filter_input(INPUT_COOKIE, 'LOGGED_USER_NAME');
        $loggedUserType = filter_input(INPUT_COOKIE, 'LOGGED_USER_TYPE');

        if (
            isset($loggedUserId) &&
            isset($loggedUserName) &&
            isset($loggedUserName)
        ) {
            $_SESSION['csrfToken'] = md5(uniqid(rand(), true));
            $_SESSION['loggedUserId'] = $loggedUserId;
            $_SESSION['loggedUserName'] = $loggedUserName;
            $_SESSION['loggedUserType'] = $loggedUserType;
            $this->bLoggedIn = true;
        }
    }

    /**
     * clearUserSession.
     */
    private function clearUserSession()
    {
        if (isset($_SESSION['csrfToken'])) {
            unset($_SESSION['csrfToken']);
        }
        if (isset($_SESSION['loggedUserId'])) {
            unset($_SESSION['loggedUserId']);
        }
        if (isset($_SESSION['loggedUserName'])) {
            unset($_SESSION['loggedUserName']);
        }
        if (isset($_SESSION['loggedUserType'])) {
            unset($_SESSION['loggedUserType']);
        }
    }

    /**
     * Verifica se o usuário está logado e, caso este esteja enviando dados
     * (GET ou POST), efetua a verificação do Token.
     *
     * A lógica usada é que o Token deve existir quando um usuário estiver
     * conectado, e, quando houver envio de dados (GET ou POST), ao menos mais
     * 1 parametro deve estar alocado (csrfToken + 1 outro).
     *
     * @return boolean
     */
    public function checkAuth()
    {
        // Se não estiver conectado.
        if (! isset($_SESSION['csrfToken'])) {
            $this->bLoggedIn = false;
            return $this->bLoggedIn;
        }

        $this->bLoggedIn = true;

        // Se houve envio de dados via POST.
        $csrfToken = filter_input(INPUT_POST, 'csrfToken', FILTER_DEFAULT);
        if (is_array(filter_input_array(INPUT_POST)) && count(filter_input_array(INPUT_POST)) > 1 && ! empty($csrfToken)) {
            $this->bLoggedIn = ($csrfToken == $_SESSION['csrfToken']);
        }

        // Se houve envio de dados via POST e o token for inválido.
        if (! $this->bLoggedIn) {
            throw new Exception('Ocorreu um erro durante o envio dos dados. <br>Token expirado!');
        }

        return $this->bLoggedIn;
    }

}

<?php

namespace Application\Site\Helpers;

use Application\Site\Model\User;

class Security
{
    private $data = array(
        'loggedIn' => null,
        'loggedUserType' => null
    );

    public function __set($property, $value)
    {
        $this->data[$property] = $value;
    }

    public function __get($property)
    {
        return $this->data[$property];
    }

    public function __isset($property)
    {
        return isset($this->data[$property]);
    }

    public function __unset($property)
    {
        unset($this->data[$property]);
    }

    /**
     * __construct
     * ------------------------------------------------------------------------
     * Método contrutor
     */
    public function __construct()
    {
        $user = new User();

        if (!$this->checkLoginSession($user)) {
            $this->rememberMeLogin($user);
        }
        $this->assignLoggedUserType($user);
        $this->checkBalance($user);
        $this->checkSuspended($user);

        $this->assignInitialMissingArray($user);

        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            header('location:' . APP_ROOT . 'admin/session');
            exit();
        }
    }

    /**
     * checkLoginSession
     * ------------------------------------------------------------------------
     * Verifica se há um usuário logado e se a sessão é válida
     *
     * @return boolean - true  => usuário está logado
     *                   false => usuário não está logado
     */
    private function checkLoginSession(User $user)
    {
        $userModel = new \app\site\model\UserModel();

        $this->loggedIn = false;
        if (isset($_SESSION['logged_number']) && isset($_SESSION['logged_id']) && isset($_SESSION['logged_pass'])) {
            $user->id = $_SESSION['logged_id'];
            $userModel->get($user);
            if (strspn($user->password, $user->hash) == $_SESSION['logged_number']) {
                $this->loggedIn = true;
                return true;
            }
        }
        return false;
    }

    /**
     * rememberMeLogin
     * ------------------------------------------------------------------------
     * Verifica se o usuário, num acesso anterior, marcou a opção "lembrar-me"
     * e desta forma, deve logar no sistema sem a necessidade de inserir seus
     * dados acesso novamente.
     *
     */
    private function rememberMeLogin(User $user)
    {
        $remember = new \app\site\object\Remember();
        $rememberModel = new \app\site\model\RememberModel();
        $userModel = new \app\site\model\UserModel();

        $cookieRememberCode = filter_input(INPUT_COOKIE, 'REMEMBER_CODE');
        if (!$this->loggedIn && isset($cookieRememberCode)) {
            $remember->rememberCode = preg_replace("/[^a-zA-Z0-9\s]/", '', $cookieRememberCode);
            $user->id = $rememberModel->getUserId($remember);
            if ($user->id != '') {
                $userModel->get($user);
                $_SESSION['logged_id'] = $user->id;
                $_SESSION['logged_pass'] = $user->password;
                $_SESSION['logged_number'] = strspn($user->password, $user->hash);
                $_SESSION['csrftoken'] = md5(uniqid(rand(), true));
                $this->loggedIn = true;
            }
        }
    }

    /**
     * assignLoggedUserType
     * ------------------------------------------------------------------------
     * Configura o atributo "userType" (paciente ou prestador)
     */
    private function assignLoggedUserType(User $user)
    {
        if ($this->loggedIn) {
            if ($user->suspended != 7) {
                $userType = ($user->userType == 1) ? 'provider' : 'patient';
                $this->loggedUserType = $userType;
            }
        }
    }

    /**
     * checkBalance
     * ------------------------------------------------------------------------
     * Checa os débitos do usuário e avalia se este precisa ser suspenso.
     */
    private function checkBalance()
    {
        $settings = \app\site\object\Settings::getInstance();

        // Se a cobrança de taxas e o método de suspensão estiverem ativos
        if ($settings->fee_type == 1 && $settings->fee_disable_acc == 'y' && $this->loggedIn) {
            // Se o débito do usuário for maior ou igual ao máximo permitido e o usuário ainda não tiver sido suspenso
            if (($obj_settings->getSettings('fee_max_debt') <= (-1 * $this->getUserData('balance'))) && $this->getUserData('suspended') != 7) {
                // Atualiza o banco de dados
                $this->updateUserAccess(7);
                // Envia e-mail
                $obj_emailer = new \KdDoctor\classes\EmailHandler();
                $objCurrency = new \KdDoctor\classes\Currency();
                $obj_emailer->assignVars(array(
                    'site_name' => $obj_settings->getSettings('site_name'),
                    'name' => $this->getUserData('name'),
                    'balance' => $objCurrency->printMoney($this->getUserData('balance')),
                    'outstanding' => $obj_settings->getSettings('site_url') . 'outstanding.php'
                ));
                $obj_emailer->email_uid = $this->getUserData('id');
                $obj_emailer->emailSender(
                    $this->getUserData('email'),
                    'suspended_balance.inc.php',
                    $obj_settings->getSettings('site_name') . ' - ' . 'Conta Suspensa'
                );
            }
        }
    }

    /**
     * checkSuspended
     *
     * Verifica se o usuário está suspenso e caso afirmativo redireciona para pagamento
     */
    private function checkSuspended()
    {
        if ($this->getLoggedIn()) {
            if (in_array($this->getUserData('suspended'), array(5, 6, 7))) {
                header('location: message.php');
                exit();
            }
        }
    }

    /**
     * checkAuth
     *
     * Verifica se o usuário está logado e caso este esteja enviando
     * dados (GET ou POST), efetua a verificação do Token
     *
     * A lógica usada é que o Token deve existir quando um usuário estiver conectado,
     * e, quando houver envio de dados (GET ou POST), ao menos + 1 parametro deve estar alocado (csrftoken + 1 outro)
     *
     * @return attribute - Se passou na validação retorna o parâmetro logged_in como verdadeiro.
     */
    public function checkAuth()
    {
        $post_csrftoken = filter_input(INPUT_POST, 'csrftoken');

        // Se estiver conectado
        if (isset($_SESSION['csrftoken'])) {
            // Se houve envio de dados (GET ou POST)
            if (count(filter_input_array(INPUT_POST)) > 1) {
                $this->setLoggedIn($post_csrftoken == $_SESSION['csrftoken']);
            // Se nao houve GET ou POST
            } else {
                $this->setLoggedIn(true);
            }
            // Se houve envio de dados (GET ou POST) e o token for inválido
            if (!$this->getLoggedIn()) {
                $_SESSION['msg_title'] = 'Ocorreu um erro durante o envio dos dados.';
                $_SESSION['msg_body'] = 'Token expirado';
                header('location: message.php');
                exit();
            }
        }
        return $this->getLoggedIn();
    }

    /**
     * checkLoginOnInitialPages
     *
     * Quando houver um requisição url para a home-page (index.php) ou
     * para a página de login (login.php) ou
     * para a página de cadastro de usuário (register_patient_user.php ou register_provider_user.php),
     * antes de iniciar o carregamento da referida página deve-se fazer a verificação
     * se usuário já está previamente logado e, caso afirmativo,
     * interrompe o script e carrega a página inicial de acordo com o perfil logado.
     * - agenda.php, no caso de prestador ou
     * - inicio.php, no caso de paciente
     * (Somente usuários não logados devem acessar index.php, login.php e podem cadastrar novo usuário).
     */
    public function checkLoginOnInitialPages()
    {
        if ($this->getLoggedIn()) {
            $url = ($this->getLoggedUserType() == 'provider') ? 'agenda.php' : 'inicio.php';
            header('location: ' . $url);
            exit();
        }
    }

    /**
     * checkUserTypeOnRegisterUser
     * Caso o usuário não esteja logado, mas não indicou o tipo de usuário (prestador ou paciente) na home-page (modal)
     * interrompe este script e retorna para a home-page (index.php).
     * (Para cadastrar um novo usuário o sistema precisa saber de qual tipo será este usuário - prestador ou paciente)
     */
    final public function checkUserTypeOnRegisterUser()
    {
        if (!$this->logged_in && (!isset($_SESSION['userType']) || $_SESSION['userType'] == '')) {
            $url = 'index.php';
            header('location: ' . $url);
            exit();
        }
    }

    /**
     * setUserTypeOnIndex
     *
     * Configura o array $_SESSION['user_type']
     */
    public function setUserTypeOnIndex() {

        $input_user_type = filter_input(INPUT_POST, 'user_type');
        $input_action = filter_input(INPUT_GET, 'action');

        // Reseta o $_SESSION['user_type'] caso não haja pesquisa na página index.php
        $_SESSION['user_type'] = (isset($input_action) && isset($_SESSION['user_type'])) ? $_SESSION['user_type'] : null;

        // Configura o $_SESSION['user_type'] caso haja seleção no modal da index.php
        if (isset($input_user_type)) {
            $_SESSION['user_type'] = $input_user_type;
        }
    }

    /**
     * assignInitialMissingArray
     * Configura os valores iniciais do atributo $missing
     */
    final private function assignInitialMissingArray()
    {
        $this->setMissing('designation', false);
        $this->setMissing('name', false);
        $this->setMissing('nick', false);
        $this->setMissing('password', false);
        $this->setMissing('repeat_password', false);
        $this->setMissing('email', false);
        $this->setMissing('person_type', false);
        $this->setMissing('cpf_cnpj', false);
        $this->setMissing('register_number', false);
        $this->setMissing('register_type', false);
        $this->setMissing('register_province', false);
        $this->setMissing('cellphone', false);
        $this->setMissing('workphone', false);
        $this->setMissing('birthdate', false);
        $this->setMissing('zip', false);
        $this->setMissing('address', false);
        $this->setMissing('number', false);
        $this->setMissing('neighborhood', false);
        $this->setMissing('city', false);
        $this->setMissing('province', false);
        $this->setMissing('card_flag', false);
        $this->setMissing('card_number', false);
        $this->setMissing('card_expiration_month', false);
        $this->setMissing('card_expiration_year', false);
        $this->setMissing('card_holder_name', false);
        $this->setMissing('card_doc_type', false);
        $this->setMissing('card_doc_number', false);
        $this->setMissing('card_security_code', false);
    }

}

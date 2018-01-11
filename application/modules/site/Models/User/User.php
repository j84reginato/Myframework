<?php

namespace Application\Site\Model;

use System\Entity;

/**
 * Define a Entity User.
 *
 * @author Jonatan Noronha Reginato
 */
class User extends Entity
{
    protected $user_data;
    public $logged_in;
    public $logged_user_type;

    // Dados de formulário
    public $id;
    
    protected $action;
    protected $user_type;
    protected $designation;
    protected $name;
    protected $nick;
    protected $password;
    protected $repeat_password;
    protected $email;
    protected $person_type;
    protected $cpf_cnpj;
    protected $register_type;
    protected $register_number;
    protected $register_province;
    protected $cellphone;
    protected $workphone;
    protected $birthdate;
    protected $zip;
    protected $address;
    protected $number;
    protected $complement;
    protected $neighborhood;
    protected $city;
    protected $province;
    protected $card_flag;
    protected $card_number;
    protected $card_expiration_month;
    protected $card_expiration_year;
    protected $card_holder_name;
    protected $card_doc_type;
    protected $card_doc_number;
    protected $card_security_code;
    protected $nletter;
    protected $terms_check;
    protected $captcha_code;
    protected $recaptcha_code;

    protected $missing;
    protected $errors;
    protected $suspended;
    protected $signup_fee;
    protected $balance;
    protected $hash;
    protected $user_id;
    protected $error_message;
    protected $success_message;

    /************************************************************************************************************************************************
     * Getter's
     ***********************************************************************************************************************************************/

    /**
     * getUserData
     * Retorna todos ou um específico dado do usuário logado
     *
     * @param str $index - Parâmetro que define qual dado do usuário deve-se retornar.
     *                     Se não indicado retorna todos os dados em formato array.
     *                     (padrão = null)
     * @return str/array
     */
    public function getUserData($index = null)
    {
        if (isset($index)) {
            return $this->user_data[$index];
        } else {
            return $this->user_data;
        }
    }

    /**
     * getLoggedIn
     *
     * Retorna o valor do atributo User->logged_in
     *
     * @return boolean
     */
    public function getLoggedIn()
    {
        return $this->logged_in;
    }

    final public function getLoggedUserType()
    {
        return $this->logged_user_type;
    }

    final public function getAction()
    {
        return $this->action;
    }

    final public function getMissing($index)
    {
        if (isset($index)) {
            return $this->missing[$index];
        } else {
            return $this->missing;
        }
    }

    final public function getErrors()
    {
        return $this->errors;
    }

    final public function getSuspended()
    {
        return $this->suspended;
    }

    final public function getSignupFee()
    {
        return $this->signup_fee;
    }

    final public function getBalance()
    {
        return $this->balance;
    }

    final public function getHash()
    {
        return $this->hash;
    }

    final public function getUserId()
    {
        return $this->user_id;
    }

    final public function getErrorMessage()
    {
        $this->setErrorMessage();
        return $this->error_message;
    }

    final public function getSuccessMessage()
    {
        return $this->success_message;
    }

    /************************************************************************************************************************************************
     * Setter's
     ***********************************************************************************************************************************************/

    final private function setUserData($user_data)
    {
        $this->user_data = $user_data;
    }

    /**
     * setLoggedIn
     *
     * Configura o valor do atributo User->user_data
     *
     * @param boolean $logged_in
     */
    final private function setLoggedIn($logged_in)
    {
        $this->logged_in = $logged_in;
    }

    /**
     * setLoggedUserType
     *
     * Configura o valor do atributo User->logged_user_type
     *
     * @param str $logged_user_type - Tipo de usuário ('prestador' ou 'paciente')
     */
    final private function setLoggedUserType($logged_user_type)
    {
        $this->logged_user_type = $logged_user_type;
    }

    final protected function setAction($action)
    {
        $this->action = $action;
    }

    final protected function setMissing($missing_index, $missing_value)
    {
        $this->missing[$missing_index] = $missing_value;
    }

    final protected function setErrors($errors)
    {
        $this->errors[] = $errors;
    }

    final private function setSuspended($suspended)
    {
        $this->suspended = $suspended;
    }

    final private function setSignupFee($signup_fee)
    {
        $this->signup_fee = $signup_fee;
    }

    final private function setBalance($balance)
    {
        $this->balance = $balance;
    }

    final private function setHash()
    {
        $string = '0123456789abcdefghijklmnopqrstuvyxz';
        $hash = '';
        for ($i = 0; $i < 5; $i++) {
            $rand = rand(0, (34 - $i));
            $hash .= $string[$rand];
            $string = str_replace($string[$rand], '', $string);
        }
        $this->hash = $hash;
    }

    final private function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    final private function setErrorMessage()
    {
        $this->error_message = '';
        if (count($this->errors) > 0) {
            $error_message = '';
            foreach ($this->errors as $erro) {
                $error_message = $error_message . $erro . '<br>';
            }
            $this->error_message = 'Atenção, ' . count($this->errors) . ' erros foram encontrados:<br>' . $error_message;
        }
    }

    final private function setSuccessMessage($success_message)
    {
        $this->success_message = $success_message;
    }

    /**
     * checkLoginSession
     * Verifica se há um usuário logado e se a sessão é válida e, caso positivo:
     * - Configura o valor do atributo User->user_data como um array com todos os dados deste usuário;
     * - Configura o valor do atributo User->logged_in como true.
     *
     * @return boolean - true => usuário está logado
     *                   false => usuário não está logado
     */
    private function checkLoginSession()
    {
        $this->setLoggedIn(false);
        if (isset($_SESSION['logged_number']) && isset($_SESSION['logged_id']) && isset($_SESSION['logged_pass'])) {
            $loggedUser = new app\site\object\User;
            $params['id'] = array(':logged_id', $_SESSION['logged_id'], 'int');
            $params['password'] = array(':logged_pass', $_SESSION['logged_pass'], 'str');
            $model_user = new \app\site\model\UserModel;
            $user_data = $model_user->getUser($params);
            if (is_array($user_data) && count($user_data) > 0) {
                if (strspn($user_data['password'], $user_data['hash']) == $_SESSION['logged_number']) {
                    $this->setUserData($user_data);
                    $this->setLoggedIn(true);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * rememberMeLogin
     *
     * Verifica se o usuário foi lembrado e deve entrar sem necessidade de inserir dados de loggin novamente.
     * Caso afirmativo:
     * - Configura o valor do atributo User->user_data como um array com todos os dados deste usuário;
     * - Configura o valor do atributo User->logged_in como true.
     *
     * @global type $objCharacter
     * @global type $objRememberMe
     */
    private function rememberMeLogin()
    {
        $lib_character = new \sys\core\Character();
        $model_remember_me = new \app\site\model\RememberMe();
        $model_user = new \app\site\model\User();

        $cookie_remember_code = filter_input(INPUT_COOKIE, 'REMEMBER_CODE');
        if (!$this->getLoggedIn() && isset($cookie_remember_code)) {
            $user_id = $model_remember_me->getUserId($lib_character->alphanumeric($cookie_remember_code));
            if ($user_id != '') {
                $params['id'] = array(':user_id', $user_id, 'int');
                $user_data = $model_user->getUser($params);
                if (is_array($user_data) && count($user_data) > 0) {
                    $_SESSION['csrftoken'] = md5(uniqid(rand(), true));
                    $_SESSION['logged_id'] = $user_id;
                    $_SESSION['logged_number'] = strspn($user_data['password'], $user_data['hash']);
                    $_SESSION['logged_pass'] = $user_data['password'];
                    $this->setUserData($user_data);
                    $this->setLoggedIn(true);
                }
            }
        }
    }

    /**
     * assignLoggedUserType
     *
     * Configura o atributo tipo de usuário (paciente ou prestador)
     */
    private function assignLoggedUserType()
    {
        if ($this->getLoggedIn()) {
            if ($this->getUserData('suspended') != 7) {
                $user_type = ($this->getUserData('user_type') == 1) ? 'provider' : 'patient';
                $this->setLoggedUserType($user_type);
            }
        }
    }

    /**
     * checkBalance
     *
     * Checa os débitos do usuário e avalia se este precisa ser suspenso.
     *
     * @global obj $objSystem
     */
    private function checkBalance()
    {
        $obj_settings = \app\site\object\Settings::getInstance();

        // Se a cobrança de taxas e o método de suspensão estiverem ativos
        if ($obj_settings->getSettings('fee_type') == 1 && $obj_settings->getSettings('fee_disable_acc') == 'y' && $this->getLoggedIn()) {
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

    /**
     * checkBlank
     * Verifica se foram deixados campos sem preenchimento
     * Caso afirmativo configura os atributos $missing e $errors
     */
    protected function checkBlank()
    {
        if (empty($this->designation)) {
            $this->setMissing('designation', true);
            $this->setErrors('Por favor, informe a saudação de tratamento desejada!');
        }
        if (empty($this->name)) {
            $this->setMissing('name', true);
            $this->setErrors('Por favor, informe seu nome completo!');
        }
        if (empty($this->nick)) {
            $this->setMissing('nick', true);
            $this->setErrors('Por favor, informe seu nome de usuário!');
        }
        if (empty($this->password)) {
            $this->setMissing('password', true);
            $this->setErrors('Por favor, informe sua senha!');
        }
        if (empty($this->repeat_password)) {
            $this->setMissing('repeat_password', true);
            $this->setErrors('Por favor, confirme sua senha!');
        }
        if (empty($this->email)) {
            $this->setMissing('email', true);
            $this->setErrors('Por favor, informe seu e-mail!');
        }
        if (empty($this->cellphone)) {
            $this->setMissing('cellphone', true);
            $this->setErrors('Por favor, informe seu número de celular!');
        }
        if (empty($this->birthdate)){
            $this->setMissing('birthdate', true);
            $this->setErrors('Por favor, informe sua data de nascimento!');
        }
        if (empty($this->zip)) {
            $this->setMissing('zip', true);
            $this->setErrors('Por favor, informe o CEP do seu endereço!');
        }
        if (empty($this->address)) {
            $this->setMissing('address', true);
            $this->setErrors('Por favor, informe seu endereço!');
        }
        if (empty($this->number)) {
            $this->setMissing('number', true);
            $this->setErrors('Por favor, informe o nº do seu endereço!');
        }
        if (empty($this->neighborhood)) {
            $this->setMissing('neighborhood', true);
            $this->setErrors('Por favor, informe seu bairro!');
        }
        if (empty($this->city)) {
            $this->setMissing('city', true);
            $this->setErrors('Por favor, informe o CEP para buscar sua cidade!');
        }
        if (empty($this->province)) {
            $this->setMissing('province', true);
            $this->setErrors('Por favor, informe o CEP para buscar seu Estado!');
        }
        if (empty($this->card_flag)) {
            $this->setMissing('card_flag', true);
            $this->setErrors('Por favor, informe a bandeira do cartão de crédito!');
        }
        if (empty($this->card_number)) {
            $this->setMissing('card_number', true);
            $this->setErrors('Por favor, informe o número do cartão de crédito!');
        }
        if (empty($this->card_expiration_month)) {
            $this->setMissing('card_expiration_month', true);
            $this->setErrors('Por favor, informe o mês de vencimento do cartão de crédito!');
        }
        if (empty($this->card_expiration_year)) {
            $this->setMissing('card_expiration_year', true);
            $this->setErrors('Por favor, informe o ano de vencimento do cartão de crédito!');
        }
        if (empty($this->card_holder_name)) {
            $this->setMissing('card_holder_name', true);
            $this->setErrors('Por favor, informe o nome impresso no cartão de crédito!');
        }
        if (empty($this->card_doc_type)) {
            $this->setMissing('card_doc_type', true);
            $this->setErrors('Por favor, informe o tipo de documento do titular do cartão de crédito!');
        }
        if (empty($this->card_doc_number)) {
            $this->setMissing('card_doc_number', true);
            $this->setErrors('Por favor, informe o número de documento do titular do cartão de crédito!');
        }
        if (empty($this->card_security_code)) {
            $this->setMissing('card_security_code', true);
            $this->setErrors('Por favor, informe o cod de segurança do cartão de crédito!');
        }
        if (!isset($this->terms_check)) {
            $this->setErrors('Você deve concordar com os Termos e Condições');
        }
    }

    final private function checkData()
    {
        $this->checkFilterWords();
        $this->checkCaptcha();
        $this->checkEmail();
        $this->checkUsername();
        $this->checkPassword();
    }

    /**
     * filterWords
     * Roda o filtro de palavras
     * Se encontrar erros configura o atributo $errors
     */
    final private function checkFilterWords()
    {
        global $objSystem;

        if ($objSystem->settings['words_filter'] == 'y') {
            if ($objSystem->filter($this->nick)) {
                $this->setErrors('Por favor, selecione um nome de usuário diferente.<br>'
                                . 'Nosso filtro de palavras não permite que o nome informado seja usado.');
            }
        }
    }

    /**
     * checkCaptcha
     * Verifica se informou o Captcha corretamente
     * Se encontrar erros configura o atributo $errors
     */
    final private function checkCaptcha()
    {
        global $objSystem;
        global $obj_securimage;

        if ($objSystem->settings['captcha_type'] == 1 && !$obj_securimage->check($this->captcha_code)) {
            $this->setErrors('A seqüência de caracteres inserida para a verificação de imagem não corresponde ao que foi exibido.');

        } elseif ($objSystem->settings['captcha_type'] == 2) {
            $response = recaptcha_check_answer($objSystem->settings['recaptcha_private'], $this->recaptcha_code);
            if (!$response) {
                //$this->errors[] = 'A seqüência de caracteres inserida para a verificação de imagem não corresponde ao que foi exibido.';
            }
        }
    }

    /**
     * checkEmail
     * Realiza a validação do email informado
     * Se encontrar erros configura o atributo $errors
     */
    final private function checkEmail()
    {
        global $objSystem;

        // Verifica se maior que 5 caracteres (x@x.x)
        if (strlen($this->email) < 5) {
            $this->setErrors('O e-mail informado está incorreto.<br>Por favor, insira um endereço de e-mail válido!');
        }

        // Verifica os caracteres válidos
        if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i', $this->email)) {
            $this->setErrors('O e-mail informado está incorreto.<br>Por favor, insira um endereço de e-mail válido!');
        }

        // Checa se o email informado está na lista negra
        if ($objSystem->settings['spam_blocked_email_enabled']) {
            $exploded_email = explode('@', $this->email);
            $email_domain = trim(array_pop($exploded_email));
            $blocked_emails = explode("\n", $objSystem->settings['spam_blocked_email_domains']);
            foreach ($blocked_emails as $blocked) {
                if (stripos($email_domain, $blocked) !== false) {
                    $this->setErrors('O endereço de e-mail informado consta na nossa lista negra.<br>'
                                    . 'Por favor, informe um endereço de email diferente.');
                }
            }
        }

        // Checa se o email é único, ou seja, se já não está sendo usado por outro usuário
        $params['email'] = array(':email', $objSystem->cleanVars($this->email), 'str');
        $user_data = $this->getUser($params);
        if (is_array($user_data) && count($user_data) > 0) {
            $this->setErrors('O endereço de e-mail informado já está em uso!<br>Por favor, informe um endereço de email diferente.');
        }
    }

    /**
     * checkUsername
     * Realiza a validação do username informado
     * Se encontrar erros configura o atributo $errors
     */
    final private function checkUsername()
    {
        global $objSystem;

        // Verifica se o username tem mais de 6 caracteres
        if (strlen($this->nick) < 6) {
            $this->setErrors('O nome de usuário informado é muito curto!');
        }
        // Checa se o nome de usuário é único, ou seja, se já não está sendo usado por outro usuário
        $params['nick'] = array(':nick', $objSystem->cleanVars($this->nick), 'str');
        $user_data = $this->getUser($params);
        if (is_array($user_data) && count($user_data) > 0) {
            $this->setErrors('O nome de usúario informado já está em uso!<br>Por favor, informe um nome de usuário diferente.');
        }
    }

    /**
     * checkPassword
     * Realiza a validação da senha informada
     * Se encontrar erros configura o atributo $errors
     */
    final private function checkPassword()
    {
        if (strlen($this->password) < 6) {
            $this->setErrors('A senha informada é muito curta!');
        }
        if ($this->password != $this->repeat_password) {
            $this->setErrors('A senhas informadas não correspondem!');
        }
    }

    /**
     * hasError
     * Verifica se foram encontrados errors e retorna um valor booleano
     *
     * @return boolean
     */
    final protected function hasError()
    {
        if (count($this->errors) == 0) {
            return false;
        }
        return true;
    }

    final private function recordData($userType)
    {
        $this->setUserActivationType($userType . '_activation_type');
        $this->setUserSignUpFee($userType . '_signup_fee');
        $this->newUser();
        $this->recordUserIp($this->getUserId(), USER_IP, 'cadastro', 'aceito');
        $this->generateLog($userType, 'Novo usuário cadastrado');
        $this->sendEmail($userType . '_activation_type');
        //$obj_patient->goToPayPage();
    }

    /**
     * setUserActivationType
     *
     * Verifica o status de ativação do novo usuário de acordo com as configurações do sistema, ou seja,
     * Se necessita ativação por e-mail, pelo administrador ou se já deve ser cadastrado como ativo.
     * Após a verificação configura o atributo $suspended com o valor correspondente.
     *
     * activation_type = 0 => Caso necessite ativação pelo administrador;
     * activation_type = 1 => Caso necessite ativação pelo usuário (link de e-mail);
     * activation_type = 2 => Caso não seja necessário ativação.
     *
     * @global type $objSystem
     * @param string $user_activation_type - 'patient_activation_type' ou 'provider_activation_type'
     */
    final private function setUserActivationType($user_activation_type)
    {
        global $objSystem;

        if ($objSystem->settings[$user_activation_type] == 0) {
            $this->setSuspended(10);
        } elseif ($objSystem->settings[$user_activation_type] == 1) {
            $this->setSuspended(8);
        } elseif ($objSystem->settings[$user_activation_type] == 2) {
            $this->setSuspended(0);
        }
    }

    /**
     * setUserSignUpFee
     * Chama os métodos que configuram os atributos $signup_fee e $balance
     */
    final private function setUserSignUpFee($user_signup_fee)
    {
        global $objSystem;

        // Se a cobrança de taxas estiverem ativas, calcula saldo a pagar
        if ($objSystem->settings['fee_type'] == 2) {
            $this->assignSignUpFee($user_signup_fee);
            $this->assignBalance();
            // Se a taxa de inscrição for maior que zero
            if ($this->balance < 0) {
                $this->setSuspended(9);
            }
        }
    }

    /**
     * assignSignUpFee
     * Configura o valor da taxa de inscrição no sistema (atributo $signup_fee)
     *
     * @param string $signup_fee
     */
    final private function assignSignUpFee($signup_fee)
    {
        $obj_fee = new \AcessoMedico\systemclass\Fees;
        $this->setSignUpFee($obj_fee->getFeeValue($signup_fee));
    }

    /**
     * assignBalance
     * Configura o valor do atributo $balance
     */
    final private function assignBalance()
    {
        global $objSystem;
        $this->setBalance($objSystem->settings['fee_signup_bonus'] - $this->signup_fee);
    }

    /**
     * newUser
     * Configura o hash da senha;
     * Criptografa a senha fornecida;
     * Grava o novo usuário no banco de dados e
     * Configura o atributo $user_id com o valor do novo usuário gerado
     */
    final private function newUser()
    {
        // Configura o hash da senha
        $this->setHash();

        // Criptografa a senha fornecida
        include PACKAGE_PATH . 'PasswordHash.php';
        $phpass = new \PasswordHash(8, false);
        $this->password = $phpass->HashPassword($this->password);

        // Grava o novo usuário no banco de dados
        $this->setUserId($this->addUser());
    }

    /**
     * recordUserIp
     * Grava o ip do usuário na tabela user_ips
     */
    final public function recordUserIp($user_id, $user_ip, $type, $status)
    {
        $objUser_ip = new \AcessoMedico\systemclass\UserIp;
        $objUser_ip->add($user_id, $user_ip, $type, $status);
    }

    /**
     * generateLog
     * Log de novo usuário registrado
     */
    final private function generateLog($type, $message)
    {
        global $objLog;

        if (defined('TRACK_USER_IP')) {
            $objLog->setLog($type, $message, $this->user_id, $this->user_id, USER_IP);
        }
    }

    /**
     * sendEmail
     * Envia emails de acordo com as configurações do sistema e
     * configura o atributo success_message
     *
     * @global type $objSystem
     * @param string $user_activation_type - 'patient_activation_type' ou 'provider_activation_type'
     */
    final private function sendEmail($user_activation_type)
    {
        global $objSystem;

        // Caso necessite ativação pelo administrador;
        if ($objSystem->settings[$user_activation_type] == 0) {
            //include INCLUDE_PATH . 'email/user_need_approval.php';
            $this->setSuccessMessage('O administrador do site irá em breve analisar seu cadastro!<br>'
                . 'Uma vez que sua conta tenha sido aceita, você será capaz de fazer o login');

        // Caso necessite ativação pelo usuário (link de e-mail);
        } elseif ($objSystem->settings[$user_activation_type] == 1) {
            //include INCLUDE_PATH . 'email/user_confirmation.php';
            $this->setSuccessMessage(sprintf(
                'Enviamos um e-mail de confirmação para %s.<br>'
                . 'Este e-mail contém um link de ativação. '
                . 'Basta clicar no link para ativar sua conta no Acesso Médico.',
                $this->email
                )
            );

        // Caso não necessite ativação
        } else {
            //email_array = array(
            //    'name' => $this->name,
            //    'email' => $this->email
            //);
            //include INCLUDE_PATH . 'email/user_approved.php';
            $this->setSuccessMessage('Agora você já pode fazer login usando seu nome de usuário e senha.');
        }
    }

    /**
     * goToPayPage
     *
     * @global \AcessoMedico\systemclass\object $objSystem
     */
    final private function goToPayPage()
    {
        global $objSystem;

        // Se a cobrança de taxas estiverem ativas e a taxa de inscrição for maior que zero
        if ($objSystem->settings['fee_type'] == 2 && $this->balance < 0) {
            $_SESSION['sign_up_id'] = $this->user_id;
            header('location: pay.php?a=3');
            exit;
        }
    }

    /**
     * assingnTemplateVars
     * Atribui valores às variáveis da view
     */
    final private function assingnTemplateVars($initial, $userType)
    {
        global $objTemplate;
        global $objCurrency;
        global $objSystem;

        if ($initial) {
            $objCaptcha = new AcessoMedico\systemclass\Captcha();
            $objTemplate->assignVars(array(
                'first' => true,
                'error_list' => $this->getErrorList(),
                'has_signup_fee' => ($this->getSignUpFee() > 0),
                'signup_fee_value' => $this->getSignUpFee(),
                'signup_fee_label' => $objCurrency->printMoneySup($this->getSignUpFee()),
                'signup_fee_text' => ($this->getSignUpFee() > 0) ? 'Taxa para realização do cadastro:' : 'Somente para cadastro.<br>Nenhum débito será gerado<br>',
                'news_letter' => ($objSystem->settings['newsletter'] == 1),
                'terms_text' => $objSystem->settings['terms_text'],
                'need_admin_approval' => ($objSystem->settings['activation_type'] == 0),
                'captcha' => $objCaptcha->getCaptchaHTML(),
                'missing' => $this->getMissing(),
                'patient' => $_SESSION[$userType],
            ));
        } else {
            $top_message = sprintf('%s, muito obrigado por registrar-se.', $_SESSION[$userType]['designation'] . ' ' . $_SESSION[$userType]['name']);
            $objTemplate->assignVars(array(
                'first' => false,
                'top_message' => $top_message,
                'sub_message' => $this->getSuccessMessage()
            ));
        }
    }

    /**
     * generateHtmlPage
     * Gera o HTML da página de resultados da busca
     */
    final private function generateHtmlPage($userType)
    {
        global $objTemplate;

        include 'header.php';
        $objTemplate->setFilenames(array('body' => 'register_' . $userType . '_user.tpl'));
        $objTemplate->display('body');
        include 'footer.php';
    }

    final private function unsetSession($userType)
    {
        unset($_SESSION[$userType]);
        unset($_SESSION['securimage_code_disp']);
        unset($_SESSION['securimage_code_value']);
        unset($_SESSION['securimage_code_ctime']);
        unset($_SESSION['securimage_code_audio']);
    }

    /**
     * checkUserValid
     * Verifica se o id fornecido pertence a algum usuário cadastrado.
     *
     * @param int $id - Id do usuário procurado
     * @return boolean
     */
    final public function checkUserValid($id)
    {
        $is_valid = $this->checkUserId($id);
        if (!$is_valid) {
            $_SESSION['msg_title'] = 'Erro';
            $_SESSION['msg_body'] = 'Usuário Inválido';
            header('location: message.php');
            exit;
        }
        return true;
    }
}

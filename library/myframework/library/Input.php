<?php

namespace sys\core;

class Input {

    private $input;

    /**
     * checkSessionValue
     *
     * Verifica se há valores atribuídos anteriormente à cada item da SESSION[$name].
     *     - Se houver, mantém este valor na SESSION[$name][$key];
     *     - Se não houver, configura o item da SESSION[$name][$key] como null.
     */
    public function checkSessionValue($name)
    {
        if (isset($_SESSION[$name])) {
            foreach ($_SESSION[$name] as $key => $value) {
                $_SESSION[$name][$key] = ($value != null) ? $value : null;
            }
        }
    }

    /**
     * setInputs
     *
     * Gera e configura um array com os valores submetidos nos inputs (POST, GET, SERVER ou COOKIE)
     * Exemplos:
     *     $input[nome do campo html] = Valor informado no campo pelo usuário;
     *     $input['username'] = 'admin'
     *     $input['password'] = '12345'
     */
    public function setInputs($array)
    {
        foreach ($array as $key => $value) {
            $this->input[$key] = filter_input($value[0], $value[1], $value[2]);
        }
    }

    /**
     * setAttributes
     *
     * Configura os valores dos atributos da classe.
     * - Se houver valores passados por input (POST, SERVER e COOKIE), os atributos receberão estes valores;
     * - Do contrário:
     *     - O atributo action receberá null;
     *     - Os demais atributos receberão os valores alocados na SESSION[$name].
     *
     * @global type $objCharacter
     * @param type $obj
     * @param type $array
     */
    public function setAttributes($object, $name)
    {
        global $objCharacter;

        foreach ($this->input as $key => $value) {
            if ($value == null & $key != 'action') {
                $value = $_SESSION[$name][$key];
            } elseif ($value != null) {
                $value = $objCharacter->cleanVars($value);
            }
            call_user_func_array(array($object, __set), array($key, $value));
        }
    }

    /**
     * setSession
     *
     * Aloca os valores dos atributos principais na SESSION[$name]
     */
    public function setSession($name)
    {
        foreach ($this->input as $key => $value) {
            $_SESSION[$name][$key] = $value;
        }
    }
}

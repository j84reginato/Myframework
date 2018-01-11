<?php

namespace Application\Site\Model;

use System\Mapper;
use System\ConnectionConfig;

final class UserModel extends Mapper
{
    const TABLE_NAME   =  ConnectionConfig::DB_PREFIX . 'users';
    const ENTITY_CLASS =  System\Entity\User;

    public function get(User $obj)
    {
        $sql = "SELECT * FROM " . self::TABLENAME . " WHERE id = :id";
        $params[] = array(':id', $obj->id, 'int');
        $result = $this->first($this->select($sql, $params));
        $this->setObject($obj, $result);
    }

    public function getAll()
    {
        return $this->select("SELECT * FROM ".self::TABLENAME, null, true);
    }

    public function save(User $obj)
    {
        if ($obj->id == '' || is_null($obj->id) || !isset($obj->id)) {
            return $this->insert($obj, self::TABLENAME);
        } else {
            return $this->update($obj, array('id' => $obj->id), self::TABLENAME);
        }
    }

    public function exclude(User $obj)
    {
        if ($obj->id == '' || is_null($obj->id) || !isset($obj->id)) {
            return array(
                'success' => false,
                'feedback' => 'Registro não encontrado'
            );
        }
        return $this->delete(array('id' => $obj->id), self::TABLENAME);
    }


    /**
     * getUser
     *
     * Retorna os dados de todos os campos da tabela de usuários do registro procurado.
     *
     * @global obj $objDb
     * @param array $params - Parâmetros do usuário procurado
     * @param str $join - Parâmetro de ligação da string de parâmetros (padrão = AND)
     * @return array $user - Retorna os dados do usuário
     */
    final public function getUser($params, $join = ' AND ')
    {
        global $objDb;

        $user = '';
        $condition = '';
        $condition_number = count($params);

        foreach ($params as $label => $value) {
            if ($condition_number == 1) {
                $condition .= $label . ' = ' . $value[0];
            } else {
                $condition .= $label . ' = ' . $value[0] . $join;
            }
            $new_params[] = $value;
            $condition_number = $condition_number - 1;
        }
        $query =  "SELECT * FROM " . DB_PREFIX . "users WHERE " . $condition;
        $objDb->query($query, $new_params);
        if ($objDb->numRows() > 0) {
            $user = $objDb->result();
        }
        return $user;
    }

    /**
     * addUser
     */
    public function addUser()
    {
        global $objLanguage;
        global $objSystem;
        global $objDb;

        // Realiza a gravação dos dados no banco
        $query =    "INSERT INTO " . DB_PREFIX . "users ( "
                        . "user_type, nick, password, hash, "
                        . "designation, name, person_type, cpf_cnpj, birthdate, register_type, register_number, register_province, "
                        . "zip, address, number, complement, neighborhood, city, province, "
                        . "cellphone, workphone, email, "
                        . "card_flag, card_number, card_expiration_month, card_expiration_year, card_holder_name, card_doc_type, card_doc_number, card_security_code, "
                        . "nletter, language, reg_date, balance, suspended) "
                    . "VALUES ( "
                        . ":user_type, :nick, :password, :hash, "
                        . ":designation, :name, :person_type, :cpf_cnpj, :birthdate, :register_type, :register_number, :register_province, "
                        . ":zip, :address, :number, :complement, :neighborhood, :city, :province, "
                        . ":cellphone, :workphone, :email, "
                        . ":card_flag, :card_number, :card_expiration_month, :card_expiration_year, :card_holder_name, :card_doc_type, :card_doc_number, :card_security_code, "
                        . ":nletter, :language, :reg_date, :balance, :suspended)";

        $params = array(
            array(':user_type', $this->user_type, 'int'),
            array(':nick', $objSystem->cleanVars($this->nick), 'str'),
            array(':password', $this->password, 'str'),
            array(':hash', $this->hash, 'str'),
            array(':designation', $objSystem->cleanVars($this->designation), 'int'),
            array(':name', $objSystem->cleanVars($this->name), 'str'),
            array(':person_type', $objSystem->cleanVars($this->person_type), 'str'),
            array(':cpf_cnpj', $objSystem->cleanVars($this->cpf_cnpj), 'str'),
            array(':birthdate', $objSystem->cleanVars($this->birthdate), 'str'),
            array(':register_type', $objSystem->cleanVars($this->register_type), 'str'),
            array(':register_number', $objSystem->cleanVars($this->register_number), 'str'),
            array(':register_province', $objSystem->cleanVars($this->register_province), 'str'),
            array(':zip', $objSystem->cleanVars($this->zip), 'str'),
            array(':address', $objSystem->cleanVars($this->address), 'str'),
            array(':number', $objSystem->cleanVars($this->number), 'str'),
            array(':complement', $objSystem->cleanVars($this->complement), 'str'),
            array(':neighborhood', $objSystem->cleanVars($this->neighborhood), 'str'),
            array(':city', $objSystem->cleanVars($this->city), 'str'),
            array(':province', $objSystem->cleanVars($this->province), 'str'),
            array(':cellphone', $objSystem->cleanVars($this->cellphone), 'str'),
            array(':workphone', $objSystem->cleanVars($this->workphone), 'str'),
            array(':email', $objSystem->cleanVars($this->email), 'str'),
            array(':card_flag', $objSystem->cleanVars($this->card_flag), 'str'),
            array(':card_number', $objSystem->cleanVars($this->card_number), 'str'),
            array(':card_expiration_month', $objSystem->cleanVars($this->card_expiration_month), 'str'),
            array(':card_expiration_year', $objSystem->cleanVars($this->card_expiration_year), 'str'),
            array(':card_holder_name', $objSystem->cleanVars($this->card_holder_name), 'str'),
            array(':card_doc_type', $objSystem->cleanVars($this->card_doc_type), 'str'),
            array(':card_doc_number', $objSystem->cleanVars($this->card_doc_number), 'str'),
            array(':card_security_code', $objSystem->cleanVars($this->card_security_code), 'str'),
            array(':nletter', $this->nletter, 'int'),
            array(':language', $objLanguage->getLanguage(), 'str'),
            array(':reg_date', time(), 'int'),
            array(':balance', $this->balance, 'float'),
            array(':suspended', $this->suspended, 'int'),
        );
        $objDb->query($query, $params);

        $this->user_id = $objDb->lastInsertId();
        return $this->user_id;
    }

    /**
     * updateUserAccess
     *
     * Atualiza a permissão de acesso do usuário ao sistema
     *
     * 0 -> Conta ativa;
     * 1 -> Se a conta do usuário estiver sido suspensa pelo administrador;
     *
     * 5 -> O usuário (prestador) tem um pagamento pendente da taxa de atendimento realizado;
     * 6 -> O usuário (paciente) tem um pagamento pendente da taxa de atendimento realizado;
     * 7 -> O usuário excedeu o limite de dívida permitido;
     * 8 -> Se a conta ainda não foi ativada pelo usuário (link de e-mail);
     * 9 -> Suspensa por não pagamento da taxa de inscrição do sistema;
     * 10 -> Se a conta ainda não foi ativada pelo administrador;
     *
     * @global \KdDoctor\classes\DatabasePdo $objDb
     * @param int $number - Parâmetro referente ao tipo de suspensão a ser aplicada:
     */
    public function updateUserAccess($number)
    {
        global $objDb;

        $query = "  UPDATE " . DB_PREFIX . "users SET suspended = " . $number . " WHERE id = :user_id";
        $params[] = array(':user_id', $this->getUserData('id'), 'int');
        $objDb->query($query, $params);
    }

    final public function updateUserLastLogin($user_id)
    {
        global $objDb;

        $query = "UPDATE " . DB_PREFIX . "users SET last_login = :date WHERE id = :user_id";
        $params[] = array(':date', date("Y-m-d H:i:s"), 'str');
        $params[] = array(':user_id', $user_id, 'int');
        $objDb->query($query, $params);
    }

    final public function setRememberCookie($user_id, $record = true)
    {
        global $objDb;

        if ($record) {
            $remember_code = md5(time());
            $query =  "INSERT INTO " . DB_PREFIX . "remember_me VALUES (:user_id, :remember_code)";
            $params[] = array(':user_id', $user_id, 'int');
            $params[] = array(':remember_code', $remember_code, 'str');
            $objDb->query($query, $params);
            setcookie('REMEMBER_CODE', $remember_code, time() + (3600 * 24 * 365));
        }
    }

    /**
     * checkUserId
     * Busca pelo id fornecido na tabela de usuários cadastrados.
     *
     * @param int $user_id - Parâmetro id do usuário procurado
     * @return boolean
     */
    public function checkUserId($user_id)
    {
        global $objDb;

        $query = "SELECT id FROM " . DB_PREFIX . "users WHERE id = :user_id";
        $params[] = array(':user_id', $user_id, 'int');
        $objDb->query($query, $params);
        if ($objDb->numRows() > 0) {
            return true;
        }
        return false;
    }


}

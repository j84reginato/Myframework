<?php

namespace app\site\model;

use sys\core\Model;
use app\site\object\Remember;
use sys\core\ConnectionConfig;

class RememberModel extends Model
{
    const TABLENAME =  ConnectionConfig::DB_PREFIX . 'remember_me';

    /**
     * getUserId
     * ------------------------------------------------------------------------
     * Retorna o id do usuário na tabela remember_me de acordo com o parâmetro.
     * @param Remember $obj
     * @return Remember->userId
     */
    public function getUserId(Remember $obj)
    {
        $sql = "SELECT user_id FROM " . self::TABLENAME . " WHERE remember_code = :remember_code";
        $params[] = array(':remember_code', $obj->rememberCode, 'str');
        $result = $this->first($this->select1($sql, $params));
        $this->setObject($obj, $result);
        return $obj->userId;
    }
}

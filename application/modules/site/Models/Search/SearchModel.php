<?php

namespace app\model;

use sys\core\Model;
use app\object\User;
use database\Config;

final class UserModel extends Model
{
    const TABLENAME =  Config::DB_PREFIX . 'users';

    public function get(User $obj) {
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
}

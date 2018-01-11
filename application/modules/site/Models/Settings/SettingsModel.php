<?php

namespace app\site\model;

use sys\core\Model;
use app\site\object\Settings;
use sys\core\ConnectionConfig;

class SettingsModel extends Model
{
    const TABLENAME =  ConnectionConfig::DB_PREFIX . 'settings';

    public function get(Settings $obj)
    {
        $sql = "SELECT * FROM " . self::TABLENAME;
        $result = $this->select($sql, null, true);
        foreach ($result as $collumn) {
            foreach ($collumn as $key => $value) {
                if ($key == 'field_name') {
                    $field_name = $value;
                }
                if ($key == 'value') {
                    $field_value = $value;
                }
                if (isset($field_name) && isset($field_value)){
                    $res[$field_name] = $field_value;
                    unset($field_name);
                    unset($field_value);
                }
            }
        }
        $this->setObject($obj, $res);
    }













    public function getAll()
    {
        return $this->select("SELECT * FROM acessomedico_users", null, true);
    }

    public function save(User $obj)
    {
        if ($obj->id == '' || is_null($obj->id) || !isset($obj->id)) {
            return $this->insert($obj, $this->db_prefix . 'users');
        } else {
            return $this->update($obj, array('id' => $obj->id), $this->db_prefix . 'users');
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
        return $this->delete(array('id' => $obj->id), 'acessomedico_users');
    }
}


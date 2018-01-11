<?php

namespace models\session;

use core\Model;
use objects\session\Session;

class SessionModel extends Model
{
    const TABLENAME =  Config::DB_PREFIX.'users';

    public function get(Session $obj)
    {
        $sql = "SELECT * FROM ".self::TABLENAME." WHERE nick = '$obj->nick' OR email = '$obj->nick'";
        $result = $this->first($this->select($sql));
        $this->setObject($obj, $result);
    }
}

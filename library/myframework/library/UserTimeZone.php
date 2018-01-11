<?php

namespace sys\core;

class UserTimeZone
{
    private $ctime;
    private $tdiff;

    public function getCtime()
    {
        return $this->ctime;
    }

    public function getTdiff()
    {
        return $this->tdiff;
    }

    /**
     * setCtime
     *
     * Configura o atributo UserTimeZone->ctime
     *
     * @param type $ctime
     */
    private function setCtime($ctime)
    {
        $this->ctime = $ctime;
    }

    /**
     * setTdiff
     *
     * Configura o atributo UserTimeZone->tdiff
     *
     * @param int $tdiff
     */
    private function setTdiff($tdiff)
    {
        $this->tdiff = $tdiff;
    }

    /**
     * __construct
     *
     * @global \KdDoctor\classes\User $objUser
     */
    function __construct()
    {
        global $objSystem;
        global $objUser;

        $timezone = $objUser->checkAuth() ? $objUser->getUserData('timezone') : $objSystem->getSettings('timezone');
        $this->setTdiff($this->getUserOffset(time(), $timezone));
        $this->setCtime($this->getUserTimestamp(time(), $timezone) + $this->getTdiff());
    }

    /**
     * getUserOffset
     *
     * Retorna o valor da diferença de tempo do timezone usuário em relação ao timezone do sistema
     *
     * @param type $timestamp - Data/Hora atual no timezone do sistema
     * @param type $userTimezone - Timezone do usuário
     * @return int
     */
    function getUserOffset($timestamp, $userTimezone)
    {
        $dt = $this->getConvertedDateTimeObject($timestamp, $userTimezone);
        return $dt->getOffset();
    }

    /**
     * getUserTimestamp
     *
     * Retorna a data/hora atual (em formato timestamp) no timezone do usuário
     *
     * @param type $timestamp - Data/Hora atual no timezone do sistema
     * @param type $userTimezone - Timezone do usuário + diferença de tempo com relação ao timezone do sistema
     * @return int
     */
    function getUserTimestamp($timestamp, $userTimezone)
    {
        $dt = $this->getConvertedDateTimeObject($timestamp, $userTimezone);
        return $dt->getTimestamp();
    }

    /**
     * getConvertedDateTimeObject
     *
     * @param type $timestamp - Data/Hora atual no timezone do sistema
     * @param type $userTimezone - Timezone do usuário
     * @return \DateTime
     */
    function getConvertedDateTimeObject($timestamp, $userTimezone)
    {
        // Instacia os objetos timezone do servidor e do usuário
        $fromZone = new \DateTimeZone('UTC');
        $toZone = new \DateTimeZone($userTimezone);

        $time = date('Y-m-d H:i:s', $timestamp);
        $dt = new \DateTime($time, $fromZone);
        $dt->setTimezone($toZone);
        return $dt;
    }
}

<?php

/**
 * @package MyFramework
 * @subpackage MVC
 * @category Model/Entity
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Mvc\Model\Entity;

/**
 * ActiveRecordInterface.
 */
interface ActiveRecordInterface
{
    /**
     * load.
     *
     * @param type $id
     */
    public function load($id);

    /**
     * store.
     */
    public function store();

    /**
     * delete.
     */
    public function delete();

}

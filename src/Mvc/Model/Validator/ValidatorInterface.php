<?php

/**
 * @package MyFramework
 * @subpackage MVC
 * @category Model/Checker
 * @version 1.0.0
 * @author Jonatan Noronha Reginato <noronha_reginato@hotmail.com>
 */
namespace j84Reginato\MyFramework\Mvc\Model\Validator;

use j84Reginato\MyFramework\Mvc\Model\Form\FormInterface;

/**
 * ValidatorInterface
 */
interface ValidatorInterface
{
    /**
     * validate.
     *
     * @param FormInterface $oForm
     */
    public function validate(FormInterface $oForm);
}
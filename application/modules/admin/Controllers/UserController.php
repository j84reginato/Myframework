<?php

namespace controllers\admin;

use controllers\Controller;
use models\user\UserModel;
use models\user\User;
use helpers\Security;

use database\Transaction;

final class UserController extends Controller
{
    public function __construct() {
        parent::__construct();
        new Security();
    }

    public function index()
    {
        try {
            Transaction::open();
            $user_model = new UserModel();
            $this->data = array(
                'list' => $user_model->getAll()
            );
            Transaction::close(true);
            $this->view();            
        } catch (Exception $e) {
            Transaction::close(false);
            print $e->getMessage();
        }
    }

    public function formRegister()
    {
        $user_obj = new User();
        $user_model = new UserModel();

        $user_obj->id = $this->getParams(0);
        $user_model->get($user_obj);

        $this->data = array(
          'user_data' => $user_obj
        );
        $this->view();
    }

    public function save()
    {
        $user_obj = new User('POST');
        $user_model = new UserModel();

        $query = $user_model->save($user_obj);

        $this->data = array(
            'user_data' => $user_obj,
            'return' => $query
        );
        $this->view();
    }

    public function exclude()
    {
        $user_obj = new User();
        $user_obj->id = $this->getParams(0);
        $user_obj->name = $this->getParams(1);

        $this->data = array(
            'user_data' => $user_obj
        );
        $this->view();
    }

    public function excludeConfirm()
    {
        $user_obj = new User();
        $user_obj->id = $this->getParams(0);
        $user_obj->name = $this->getParams(1);

        $user_model = new UserModel();
        $query = $user_model->exclude($user_obj);

        $this->data = array(
            'user_data' => $user_obj,
            'return' => $query
        );
        $this->view();
    }
}

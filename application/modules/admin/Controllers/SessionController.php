<?php

namespace controllers\admin;

use core\Controller;
use models\session\SessionModel;
use objects\session\Session;
use api\session\SessionApi;

final class SessionController extends Controller
{
    public function index()
    {
        $this->layout = '_layout_login';
        $this->title = 'Admin Login';
        $this->description = 'Admin login description';
        $this->keywords = 'Admin login Keywords';

        $this->view();
    }

    public function login()
    {
        $session_obj = new Session('POST');
        $session_api = new SessionApi();
        $session_model = new SessionModel();

        $session_api->stripVars($session_obj);        
        $session_model->get($session_obj);
        $session_api->checkPass($session_obj);
    }

    public function logout()
    {
        $session_api = new SessionApi();
        $session_api->logout();
    }
}

<?php

namespace Application\Admin\Controllers;

use Myframework\MMVC\Controller;
use helpers\Security;

final class HomeController extends Controller
{
    public function index()
    {
        new Security();
        $this->view();
    }
}

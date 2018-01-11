<?php

namespace Myframework\MMVC;

abstract class Module
{
    protected $modules = array(
        'site'  => 'site',
        'admin' => 'admin'
    );
    protected $defaultModule = 'site';
    protected $onDefaultModule = true;
}

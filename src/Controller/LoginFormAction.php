<?php

namespace Snowdog\DevTest\Controller;

class LoginFormAction extends AbstractControllerClass
{

    public function execute()
    {
        $this->checkLogin(false);
        require __DIR__ . '/../view/login.phtml';
    }
}
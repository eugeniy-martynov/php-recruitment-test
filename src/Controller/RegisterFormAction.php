<?php

namespace Snowdog\DevTest\Controller;

class RegisterFormAction extends AbstractControllerClass
{
    public function execute() {
        $this->checkLogin(false);
        require __DIR__ . '/../view/register.phtml';
    }
}
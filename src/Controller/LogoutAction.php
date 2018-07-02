<?php

namespace Snowdog\DevTest\Controller;

class LogoutAction extends AbstractControllerClass
{

    public function execute() {
        $this->checkLogin();
        if(isset($_SESSION['login'])) {
            unset($_SESSION['login']);
            $_SESSION['flash'] = 'Logged out successfully';
        }
        header('Location: /login');
    }
}
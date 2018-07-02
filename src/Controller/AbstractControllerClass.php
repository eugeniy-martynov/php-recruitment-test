<?php

namespace Snowdog\DevTest\Controller;

abstract class AbstractControllerClass
{
    protected function checkLogin($login = true)
    {
        if ($login) {
            if (!isset($_SESSION['login'])) {
                include __DIR__ . '/../view/403.phtml';
                die();
            }
        } else {
            if (isset($_SESSION['login'])) {
                include __DIR__ . '/../view/403.phtml';
                die();
            }
        }
    }
}

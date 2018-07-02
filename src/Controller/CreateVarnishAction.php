<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;

class CreateVarnishAction
{
    /**
     * @var VarnishManager
     */
    private $varnishManager;
    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
    }

    public function execute()
    {
        $ip = $_POST['ip'];

        if (!empty($ip)) {
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                if (isset($_SESSION['login'])) {
                    $user = $this->userManager->getByLogin($_SESSION['login']);
                    if ($user) {
                        if ($this->varnishManager->create($user, $ip)) {
                            $_SESSION['flash'] = 'Varnish ' . $ip . ' added!';
                        }
                    }
                }
            } else {
                $_SESSION['flash'] = 'Wrong IP!';
            }
        } else {
            $_SESSION['flash'] = 'Name and Hostname cannot be empty!';
        }

        header('Location: /varnish');
    }
}
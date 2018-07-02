<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;

abstract class AbstractVarnishClass extends AbstractControllerClass
{
    /**
     * @var UserManager
     */
    protected $userManager;
    /**
     * @var VarnishManager
     */
    protected $varnishManager;

    /** @var \Snowdog\DevTest\Model\User $user */
    protected $user;
    /**
     * @var WebsiteManager
     */
    protected $websiteManager;

    public function __construct(UserManager $userManager, VarnishManager $varnishManager, WebsiteManager $websiteManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
        if (isset($_SESSION['login'])) {
            $this->user = $this->userManager->getByLogin($_SESSION['login']);
        }
        $this->websiteManager = $websiteManager;
    }

    protected function getVarnishes()
    {
        if ($this->user) {
            return $this->varnishManager->getAllByUser($this->user);
        }
        return [];
    }

    protected function getWebsites()
    {
        if ($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        }
        return [];
    }

    protected function getAssignedWebsiteIds(Varnish $varnish)
    {
        $websites = $this->websiteManager->getWebsitesByVarnish($varnish);
        $ids = [];
        foreach ($websites as $website) {
            $ids[] = $website->getWebsiteId();
        }
        return $ids;
    }
}

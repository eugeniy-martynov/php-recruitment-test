<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Model\PageManager;

class IndexAction extends AbstractControllerClass
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var User
     */
    private $user;
    /**
     * @var PageManager
     */
    private $pageManager;

    public function __construct(UserManager $userManager, WebsiteManager $websiteManager, PageManager $pageManager)
    {
        $this->checkLogin();
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    protected function getWebsites()
    {
        if($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        } 
        return [];
    }

    private function getAssignedWebsiteIds($websites)
    {
        $websiteIds = [];
        foreach ($websites as $website) {
            $websiteIds[] = $website->getWebsiteId();
        }
        return implode(', ', $websiteIds);
    }

    private function getAssignedWebsiteHosts($websites)
    {
        $hosts = [];
        foreach ($websites as $website) {
            $hosts[$website->getWebsiteId()] = $website->getHostname();;
        }
        return $hosts;
    }

    public function execute()
    {
        $websites = $this->getWebsites();

        if ($websites) {
            $websiteIds = $this->getAssignedWebsiteIds($websites);
            $hosts = $this->getAssignedWebsiteHosts($websites);

            $lastVisitPage = $this
                ->pageManager
                ->getLeastRecentlyPageByWebsite($websiteIds);
            $countOfPage = $this
                ->pageManager
                ->getCountOfPage($websiteIds)
                ->count;
            $firstVisitPage = $this
                ->pageManager
                ->getMostRecentlyPageByWebsite($websiteIds);

            if ($lastVisitPage)
                $lastVisitPage = $hosts[$lastVisitPage->getWebsiteId()] . '/' . $lastVisitPage->getUrl();
            if ($firstVisitPage)
                $firstVisitPage = $hosts[$firstVisitPage->getWebsiteId()] . '/' . $firstVisitPage->getUrl();
        }

        require __DIR__ . '/../view/index.phtml';
    }
}
<?php

namespace Snowdog\DevTest\Command;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Model\VarnishManager;
use Symfony\Component\Console\Output\OutputInterface;

class WarmCommand
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;

    public function __construct(WebsiteManager $websiteManager, PageManager $pageManager, VarnishManager $varnishManager)
    {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
        $this->varnishManager = $varnishManager;
    }

    public function __invoke($id, OutputInterface $output)
    {
        $website = $this->websiteManager->getById($id);
        if ($website) {
            $pages = $this->pageManager->getAllByWebsite($website);

            $resolver = new \Old_Legacy_CacheWarmer_Resolver_Method();
            $actor = new \Old_Legacy_CacheWarmer_Actor();
            $actor->setActor(function ($hostname, $ip, $url) use ($output) {
                $output->writeln('Visited <info>http://' . $hostname . '/' . $url . '</info> via IP: <comment>' . $ip . '</comment>');
            });
            $warmer = new \Old_Legacy_CacheWarmer_Warmer();
            $warmer->setResolver($resolver);
            $warmer->setHostname($website->getHostname());
            $warmer->setActor($actor);
            $varnish = $this->varnishManager->getById($website->getVarnishId());

            foreach ($pages as $page) {
                $warmer->warm($page->getUrl(), $varnish);
                $this->pageManager->trackLastUpdatePageById($page->getPageId());
            }
        } else {
            $output->writeln('<error>Website with ID ' . $id . ' does not exists!</error>');
        }
    }
}
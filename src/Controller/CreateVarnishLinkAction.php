<?php

namespace Snowdog\DevTest\Controller;

class CreateVarnishLinkAction extends AbstractVarnishClass
{
    public function execute()
    {
        $this->websiteManager->link($_POST['assoc']);
        include __DIR__ . '/../view/varnish_list.phtml';
    }
}
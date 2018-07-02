<?php

namespace Snowdog\DevTest\Controller;

class VarnishesAction extends AbstractVarnishClass
{
    public function execute()
    {
        include __DIR__ . '/../view/varnish.phtml';
    }
}

<?php

namespace Bolt\Extension\DesignSpike\MenuChain;

use Bolt;

class Extension extends \Bolt\BaseExtension
{

    public  $config;

    const NAME = "MenuChain";

    public function initialize()
    {
        if ($this->app['config']->getWhichEnd() == 'frontend') {
            // Twig functions
            $this->app['twig']->addExtension(new Twig\MenuChainExtension($this->app));
        }
    }

    public function getName()
    {
        return Extension::NAME;
    }

}

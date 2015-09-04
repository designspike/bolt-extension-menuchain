<?php

namespace Bolt\Extension\DesignSpike\MenuChain;

require_once "src/Twig/MenuChainExtension.php";

if (isset($app)) {
    $app['extensions']->register(new Extension($app));
}

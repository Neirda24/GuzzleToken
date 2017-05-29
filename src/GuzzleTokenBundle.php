<?php

namespace Guzzle\Token;

use Guzzle\Token\DependencyInjection\CompilerPass\AddTokenPluginToHandlers;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GuzzleTokenBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddTokenPluginToHandlers());
    }
}

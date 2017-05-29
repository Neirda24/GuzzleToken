<?php

namespace Guzzle\Token\DependencyInjection\CompilerPass;

use Guzzle\Token\TokenMiddleware;
use LogicException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\ExpressionLanguage\Expression;

class AddTokenPluginToHandlers implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws LogicException
     */
    public function process(ContainerBuilder $container)
    {
        /** @var array $config */
        $config = $container->getParameter('guzzle.token.config');
        $container->getParameterBag()->remove('guzzle.token.config');

        foreach ($config as $guzzleClientName => $tokenConfiguration) {
            $guzzleServiceName = sprintf('guzzle.client.%s', $guzzleClientName);

            if (!$container->hasDefinition($guzzleServiceName)) {
                throw new LogicException(sprintf('No clients `%s` declared under `guzzle`.', $guzzleClientName));
            }

            $guzzleService       = $container->getDefinition($guzzleServiceName);
            $guzzleServiceConfig = $guzzleService->getArgument(0);

            /** @var Definition $handlerDefinition */
            $handlerDefinition = $guzzleServiceConfig['handler'];

            $tokenMiddlewareServiceName = sprintf('guzzle_bundle.middleware.token.%s', $guzzleClientName);
            $tokenMiddlewareService     = new Definition(TokenMiddleware::class);
            $tokenMiddlewareService->setArguments([
                $tokenConfiguration['type'],
                $tokenConfiguration['parameter'],
                $tokenConfiguration['token'],
            ]);
            $container->setDefinition($tokenMiddlewareServiceName, $tokenMiddlewareService);

            $tokenMiddlewareExpression = new Expression(sprintf('service("%s").attach()', $tokenMiddlewareServiceName));
            $handlerDefinition->addMethodCall('push', [$tokenMiddlewareExpression]);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use App\Service\Api\ProviderResolver;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

final class ApiProviderCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ProviderResolver::class)) {
            return;
        }

        $definition = $container->getDefinition(ProviderResolver::class);
        $taggedServiceIds = $container->findTaggedServiceIds('service.api_providers');

        foreach ($taggedServiceIds as $id => $tags) {
            $definition->addMethodCall('registerProvider', [new Reference($id)]);
        }
    }
}

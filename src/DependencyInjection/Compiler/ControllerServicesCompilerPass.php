<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class ControllerServicesCompilerPass implements CompilerPassInterface
{
    /**
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('weblabel_api.controller_services') as $id => $tagConfiguration) {
            $definition = $container->getDefinition($id);
            $definition->addMethodCall('setFormErrorNormalizer', [new Reference('weblabel_api.normalizer.form.error')]);
            $definition->addMethodCall('setAuthorizationChecker', [new Reference('weblabel_api.security.authorization_checker')]);
        }
    }
}

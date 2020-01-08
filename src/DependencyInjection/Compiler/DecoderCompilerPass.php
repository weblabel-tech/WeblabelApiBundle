<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class DecoderCompilerPass implements CompilerPassInterface
{
    /**
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('weblabel_api.decoder_resolver.generic');
        $references = [];
        foreach ($container->findTaggedServiceIds('weblabel_api.decoder') as $id => $tagConfiguration) {
            $references[] = new Reference($id);
        }

        $definition->setArgument(0, $references);
    }
}

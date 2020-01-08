<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Weblabel\ApiBundle\DependencyInjection\Compiler\DecoderCompilerPass;

class DecoderCompilerPassTest extends TestCase
{
    public function test_resolver_autoconfiguration()
    {
        $containerBuilder = new ContainerBuilder();
        $decoderResolverDefinition = $containerBuilder->register('weblabel_api.decoder_resolver.generic');
        $decoderDefinition = $containerBuilder->register('weblabel_api.decoder.json');
        $decoderDefinition->addTag('weblabel_api.decoder');

        $compilerPass = new DecoderCompilerPass();
        $compilerPass->process($containerBuilder);

        self::assertContainsEquals(new Reference('weblabel_api.decoder.json'), $decoderResolverDefinition->getArgument(0));
    }
}

<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Weblabel\ApiBundle\Decoder\DecoderInterface;
use Weblabel\ApiBundle\DependencyInjection\Compiler\DecoderCompilerPass;

class WeblabelApiBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(DecoderInterface::class)->addTag('weblabel_api.decoder');
        $container->addCompilerPass(new DecoderCompilerPass());
    }
}

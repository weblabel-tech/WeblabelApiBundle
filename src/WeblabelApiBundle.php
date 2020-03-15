<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Weblabel\ApiBundle\Controller\AbstractController;
use Weblabel\ApiBundle\DependencyInjection\Compiler\ControllerServicesCompilerPass;

class WeblabelApiBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(AbstractController::class)->addTag('weblabel_api.controller_services')->addTag('controller.service_arguments');
        $container->addCompilerPass(new ControllerServicesCompilerPass());
    }
}

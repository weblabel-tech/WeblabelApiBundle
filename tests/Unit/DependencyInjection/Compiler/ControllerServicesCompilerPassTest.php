<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Unit\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Weblabel\ApiBundle\DependencyInjection\Compiler\ControllerServicesCompilerPass;

class ControllerServicesCompilerPassTest extends TestCase
{
    public function test_controller_services_autoconfiguration()
    {
        $containerBuilder = new ContainerBuilder();
        $testControllerDefinition = $containerBuilder->register('test_controller');
        $testControllerDefinition->addTag('weblabel_api.controller_services');

        $compilerPass = new ControllerServicesCompilerPass();
        $compilerPass->process($containerBuilder);

        $methodCalls = $testControllerDefinition->getMethodCalls();

        self::assertCount(2, $methodCalls);
        self::assertContainsEquals(['setFormErrorNormalizer', [new Reference('weblabel_api.normalizer.form.error')]], $methodCalls);
        self::assertContainsEquals(['setAuthorizationChecker', [new Reference('weblabel_api.security.authorization_checker')]], $methodCalls);
    }
}

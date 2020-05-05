<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Weblabel\ApiBundle\EventListener\ExceptionListener;
use Weblabel\ApiBundle\EventListener\RequestBodyListener;
use Weblabel\ApiBundle\Normalizer\Form\FormErrorNormalizerInterface;
use Weblabel\ApiBundle\Security\AuthorizationChecker;
use Weblabel\ApiBundle\Transformer\ExceptionTransformer\ConflictExceptionTransformer;
use Weblabel\ApiBundle\Transformer\ExceptionTransformer\GenericExceptionTransformer;
use Weblabel\ApiBundle\Transformer\ExceptionTransformer\HttpExceptionTransformer;
use Weblabel\ApiBundle\Transformer\ExceptionTransformer\ValidationExceptionTransformer;
use Weblabel\ApiBundle\Transformer\ExceptionTransformerResolverInterface;

class WeblabelApiBundleIntegrationTest extends KernelTestCase
{
    public function test_service_configuration()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        self::assertInstanceOf(RequestBodyListener::class, $container->get('test.weblabel_api.request_body_listener'));

        self::assertInstanceOf(FormErrorNormalizerInterface::class, $container->get('weblabel_api.normalizer.form.error'));

        self::assertInstanceOf(ExceptionTransformerResolverInterface::class, $container->get('weblabel_api.transformer.exception_resolver'));
        self::assertInstanceOf(ValidationExceptionTransformer::class, $container->get('test.weblabel_api.transformer.exception.validation'));
        self::assertInstanceOf(ConflictExceptionTransformer::class, $container->get('test.weblabel_api.transformer.exception.conflict'));
        self::assertInstanceOf(HttpExceptionTransformer::class, $container->get('test.weblabel_api.transformer.exception.http'));
        self::assertInstanceOf(GenericExceptionTransformer::class, $container->get('test.weblabel_api.transformer.exception.generic'));

        self::assertInstanceOf(ExceptionListener::class, $container->get('test.weblabel_api.exception_listener'));

        self::assertInstanceOf(AuthorizationChecker::class, $container->get('test.weblabel_api.security.authorization_checker'));
    }
}

<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Integration\Controller;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Weblabel\ApiBundle\Normalizer\Form\FormErrorNormalizer;
use Weblabel\ApiBundle\Security\AuthorizationChecker;
use Weblabel\ApiBundle\Tests\Fixtures\Controller\DefaultController;

final class DefaultControllerTest extends KernelTestCase
{
    public function testControllerServices()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();
        /** @var DefaultController $controller */
        $controller = $container->get('Weblabel\ApiBundle\Tests\Fixtures\Controller\DefaultController');

        self::assertInstanceOf(FormErrorNormalizer::class, $this->getProperty($controller, 'formErrorNormalizer'));
        self::assertInstanceOf(AuthorizationChecker::class, $this->getProperty($controller, 'authorizationChecker'));
    }

    private function getProperty(object $object, string $name)
    {
        $property = new \ReflectionProperty(\get_class($object), $name);
        $property->setAccessible(true);
        $value = $property->getValue($object);
        $property->setAccessible(false);

        return $value;
    }
}

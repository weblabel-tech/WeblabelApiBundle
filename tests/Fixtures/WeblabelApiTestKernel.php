<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Fixtures;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;
use Weblabel\ApiBundle\WeblabelApiBundle;
use Weblabel\DataTransformerBundle\WeblabelDataTransformerBundle;

class WeblabelApiTestKernel extends Kernel
{
    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles()
    {
        return [
            new WeblabelDataTransformerBundle(),
            new WeblabelApiBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/services.test.xml');
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}

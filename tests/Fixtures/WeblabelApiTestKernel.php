<?php

declare(strict_types=1);

namespace Weblabel\ApiBundle\Tests\Fixtures;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Weblabel\ApiBundle\WeblabelApiBundle;
use Weblabel\DataTransformerBundle\WeblabelDataTransformerBundle;

class WeblabelApiTestKernel extends Kernel
{
    public function __construct()
    {
        parent::__construct('test', true);
    }

    /**
     * @return iterable<mixed, BundleInterface>
     */
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new SecurityBundle(),
            new WeblabelDataTransformerBundle(),
            new WeblabelApiBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/framework.test.yaml');
        $loader->load(__DIR__.'/config/security.test.yaml');
        $loader->load(__DIR__.'/config/services.test.yaml');
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}

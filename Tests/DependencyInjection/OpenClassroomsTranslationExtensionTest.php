<?php

namespace Tests\DependencyInjection;

use OpenClassrooms\Bundle\TranslationBundle\DependencyInjection\OpenClassroomsTranslationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class OpenClassroomsTranslationExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @test
     */
    public function assertCatalogService()
    {
        $this->container = new ContainerBuilder();
        $extension = new OpenClassroomsTranslationExtension();
        $this->container->registerExtension($extension);

        $extension->load(array(), $this->container);

        $this->assertTrue($this->container->has('openclassrooms.translation.catalogue_service'));
    }
}

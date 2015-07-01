<?php

namespace Tests\DependencyInjection;

use OpenClassrooms\Bundle\TranslationBundle\DependencyInjection\OpenClassroomsTranslationExtension;
use OpenClassrooms\Bundle\TranslationBundle\OpenClassroomsTranslationBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class OpenClassroomsTranslationExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var YamlFileLoader
     */
    private $configLoader;

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @var ExtensionInterface
     */
    private $extension;

    /**
     * @var XmlFileLoader
     */
    protected $serviceLoader;

    /**
     * @test
     */
    public function CatalogServiceShouldBePresent()
    {
        $this->configLoader->load('config.yml');
        $this->container->compile();

        $this->assertTrue($this->container->has('openclassrooms.translation.catalogue_service'));
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function NoConfiguration_ThrowException()
    {
        $this->configLoader->load('empty_config.yml');
        $this->container->compile();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function WithoutLocaleSourceConfiguration_ThrowException()
    {
        $this->configLoader->load('without_locale_source.yml');
        $this->container->compile();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function WithoutLocaleTargetConfiguration_ThrowException()
    {
        $this->configLoader->load('without_locale_target.yml');
        $this->container->compile();
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function WithoutBundleConfiguration_ThrowException()
    {
        $this->configLoader->load('without_bundle.yml');
        $this->container->compile();
    }

    /**
     * @test
     */
    public function CorrectConfiguration()
    {
        $this->configLoader->load('config.yml');
        $this->container->compile();

        $localeSource = $this->container->getParameter('openclassrooms.translation.locale_source');
        $this->assertEquals('fr', $localeSource);

        $localeTargets = $this->container->getParameter('openclassrooms.translation.locale_targets');
        $this->assertCount(2, $localeTargets);
        $this->assertEquals('en', $localeTargets[0]);
        $this->assertEquals('es', $localeTargets[1]);

        $bundles = $this->container->getParameter('openclassrooms.translation.bundles');
        $this->assertCount(2, $bundles);
        $this->assertEquals('AppBundle', $bundles[0]);
        $this->assertEquals('UserBundle', $bundles[1]);
    }

    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->initTranslationBundle();
        $this->initServiceLoader();
        $this->initConfigLoader();
    }

    private function initTranslationBundle()
    {
        $this->extension = new OpenClassroomsTranslationExtension();
        $this->container->registerExtension($this->extension);
        $this->container->loadFromExtension('open_classrooms_translation');
        $bundle = new OpenClassroomsTranslationBundle();
        $bundle->build($this->container);
    }

    private function initServiceLoader()
    {
        $this->serviceLoader = new XmlFileLoader(
            $this->container,
            new FileLocator(__DIR__ . '/../Fixtures/Resources/config')
        );
        $this->serviceLoader->load('services.xml');
    }

    private function initConfigLoader()
    {
        $this->configLoader = new YamlFileLoader(
            $this->container,
            new FileLocator(__DIR__ . '/../Fixtures/Resources/config/')
        );
    }
}

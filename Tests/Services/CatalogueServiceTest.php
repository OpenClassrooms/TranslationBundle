<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Tests\Services;

use OpenClassrooms\Bundle\TranslationBundle\Services\CatalogueService;
use OpenClassrooms\Bundle\TranslationBundle\Services\Impl\CatalogueServiceImpl;
use Symfony\Bundle\FrameworkBundle\Translation\TranslationLoader;
use Symfony\Component\Translation\Loader\YamlFileLoader;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class CatalogueServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CatalogueService
     */
    private $service;

    /**
     * @test
     */
    public function NonExistingFilePath_ReturnEmpty()
    {
        $keys = $this->service->findMissingKeys('fr', array('en'), array(__DIR__.'../non_existing'));
        $this->assertEmpty($keys);
    }

    /**
     * @test
     */
    public function NonExistingReference_ReturnEmpty()
    {
        $keys = $this->service->findMissingKeys('tt', array('en'), array(__DIR__.'/../Fixtures/ABundle/Resources/translations'));
        $this->assertEmpty($keys);
    }

    /**
     * @test
     */
    public function EmptyLocales_ReturnEmpty()
    {
        $keys = $this->service->findMissingKeys('fr', array(), array(__DIR__.'/../Fixtures/ABundle/Resources/translations'));
        $this->assertEmpty($keys);
    }

    /**
     * @test
     */
    public function NonExistingLocale_ReturnAllKeys()
    {
        $keys = $this->service->findMissingKeys('fr', array('tt'), array(__DIR__.'/../Fixtures/ABundle/Resources/translations'));
        $this->assertCount(3, $keys['tt']);
    }

    /**
     * @test
     */
    public function OneFilePath_ReturnKeys()
    {
        $keys = $this->service->findMissingKeys('fr', array('en'), array(__DIR__.'/../Fixtures/ABundle/Resources/translations'));
        $this->assertCount(1, $keys['en']);
        $this->assertContains('a.key_2', $keys['en']);
    }

    /**
     * @test
     */
    public function MultiFilePath_ReturnKeys()
    {
        $path = array(__DIR__.'/../Fixtures/ABundle/Resources/translations', __DIR__.'/../Fixtures/BBundle/Resources/translations');
        $keys = $this->service->findMissingKeys('fr', array('en'), $path);
        $this->assertCount(2, $keys['en']);
        $this->assertContains('a.key_2', $keys['en']);
        $this->assertContains('b.key_3', $keys['en']);
    }

    /**
     * @test
     */
    public function NoMissing_ReturnEmpty()
    {
        $keys = $this->service->findMissingKeys('fr', array('en'), array(__DIR__.'/../Fixtures/CBundle/Resources/translations'));
        $this->assertEmpty($keys);
    }

    protected function setUp()
    {
        $this->service = new CatalogueServiceImpl();
        $translationLoader = new TranslationLoader();
        $translationLoader->addLoader('yml', new YamlFileLoader());
        $this->service->setTranslationLoader($translationLoader);
    }

}

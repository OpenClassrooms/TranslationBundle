<?php

namespace Tests\Services\Impl;

use OpenClassrooms\Bundle\TranslationBundle\Services\CatalogueService;
use OpenClassrooms\Bundle\TranslationBundle\Services\Impl\CatalogueServiceImpl;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\Loader\YamlFileLoader;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class CatalogueServiceImplTest extends \PHPUnit_Framework_TestCase
{

    private $referenceKeys;

    private $referenceKeysFilePath1 = array();

    private $referenceKeysFilePath2 = array();

    /**
     * @var CatalogueService
     */
    private $service;

    /**
     * @test
     */
    public function NonExistingFilePath_ReturnEmpty()
    {
        $keys = $this->service->findMissingKeys('en', array('fr'), array(__DIR__.'../non_existing'));
        $this->assertEmpty($keys);
    }

    /**
     * @test
     */
    public function NonExistingReference_ReturnEmpty()
    {
        $keys = $this->service->findMissingKeys('tt', array('en'), array(__DIR__.'../non_existing'));
        $this->assertEmpty($keys);
    }

    /**
     * @test
     */
    public function EmptyLocales_ReturnEmpty()
    {
        $keys = $this->service->findMissingKeys('en', array(), array(__DIR__.'../non_existing'));
        $this->assertEmpty($keys);
    }

    /**
     * @test
     */
    public function NonExistingLocale_ReturnAllKeys()
    {
        $keys = $this->service->findMissingKeys('en', array('tt'), array(__DIR__.'../non_existing'));
        $this->assertCount(count($this->referenceKeys), $keys);
    }

    /**
     * @test
     */
    public function OneFilePath_ReturnKeys()
    {
        $keys = $this->service->findMissingKeys('en', array('tt'), array(__DIR__.'../non_existing'));
    }

    /**
     * @test
     */
    public function MultiFilePath_ReturnKeys()
    {
        $keys = $this->service->findMissingKeys('en', array('tt'), array(__DIR__.'../non_existing'));
    }

    protected function setUp()
    {
        $this->service = new CatalogueServiceImpl();
        $this->service->setFinder(new Finder());
        $this->service->setLoader(new YamlFileLoader());
        $this->referenceKeys = array_merge($this->referenceKeysFilePath1, $this->referenceKeysFilePath2);
    }

}

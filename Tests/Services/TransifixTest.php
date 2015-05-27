<?php

namespace OpenClassrooms\Bundle\TranslationBundle\Tests\Services;

use OpenClassrooms\Bundle\TranslationBundle\Services\Impl\TransifixImpl;
use OpenClassrooms\Bundle\TranslationBundle\Services\Transifix;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Bastien Rambure <bastien.rambure@openclassrooms.com>
 */
class TransifixTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Transifix
     */
    private $service;

    /**
     * @test
     */
    public function FixYamlFileShouldChangeYamlRoot()
    {
        $path = __DIR__ . '/../Fixtures/TransifixBundle/Resources/translations/twoCharRoot.en.yml';
        $this->service->fixYamlFile($path);

        $actual = FileSystemStub::$files[$path];
        $expected = file_get_contents(
            __DIR__ . '/../Fixtures/TransifixBundle/Resources/translations/fixedTwoCharRoot.en.yml'
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function LoadYamlFileShouldReturnArray()
    {
        $actual = $this->service->loadYaml(
            __DIR__ . '/../Fixtures/TransifixBundle/Resources/translations/twoCharRoot.en.yml'
        );

        $expected = array(
            'en' => array(
                'test' => array(
                    'string' => 'my string'
                )
            )
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function LoadYamlFileWithCutLinesShouldReturnArray()
    {
        $actual = $this->service->loadYaml(
            __DIR__ . '/../Fixtures/TransifixBundle/Resources/translations/cutLines.en.yml'
        );

        $expected = array(
            'en' => array(
                'test' => array(
                    'string' => 'This is a line cut by transifex'
                )
            )
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function FixXssShouldRemoveScriptTags()
    {
        $actual = array(
            'testRoot' => array(
                'test' => array(
                    'string' => "youpi <script>alert('OK');</script> test"
                )
            )
        );

        $actual = $this->service->fixXss($actual);

        $expected = array(
            'testRoot' => array(
                'test' => array(
                    'string' => "youpi  test"
                )
            )
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function FixXssShouldNotRemoveHrefLink()
    {
        $actual = array(
            'testRoot' => array(
                'test' => array(
                    'string' => '<a href="%link%">test</a>'
                )
            )
        );

        $actual = $this->service->fixXss($actual);

        $expected = array(
            'testRoot' => array(
                'test' => array(
                    'string' => '<a href="%link%">test</a>'
                )
            )
        );

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function FixXssShouldNotRemoveClassInLinks()
    {
        $actual = array(
            'testRoot' => array(
                'test' => array(
                    'string' => 'Test <a class="underlined" href="%signup_url%"> ok</a>'
                )
            )
        );

        $actual = $this->service->fixXss($actual);

        $expected = array(
            'testRoot' => array(
                'test' => array(
                    'string' => 'Test <a class="underlined" href="%signup_url%"> ok</a>'
                )
            )
        );

        $this->assertEquals($expected, $actual);
    }

    protected function setUp()
    {
        $this->service = new TransifixImpl();
        $this->service->setYaml(new Yaml());
        $this->service->setHtmlPurifier(new \HTMLPurifier());
        $this->service->setFileSystem(new FileSystemStub());
    }

}

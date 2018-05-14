<?php

namespace Charcoal\Tests\Config\Mixin\FileLoader;

// From 'charcoal-config'
use Charcoal\Tests\Config\Mixin\FileLoader\AbstractFileLoaderTestCase;
use Charcoal\Config\FileAwareTrait;

/**
 * Test {@see FileAwareTrait::loadXmlFile() XML File Loading}
 *
 * @coversDefaultClass \Charcoal\Config\FileAwareTrait
 */
class XmlFileLoaderTest extends AbstractFileLoaderTestCase
{
    /**
     * Asserts that the File Loader supports XML config files.
     *
     * @covers ::loadXmlFile()
     * @covers ::loadFile()
     * @return void
     */
    public function testLoadFile()
    {
        $path = $this->getPathToFixture('pass/valid.xml');
        $data = $this->obj->loadFile($path);

        $this->assertEquals('localhost', $data['host']);
        $this->assertEquals('11211', $data['port']);
        $this->assertEquals(
            [
                'pdo_mysql',
                'pdo_pgsql',
                'pdo_sqlite',
            ],
            $data['drivers']
        );
    }

    /**
     * Asserts that an empty file is silently ignored.
     *
     * @covers ::loadXmlFile()
     * @return void
     */
    public function testLoadEmptyFile()
    {
        $path = $this->getPathToFixture('pass/empty.xml');
        $data = $this->obj->loadFile($path);

        $this->assertEquals([], $data);
    }

    /**
     * Asserts that a broken file is NOT ignored.
     *
     * @expectedException              UnexpectedValueException
     * @expectedExceptionMessageRegExp /^XML file ".+?" could not be parsed: .+$/
     *
     * @covers ::loadXmlFile()
     * @return void
     */
    public function testLoadMalformedFile()
    {
        // phpcs:disable Generic.PHP.NoSilencedErrors.Discouraged
        $path = $this->getPathToFixture('fail/malformed.xml');
        $data = @$this->obj->loadFile($path);
        // phpcs:enable
    }

    /**
     * Asserts that an unparsable file is NOT ignored.
     *
     * @expectedException              UnexpectedValueException
     * @expectedExceptionMessageRegExp /^XML file ".+?" could not be parsed: .+$/
     *
     * @covers ::loadXmlFile()
     * @return void
     */
    public function testLoadUnparsableXmlFile()
    {
        // phpcs:disable Generic.PHP.NoSilencedErrors.Discouraged
        $path = $this->getPathToFixture('fail/unparsable.xml');
        $data = @$this->obj->loadFile($path);
        // phpcs:enable
    }
}

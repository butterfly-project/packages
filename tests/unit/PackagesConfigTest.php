<?php

namespace Butterfly\Component\Packages\Tests;

use Butterfly\Component\Annotations\ClassParser;
use Butterfly\Component\Annotations\Parser\PhpDocParser;
use Butterfly\Component\Packages\PackagesConfig;

class PackagesConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildForComposer()
    {
        $dir = __DIR__ . '/data/config';

        $classParser = new ClassParser(new PhpDocParser());
        $config      = PackagesConfig::buildForComposer($dir, $classParser);

        $this->assertEquals($this->getExpectedConfig($dir), $config);
    }

    private function getExpectedConfig($dir)
    {
        return array(
            'parameters' => array(
                'package.foo.parameter' => 'foo',
                'package.baz.parameter' => 'baz',
                'package.bar.parameter' => 'bar',
                'main.parameter' => 'value1',
                'app.dir.root'   => $dir,
                'butterfly.packages.config' => array(
                    'main'        => array(
                        'dir' => $dir,
                    ),
                    'package/foo' => array(
                        'dir' => $dir . '/vendor/package/foo',
                    ),
                    'package/bar' => array(
                        'dir' => $dir . '/vendor/package/bar/Package/Bar',
                    ),
                    'package/baz' => array(
                        'dir' => $dir . '/vendor/package/baz',
                    ),
                    'butterfly/config' => array(
                        'dir' => $dir . '/vendor/butterfly/config',
                    ),
                ),
            ),
            'services'   => array(
                'foo\service1' => array(
                    'class' => 'Foo\Service1',
                ),
                'baz\service2' => array(
                    'class' => 'Baz\Service2',
                ),
                'baz\old\service3' => array(
                    'class' => 'Baz\Old\Service3',
                ),
                'bar\service4' => array(
                    'class' => 'Bar\Service4',
                ),
                'project\service5' => array(
                    'class' => 'Project\Service5',
                ),
            ),
            'aliases'    => array(
                'service5.alias' => 'service5',
                'service1'       => 'foo\service1',
                'service2'       => 'baz\service2',
                'service3'       => 'baz\old\service3',
                'service4'       => 'bar\service4',
                'service5'       => 'project\service5',
            ),
            'tags'       => array(),
        );
    }
}

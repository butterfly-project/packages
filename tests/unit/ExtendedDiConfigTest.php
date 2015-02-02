<?php

namespace Butterfly\Component\Packages\Tests;

use Butterfly\Component\Packages\ExtendedDiConfig;

class ExtendedDiConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildForComposer()
    {
        $dir    = __DIR__ . '/data/config';
        $output = $dir . '/config.php';

        ExtendedDiConfig::buildForComposer($dir, $output);

        $this->assertEquals($this->getExpectedConfig($dir), require $output);

        unlink($output);
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
                'service1' => array(
                    'class' => 'Foo\Service1',
                    'alias' => 'Foo\Service1',
                ),
                'service2' => array(
                    'class' => 'Baz\Service2',
                    'alias' => 'Baz\Service2',
                ),
                'service3' => array(
                    'class' => 'Baz\Old\Service3',
                    'alias' => 'Baz\Old\Service3',
                ),
                'service4' => array(
                    'class' => 'Bar\Service4',
                    'alias' => 'Bar\Service4',
                ),
                'service5' => array(
                    'class' => 'Project\Service5',
                    'alias' => 'Project\Service5',
                ),
            ),
            'interfaces' => array(),
            'aliases'    => array(
                'Foo\Service1'     => 'service1',
                'Baz\Service2'     => 'service2',
                'Baz\Old\Service3' => 'service3',
                'Bar\Service4'     => 'service4',
                'Project\Service5' => 'service5',
            ),
            'tags'       => array(),
        );
    }
}

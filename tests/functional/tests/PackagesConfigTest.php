<?php

namespace Butterfly\Tests;

use Butterfly\Component\Packages\PackagesConfig;

class PackagesConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildForComposer()
    {
        $dir    = __DIR__ . '/config';

        $config = PackagesConfig::buildForComposer($dir);

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
            'services'           => array(),
            'interfaces'         => array(),
            'interfaces_aliases' => array(),
            'aliases'            => array(),
            'tags'               => array(),
        );
    }
}

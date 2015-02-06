<?php

namespace Butterfly\Component\Packages\Tests;

use Butterfly\Component\Packages\PackagesConfig;

class PackagesConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildForComposer()
    {
        $dir    = __DIR__ . '/data/config';

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
            'services'   => array(
                'service1' => array(
                    'class' => 'Foo\Service1',
                    'alias' => array('foo\service1'),
                ),
                'service2' => array(
                    'class' => 'Baz\Service2',
                    'alias' => array('baz\service2'),
                ),
                'service3' => array(
                    'class' => 'Baz\Old\Service3',
                    'alias' => array('baz\old\service3'),
                ),
                'service4' => array(
                    'class' => 'Bar\Service4',
                    'alias' => array('bar\service4'),
                ),
                'service5' => array(
                    'class' => 'Project\Service5',
                    'alias' => array('project\service5'),
                ),
            ),
            'interfaces'         => array(),
            'interfaces_aliases' => array(),
            'aliases'    => array(
                'foo\service1'     => 'service1',
                'baz\service2'     => 'service2',
                'baz\old\service3' => 'service3',
                'bar\service4'     => 'service4',
                'project\service5' => 'service5',
            ),
            'tags'       => array(),
        );
    }
}

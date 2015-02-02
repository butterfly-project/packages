<?php

namespace Butterfly\Component\Packages\Tests;

use Butterfly\Component\Packages\ComposerConfigAdapter;

class ComposerConfigAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testParseConfiguration()
    {
        $baseDir = __DIR__ . '/data/config';
        $builder = new ComposerConfigAdapter($baseDir);

        $expectedDiConfigs = array(
            $baseDir . '/vendor/package/foo/config/foo.yml',
            $baseDir . '/vendor/package/baz/di.yml',
            $baseDir . '/vendor/package/bar/Package/Bar/di.yml',
            $baseDir . '/di.yml',
        );

        $expectedPackagesConfig = array(
            'main'        => array(
                'dir'      => $baseDir,
            ),

            'package/foo' => array(
                'dir'      => $baseDir . '/vendor/package/foo',
            ),

            'package/bar' => array(
                'dir'      => $baseDir . '/vendor/package/bar/Package/Bar',
            ),

            'package/baz' => array(
                'dir'      => $baseDir . '/vendor/package/baz',
            ),

            'butterfly/config' => array(
                'dir'      => $baseDir . '/vendor/butterfly/config',
            ),
        );

        $expectedAnnotationDirs = array(
            $baseDir . '/vendor/package/foo/src',
            $baseDir . '/vendor/package/baz/libraries',
            $baseDir . '/vendor/package/baz/legacy',
            $baseDir . '/vendor/package/bar/Package/Bar/src',
            $baseDir . '/src',
        );

        $this->assertEquals($expectedDiConfigs, $builder->getDiConfigs());
        $this->assertEquals($expectedPackagesConfig, $builder->getPackagesConfigs());
        $this->assertEquals($expectedAnnotationDirs, $builder->getAnnotationDirs());
    }

    public function testParseConfigurationIfNotFoundComposerJson()
    {
        $baseDir = __DIR__ . '/data/config_not_composer_json';

        $builder = new ComposerConfigAdapter($baseDir);

        $expectedDiConfigs = array(
            $baseDir . '/di.yml',
        );

        $this->assertEquals($expectedDiConfigs, $builder->getDiConfigs());
    }

    public function testParseConfigurationIfNotFoundDiConfig()
    {
        $builder = new ComposerConfigAdapter(__DIR__ . '/data/config_not_di_config');

        $this->assertEquals(array(), $builder->getDiConfigs());
    }
}

<?php

namespace Butterfly\Component\Packages\Tests;

use Butterfly\Component\Annotations\ClassParser;
use Butterfly\Component\Annotations\FileLoader\FileLoader;
use Butterfly\Component\Annotations\Parser\PhpDocParser;
use Butterfly\Component\Packages\Annotation\AnnotationDiConfigAdapter;

class AnnotationDiConfigAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function getDataForTestExtractDiConfiguration()
    {
        $baseNamespace = 'Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation';

        return array(

            // Example 1. not named service
            array(__DIR__ . '/Stub/Annotation/Example1', array('services' => array(
                "$baseNamespace\\Example1\\Service" => array(
                    'class' => "$baseNamespace\\Example1\\Service",
                ),
            ))),

            // Example 2. named service
            array(__DIR__ . '/Stub/Annotation/Example2', array('services' => array(
                'service.base' => array(
                    'class' => "$baseNamespace\\Example2\\Service",
                    'alias' => "$baseNamespace\\Example2\\Service",
                ),
            ))),

            // Example 3. property for type
            array(__DIR__ . '/Stub/Annotation/Example3', array('services' => array(
                "$baseNamespace\\Example3\\DirA\\InnerService" => array(
                    'class' => "$baseNamespace\\Example3\\DirA\\InnerService",
                ),
                'service.base' => array(
                    'class' => "$baseNamespace\\Example3\\Service",
                    'alias' => "$baseNamespace\\Example3\\Service",
                    'properties' => array(
                        'inner' => "$baseNamespace\\Example3\\DirA\\InnerService",
                    ),
                ),
            ))),

            // Example 4. property for annotation
            array(__DIR__ . '/Stub/Annotation/Example4', array('services' => array(
                'service.base' => array(
                    'class' => "$baseNamespace\\Example4\\Service",
                    'alias' => "$baseNamespace\\Example4\\Service",
                    'properties' => array(
                        'innerService'  => "service.inner",
                        'innerProperty' => "parameter.inner",
                    ),
                ),
            ))),

            // Example 5. methods for types
            array(__DIR__ . '/Stub/Annotation/Example5', array('services' => array(
                "$baseNamespace\\Example5\\DirA\\InnerService" => array(
                    'class' => "$baseNamespace\\Example5\\DirA\\InnerService",
                ),
                "$baseNamespace\\Example5\\DirA\\Inner2Service" => array(
                    'class' => "$baseNamespace\\Example5\\DirA\\Inner2Service",
                ),
                'service.base' => array(
                    'class' => "$baseNamespace\\Example5\\Service",
                    'alias' => "$baseNamespace\\Example5\\Service",
                    'calls' => array(
                        array('init', array(
                            "@$baseNamespace\\Example5\\DirA\\InnerService",
                            "@$baseNamespace\\Example5\\DirA\\Inner2Service",
                        )),
                    ),
                ),
            ))),

            // Example 6. methods for phpDoc types
            array(__DIR__ . '/Stub/Annotation/Example6', array('services' => array(
                "$baseNamespace\\Example6\\DirA\\InnerService" => array(
                    'class' => "$baseNamespace\\Example6\\DirA\\InnerService",
                ),
                "$baseNamespace\\Example6\\DirA\\Inner2Service" => array(
                    'class' => "$baseNamespace\\Example6\\DirA\\Inner2Service",
                ),
                'service.base' => array(
                    'class' => "$baseNamespace\\Example6\\Service",
                    'alias' => "$baseNamespace\\Example6\\Service",
                    'calls' => array(
                        array('init', array(
                            "@$baseNamespace\\Example6\\DirA\\InnerService",
                            "@$baseNamespace\\Example6\\DirA\\Inner2Service",
                        )),
                    ),
                ),
            ))),

            // Example 7. methods for autowired annotation
            array(__DIR__ . '/Stub/Annotation/Example7', array('services' => array(
                "$baseNamespace\\Example7\\DirA\\InnerService" => array(
                    'class' => "$baseNamespace\\Example7\\DirA\\InnerService",
                ),
                "$baseNamespace\\Example7\\DirA\\Inner2Service" => array(
                    'class' => "$baseNamespace\\Example7\\DirA\\Inner2Service",
                ),
                'service.base' => array(
                    'class' => "$baseNamespace\\Example7\\Service",
                    'alias' => "$baseNamespace\\Example7\\Service",
                    'calls' => array(
                        array('init', array(
                            "@service.inner",
                            "@service.inner2",
                            "%parameter.input%",
                        )),
                    ),
                ),
            ))),

            // Example 8. tags
            array(__DIR__ . '/Stub/Annotation/Example8', array('services' => array(
                'service.base' => array(
                    'class' => "$baseNamespace\\Example8\\Service",
                    'alias' => "$baseNamespace\\Example8\\Service",
                    'tags' => array('tagA'),
                ),
                'service.base2' => array(
                    'class' => "$baseNamespace\\Example8\\Service2",
                    'alias' => "$baseNamespace\\Example8\\Service2",
                    'tags' => array('tagA', 'tagB'),
                ),
            ))),

            // Example 9. scopes
            array(__DIR__ . '/Stub/Annotation/Example9', array('services' => array(
                'service.base' => array(
                    'class' => "$baseNamespace\\Example9\\Service",
                    'alias' => "$baseNamespace\\Example9\\Service",
                    'scope' => 'prototype',
                ),
            ))),

            // Example 10. constructor
            array(__DIR__ . '/Stub/Annotation/Example10', array('services' => array(
                "$baseNamespace\\Example10\\DirA\\InnerService" => array(
                    'class' => "$baseNamespace\\Example10\\DirA\\InnerService",
                ),
                'service.base' => array(
                    'class' => "$baseNamespace\\Example10\\Service",
                    'alias' => "$baseNamespace\\Example10\\Service",
                    'arguments' => array(
                        '@service.inner',
                    ),
                ),
            ))),

        );
    }

    /**
     * @dataProvider getDataForTestExtractDiConfiguration
     *
     * @param string $dirPath
     * @param array $expectedConfig
     */
    public function testExtractDiConfiguration($dirPath, array $expectedConfig)
    {
        $annotations = $this->parseAnnotations($dirPath);
        $adapter     = new AnnotationDiConfigAdapter();

        $diConfig    = $adapter->extractDiConfiguration($annotations);

        ksort($expectedConfig['services']);
        ksort($diConfig['services']);

        $this->assertEquals($expectedConfig, $diConfig);
    }

    /**
     * @param string $dirPath
     * @return array
     */
    protected function parseAnnotations($dirPath)
    {
        $parser = new ClassParser(new PhpDocParser(), new FileLoader());

        return $parser->parseClassesInDir($dirPath);
    }
}

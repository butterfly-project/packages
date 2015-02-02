<?php

namespace Butterfly\Component\Packages\Tests;

use Butterfly\Component\Packages\Annotation\ReflectionClass;

class ReflectionClassTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUseStatements()
    {
        $testClassname   = 'Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\NamespacesClass';
        $reflectionClass = new ReflectionClass($testClassname);

        $expected = array(
            'Example3'   => '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirA\Example3',
            'E4'         => '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirA\Example4',
            'DirB'       => '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirB',
            'DirectoryC' => '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirC',
            'Example7'   => '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirD\Example7',
            'E8'         => '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirD\Example8',
            'DirD'       => '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirD',
            'DirectoryE' => '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirE',
        );

        $result = $reflectionClass->getUseStatements();

        $this->assertEquals($expected, $result);
    }

    public function getDataForTestGetFullNamespace()
    {
        return array(
            array('Example1',               '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\Example1'),
            array('DirA\Example2',          '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirA\Example2'),
            array('Example3',               '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirA\Example3'),
            array('E4',                     '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirA\Example4'),
            array('DirB\Example5',          '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirB\Example5'),
            array('DirectoryC\Example6',    '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirC\Example6'),
            array('Example7',               '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirD\Example7'),
            array('E8',                     '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirD\Example8'),
            array('DirD\Example9',          '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirD\Example9'),
            array('DirectoryE\Example10',   '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirE\Example10'),
            array('\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirE\Example11', '\Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\DirE\Example11'),
        );
    }

    /**
     * @dataProvider getDataForTestGetFullNamespace
     *
     * @param string $shortType
     * @param string $expected
     */
    public function testGetFullNamespace($shortType, $expected)
    {
        $testClassname   = 'Butterfly\Component\Packages\Tests\Annotation\Stub\Reflection\NamespacesClass';
        $reflectionClass = new ReflectionClass($testClassname);

        $result = $reflectionClass->getFullNamespace($shortType);

        $this->assertEquals($expected, $result);
    }
}

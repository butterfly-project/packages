<?php

namespace Butterfly\Component\Packages\Tests;

use Butterfly\Component\Packages\Packages;

class PackagesTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPackageNames()
    {
        $config = array(
            'package_one' => array(),
            'package_two' => array(),
        );

        $packages = new Packages($config);

        $this->assertEquals(array('package_one', 'package_two'), $packages->getPackagesNames());
    }

    public function testHasPackage()
    {
        $config = array(
            'package_one' => array(),
        );

        $packages = new Packages($config);

        $this->assertTrue($packages->hasPackage('package_one'));
        $this->assertFalse($packages->hasPackage('undefined_package'));
    }

    public function testGetPackageDir()
    {
        $config = array(
            'package_one' => array('dir' => '/package/dir'),
        );

        $packages = new Packages($config);

        $this->assertEquals('/package/dir', $packages->getPackageDir('package_one'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetPackageDirIfPackageNotFound()
    {
        $config = array();

        $packages = new Packages($config);

        $packages->getPackageDir('package_one');
    }
}

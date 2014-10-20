<?php

namespace Butterfly\Component\Packages;

class Packages
{
    /**
     * @var array
     */
    protected $configs;

    /**
     * @param array $packagesConfigs
     */
    public function __construct(array $packagesConfigs)
    {
        $this->configs = $packagesConfigs;
    }

    /**
     * @return array
     */
    public function getPackagesNames()
    {
        return array_keys($this->configs);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasPackage($name)
    {
        return isset($this->configs[$name]);
    }

    /**
     * @param string $name
     * @return string|null
     * @throws \RuntimeException if package is not found
     */
    public function getPackageDir($name)
    {
        $this->throwIsPackageNotFound($name);

        return $this->configs[$name]['dir'];
    }

    /**
     * @param string $name
     * @throws \RuntimeException if package is not found
     */
    protected function throwIsPackageNotFound($name)
    {
        if (!isset($this->configs[$name])) {
            throw new \RuntimeException(sprintf("Package '%s' is not found", $name));
        }
    }
}

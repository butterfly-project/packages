<?php

namespace Butterfly\Component\Packages;

class ComposerConfigAdapter
{
    const DEFAULT_PRIORITY       = 20;
    const PROJECT_COMPONENT_NAME = 'main';
    const DEFAULT_CONFIG_NAME    = 'di.yml';

    protected $baseDir;
    protected $vendorDir;

    protected $diConfigs = array();
    protected $packagesConfigs = array();

    /**
     * @param string $baseDir
     */
    public function __construct($baseDir)
    {
        $this->baseDir   = $baseDir;
        $this->vendorDir = $baseDir . '/vendor';

        $this->init();
    }

    protected function init()
    {
        $mainConfig        = $this->parseJsonFile($this->baseDir . '/composer.json');
        $componentsConfigs = $this->parseJsonFile($this->vendorDir . '/composer/installed.json');

        $this->packagesConfigs[self::PROJECT_COMPONENT_NAME] = array(
            'dir' => $this->baseDir
        );
        foreach ($componentsConfigs as $config) {
            $this->packagesConfigs[$config['name']] = array(
                'dir' => $this->vendorDir . $this->getPackageDir($config),
            );
        }

        $this->diConfigs = $this->calcDiConfigsPaths($componentsConfigs);

        $diConfig = $this->calcDiConfigPath($this->baseDir, $mainConfig);

        if (null !== $diConfig) {
            $this->diConfigs[] = $diConfig;
        }
    }

    /**
     * @param array $packagesConfigs
     * @return array
     * @throws \RuntimeException if DI config is not readable
     */
    protected function calcDiConfigsPaths(array $packagesConfigs)
    {
        $sortedConfigs = $this->sortConfigs($packagesConfigs);

        $diConfigs = array();
        foreach ($sortedConfigs as $config) {
            $packageDir = $this->vendorDir . $this->getPackageDir($config);
            $configPath = $this->calcDiConfigPath($packageDir, $config);

            if (null === $configPath) {
                continue;
            }

            $diConfigs[] = $configPath;
        }

        return $diConfigs;
    }

    /**
     * @param array $composerConfig
     * @return array
     */
    protected function sortConfigs(array $composerConfig)
    {
        usort($composerConfig, function ($a, $b) {
            $aPriority = isset($a['priority']) ? $a['priority'] : self::DEFAULT_PRIORITY;
            $bPriority = isset($b['priority']) ? $b['priority'] : self::DEFAULT_PRIORITY;

            return ($aPriority > $bPriority) ? 1 : -1;
        });

        return $composerConfig;
    }

    /**
     * @param array $config
     * @return string
     */
    protected function getPackageDir(array $config)
    {
        $targetDir = isset($config['target-dir']) ? '/' . $config['target-dir'] : '';

        return '/' . $config['name'] . $targetDir;
    }

    /**
     * @param string $packageDir
     * @param array $config
     * @return null|string
     * @throws \RuntimeException if DI config is not readable
     */
    protected function calcDiConfigPath($packageDir, array $config)
    {
        $isCustomDiConfigName = isset($config['di-config']);

        $configName = $isCustomDiConfigName ? $config['di-config'] : self::DEFAULT_CONFIG_NAME;
        $configPath = $packageDir . '/' . $configName;

        return $this->checkDiConfigPath($configPath, $isCustomDiConfigName) ? $configPath : null;
    }

    /**
     * @param string $configPath
     * @param bool $isFatal
     * @return bool
     * @throws \RuntimeException if DI config is not readable
     */
    protected function checkDiConfigPath($configPath, $isFatal)
    {
        if (is_readable($configPath)) {
            return true;
        }

        if (!$isFatal) {
            return false;
        }

        throw new \RuntimeException(sprintf("DI config '%s' is not readable", $configPath));
    }

    /**
     * @param string $file
     * @return array
     */
    protected function parseJsonFile($file)
    {
        return is_readable($file) ? json_decode(file_get_contents($file), true) : array();
    }

    /**
     * @return array
     */
    public function getPackagesConfigs()
    {
        return $this->packagesConfigs;
    }

    /**
     * @return array
     */
    public function getDiConfigs()
    {
        return $this->diConfigs;
    }
}

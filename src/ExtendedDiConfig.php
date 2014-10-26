<?php

namespace Butterfly\Component\Packages;

use Butterfly\Component\Config\ConfigBuilder;
use Butterfly\Component\DI\Builder\DiConfig;
use Butterfly\Component\DI\Container;

class ExtendedDiConfig extends DiConfig
{
    const PARAMETER_APP_ROOT_DIR    = 'app.dir.root';
    const PARAMETER_PACKAGES_CONFIG = 'butterfly.packages.config';

    const CONFIG_MODULE             = 'butterfly/config';
    const COMPILED_DI_CONFIG_PATH   = '/etc/di.php';

    /**
     * @param string $rootDir
     * @param string $output
     * @param array $additionalConfigPaths
     */
    public static function buildForComposer($rootDir, $output, array $additionalConfigPaths = array())
    {
        $composerAdapter = new ComposerConfigAdapter($rootDir);
        $configBuilder   = self::getConfigBuilder($composerAdapter);

        self::addPathsToConfigBuilder($configBuilder, $composerAdapter->getDiConfigs());
        self::addPathsToConfigBuilder($configBuilder, $additionalConfigPaths);

        $configBuilder->addConfiguration(array(
            self::PARAMETER_APP_ROOT_DIR    => $rootDir,
            self::PARAMETER_PACKAGES_CONFIG => $composerAdapter->getPackagesConfigs(),
        ));

        self::build($configBuilder->getData(), $output);
    }

    /**
     * @param ComposerConfigAdapter $composerAdapter
     * @return ConfigBuilder
     */
    protected static function getConfigBuilder(ComposerConfigAdapter $composerAdapter)
    {
        $packagesConfig = $composerAdapter->getPackagesConfigs();
        $diConfig       = $packagesConfig[self::CONFIG_MODULE]['dir'] . self::COMPILED_DI_CONFIG_PATH;
        $container      = new Container(require $diConfig);

        return $container->get('butterfly.config.builder');
    }

    /**
     * @param ConfigBuilder $builder
     * @param array $paths
     */
    protected static function addPathsToConfigBuilder(ConfigBuilder $builder, array $paths)
    {
        foreach ($paths as $path) {
            $builder->addPath($path);
        }
    }
}
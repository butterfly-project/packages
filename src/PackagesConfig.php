<?php

namespace Butterfly\Component\Packages;

use Butterfly\Component\Annotations\ClassFinder\ClassFinder;
use Butterfly\Component\Annotations\IClassParser;
use Butterfly\Component\Annotations\Visitor\AnnotationsHandler;
use Butterfly\Component\Config\ConfigBuilder;
use Butterfly\Component\DI\Compiler\Annotation\AnnotationConfigVisitor;
use Butterfly\Component\DI\Compiler\ConfigCompiler;

class PackagesConfig
{
    const PARAMETER_APP_ROOT_DIR    = 'app.dir.root';
    const PARAMETER_PACKAGES_CONFIG = 'butterfly.packages.config';

    /**
     * @param string $rootDir
     * @param IClassParser $classParser
     * @param ConfigBuilder $configBuilder
     * @param array $additionalConfigPaths
     * @param array $additionalConfiguration
     * @return array
     */
    public static function buildForComposer($rootDir, IClassParser $classParser, ConfigBuilder $configBuilder = null, array $additionalConfigPaths = array(), array $additionalConfiguration = array())
    {
        if (null === $configBuilder) {
            $configBuilder = ConfigBuilder::createInstance();
        }

        $composerAdapter = new ComposerConfigAdapter($rootDir);

        $configBuilder->addPaths($composerAdapter->getDiConfigs());
        $configBuilder->addPaths($additionalConfigPaths);

        $configBuilder->addConfiguration(array_merge(array(
            self::PARAMETER_APP_ROOT_DIR    => $rootDir,
            self::PARAMETER_PACKAGES_CONFIG => $composerAdapter->getPackagesConfigs(),
        ), $additionalConfiguration));

        $annotations = self::parseAnnotations($composerAdapter->getAnnotationDirs(), $classParser);

        $configBuilder->addConfiguration(self::convertAnnotations($annotations));
        $configBuilder->addConfiguration(array(
            'annotations' => $annotations,
        ));

        return $configBuilder->getData();
    }

    /**
     * @param array $paths
     * @param IClassParser $classParser
     * @return array
     */
    protected static function parseAnnotations(array $paths, IClassParser $classParser)
    {
        $classFinder = new ClassFinder(array('php'));

        $annotations = array();

        foreach ($paths as $path) {
            $classes = $classFinder->findClassesInDir($path);

            foreach ($classes as $class) {
                $annotations[$class] = $classParser->parseClass($class);
            }
        }

        return $annotations;
    }

    /**
     * @param array $annotations
     * @return array
     */
    protected static function convertAnnotations(array $annotations)
    {
        $visitor = new AnnotationConfigVisitor();
        $handler = new AnnotationsHandler(array($visitor));
        $handler->handle($annotations);

        return $visitor->extractDiConfiguration();
    }
}

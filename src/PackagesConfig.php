<?php

namespace Butterfly\Component\Packages;

use Butterfly\Component\Annotations\ClassParser;
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
     * @param array $additionalConfigPaths
     * @param array $additionalConfiguration
     * @return array
     */
    public static function buildForComposer($rootDir, array $additionalConfigPaths = array(), array $additionalConfiguration = array())
    {
        $composerAdapter = new ComposerConfigAdapter($rootDir);
        $configBuilder = ConfigBuilder::createInstance();

        $configBuilder->addPaths($composerAdapter->getDiConfigs());
        $configBuilder->addPaths($additionalConfigPaths);

        $configBuilder->addConfiguration(array_merge(array(
            self::PARAMETER_APP_ROOT_DIR    => $rootDir,
            self::PARAMETER_PACKAGES_CONFIG => $composerAdapter->getPackagesConfigs(),
        ), $additionalConfiguration));

        $annotationPaths = $composerAdapter->getAnnotationDirs();
        foreach ($annotationPaths as $annotationDir) {
            $annotations = ClassParser::createInstance()->parseClassesInDir($annotationDir);
            $configBuilder->addConfiguration(self::convertAnnotations($annotations));
        }

        return ConfigCompiler::compile($configBuilder->getData());
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

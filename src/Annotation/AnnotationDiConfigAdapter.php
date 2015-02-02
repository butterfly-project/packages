<?php

namespace Butterfly\Component\Packages\Annotation;

class AnnotationDiConfigAdapter
{
    /**
     * @var array
     */
    protected $annotations;

    /**
     * @var array
     */
    protected $services;

    /**
     * @param array $classesAnnotation
     * @return array
     */
    public function extractDiConfiguration(array $classesAnnotation)
    {
        $this->services = array();
        $this->annotations = $classesAnnotation;

        $this->resolve();

        return array(
            'services' => $this->services,
        );
    }

    protected function resolve()
    {
        foreach ($this->annotations as $className => $classAnnotation) {
            if (!array_key_exists('service', $classAnnotation['class'])) {
                continue;
            }

            $this->resolveService($className, $classAnnotation);
        }
    }

    /**
     * @param string $className
     * @param array $classAnnotation
     */
    protected function resolveService($className, array $classAnnotation)
    {
        $configuration = array();

        if (null !== $classAnnotation['class']['service']) {
            $serviceName = $classAnnotation['class']['service'];
            $configuration['alias'] = $className;
        } else {
            $serviceName = $className;
        }

        $configuration['class'] = $className;

        $reflectionClass = new ReflectionClass($className);

        foreach ($classAnnotation['properties'] as $propertyName => $propertyAnnotation) {
            if (!array_key_exists('autowired', $propertyAnnotation)) {
                continue;
            }

            if (is_array($propertyAnnotation['autowired'])) {
                throw new \RuntimeException(sprintf("Incorrect @autowired value in %s property. Expected service name (string type), array given.", $propertyName));
            } elseif (null === $propertyAnnotation['autowired']) {
                $namespace = $reflectionClass->getFullNamespace($propertyAnnotation['var']);
                $innerServiceName = substr($namespace, 1);
                $configuration['properties'][$propertyName] = $innerServiceName;
            } else {
                $words = explode(' ', $propertyAnnotation['autowired']);
                $innerServiceName = reset($words);
                $configuration['properties'][$propertyName] = $innerServiceName;
            }
        }

        foreach ($classAnnotation['methods'] as $methodName => $methodAnnotation) {
            if (!array_key_exists('autowired', $methodAnnotation)) {
                continue;
            }

            if (null === $methodAnnotation['autowired']) {
                $reflectionMethod = $reflectionClass->getMethod($methodName);

                $arguments = $this->getMethodTypesForNative($reflectionMethod->getParameters());

                if (null !== $arguments) {
                    $configuration['calls'][] = array($methodName, $arguments);

                    continue;
                }

                if (!empty($methodAnnotation['param'])) {
                    $arguments = array();
                    foreach ($methodAnnotation['param'] as $value) {
                        $words = array_filter(explode(' ', $value));
                        $shortType = array_shift($words);
                        $fullNamespace = $reflectionClass->getFullNamespace($shortType);
                        $arguments[] = '@' . substr($fullNamespace, 1);
                    }

                    $configuration['calls'][] = array($methodName, $arguments);
                }
            } elseif (is_array($methodAnnotation['autowired'])) {

                $arguments = array();

                foreach ($methodAnnotation['autowired'] as $dependency) {
                    $arguments[] = ('%' != $dependency[0]) ? '@' . $dependency : $dependency;
                }

                $configuration['calls'][] = array($methodName, $arguments);
            }
        }

        if (!empty($classAnnotation['class']['scope'])) {
            $configuration['scope'] = (string)$classAnnotation['class']['scope'];
        }


        if (!empty($classAnnotation['class']['tags'])) {
            $configuration['tags'] = (array)$classAnnotation['class']['tags'];
        }

        $this->services[$serviceName] = $configuration;
    }

    /**
     * @param \ReflectionParameter[] $reflectionParameters
     * @return array|null
     */
    protected function getMethodTypesForNative(array $reflectionParameters)
    {
        $arguments = array();

        /** @var \ReflectionParameter[] $reflectionParameters */
        foreach ($reflectionParameters as $reflectionParameter) {
            $position             = $reflectionParameter->getPosition();
            $nativeTypeClass      = $reflectionParameter->getClass();

            if (null === $nativeTypeClass) {
                return null;
            }

            $innerServiceName     = '@' . $nativeTypeClass->getName();
            $arguments[$position] = $innerServiceName;
        }

        return $arguments;
    }
}

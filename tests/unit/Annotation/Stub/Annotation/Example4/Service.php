<?php

namespace Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example4;

/**
 * @service service.base
 */
class Service
{
    /**
     * @autowired service.inner
     *
     * @var \stdClass
     */
    protected $innerService;

    /**
     * @autowired parameter.inner
     *
     * @var string
     */
    protected $innerProperty;
}

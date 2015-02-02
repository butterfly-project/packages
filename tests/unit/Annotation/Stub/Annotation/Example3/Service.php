<?php

namespace Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example3;

use Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example3\DirA\InnerService;

/**
 * @service service.base
 */
class Service
{
    /**
     * @autowired
     *
     * @var InnerService
     */
    protected $inner;
}

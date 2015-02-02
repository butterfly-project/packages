<?php

namespace Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example5;

use Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example5\DirA\InnerService;
use Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example5\DirA\Inner2Service;

/**
 * @service service.base
 */
class Service
{
    /**
     * @autowired
     */
    public function init(InnerService $innerService, Inner2Service $inner2Service)
    {

    }
}

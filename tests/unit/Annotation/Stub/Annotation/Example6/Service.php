<?php

namespace Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example6;

use Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example6\DirA\InnerService;
use Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example6\DirA\Inner2Service;

/**
 * @service service.base
 */
class Service
{
    /**
     * @autowired
     *
     * @param InnerService $innerService
     * @param Inner2Service $inner2Service
     */
    public function init($innerService, $inner2Service)
    {

    }
}

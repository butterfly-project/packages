<?php

namespace Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example10;

use Butterfly\Component\Packages\Tests\Annotation\Stub\Annotation\Example10\DirA\InnerService;

/**
 * @service service.base
 */
class Service
{
    /**
     * @var InnerService
     */
    protected $innerService;

    /**
     * @autowired ["service.inner"]
     *
     * @param InnerService $innerService
     */
    public function __construct(InnerService $innerService)
    {
        $this->innerService = $innerService;
    }
}

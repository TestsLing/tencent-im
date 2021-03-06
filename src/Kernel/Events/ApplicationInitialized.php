<?php

namespace EasyIM\Kernel\Events;

use EasyIM\Kernel\ServiceContainer;

/**
 * Class ApplicationInitialized.
 */
class ApplicationInitialized
{
    /**
     * @var \EasyIM\Kernel\ServiceContainer
     */
    public $app;

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }
}

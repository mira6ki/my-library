<?php

namespace Traits;

use Log\LogErrorService;

trait LogErrorServiceTrait
{
    public function getLogErrorsService(): LogErrorService
    {
        return new LogErrorService();
    }
}

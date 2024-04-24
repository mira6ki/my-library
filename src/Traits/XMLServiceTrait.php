<?php

namespace Traits;

use Service\XmlService;

trait XMLServiceTrait
{
    public function getXMLService(): XMLService
    {
        return new XMLService();
    }
}

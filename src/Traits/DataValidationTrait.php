<?php

namespace Traits;

trait DataValidationTrait
{
    protected function filterInput($input): string
    {
        $filteredInput = trim($input);

        $filteredInput = stripslashes($filteredInput);

        $filteredInput = strip_tags($filteredInput);

        $filteredInput = htmlspecialchars($filteredInput);

        return $filteredInput;
    }
}

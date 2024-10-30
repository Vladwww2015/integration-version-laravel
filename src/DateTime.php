<?php

namespace IntegrationHelper\IntegrationVersionLaravel;

use Carbon\Carbon;
use IntegrationHelper\IntegrationVersion\DateTimeInterface;

class DateTime implements DateTimeInterface
{
    public function getNow(): string
    {
        return Carbon::now();
    }
}

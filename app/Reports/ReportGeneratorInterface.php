<?php

namespace App\Reports;

use Carbon\Carbon;

interface ReportGeneratorInterface
{
    public function generate(Carbon $from, Carbon $to): array;

    public function getTitle(): string;
}
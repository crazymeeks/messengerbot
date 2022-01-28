<?php

namespace Reports;

interface ReportInterface
{

    /**
     * Get report data
     *
     * @return array
     */
    public function getData(): array;

    
}
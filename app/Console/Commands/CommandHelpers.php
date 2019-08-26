<?php

namespace App\Console\Commands;

trait CommandHelpers
{
    /**
     * Process start time
     *
     * @var float
     */
    protected $processStart = 0;

    /**
     * Process end time
     *
     * @var float
     */
    protected $processEnd = 0;

    /**
     * Total items processed
     *
     * @var null
     */
    protected $processed = null;

    protected function goodbye()
    {
        $processLabel = $this->calculateProcessTime();

        $this->info('');
        $this->info('>>>> Process completed in '.$processLabel.'! <<<<');
        if ($this->processed) {
            $this->info('>>>> A total of '.$this->processed.' items were processed. <<<<');
        }
        die();
    }

    protected function calculateProcessTime()
    {
        $this->processEnd = microtime(true);

        $processTime = $this->processEnd - $this->processStart;
        $processLabel = ' seconds';
        if ($processTime > 90) {
            $processTime = $processTime / 60;
            $processLabel = ' minutes';
        }

        return round($processTime, 2).$processLabel;
    }

    protected function updateStatus($status)
    {
        $this->info('----> '.$status);
    }
}

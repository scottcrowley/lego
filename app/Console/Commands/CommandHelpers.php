<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Schema;

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
    protected $processed = 0;

    /**
     * RebrickableApi(Lego|User) instance
     *
     * @var object
     */
    protected $api;

    /**
     * End the command execution and display process time
     *
     * @return void
     */
    protected function goodbye()
    {
        $processLabel = $this->calculateProcessTime();

        $this->info('');
        $this->info('');
        $this->warn('>>>> Process completed in '.$processLabel.'! <<<<');
        $this->displayProcessed();

        die();
    }

    /**
     * Display the number of processed items
     *
     * @return void
     */
    protected function displayProcessed()
    {
        if ($this->processed) {
            $this->warn('>>>> A total of '.$this->processed.' items were processed. <<<<');
        }
    }

    /**
     * Displays an error and exits command
     *
     * @return void
     */
    protected function displayError($message)
    {
        $this->error(">>>> $message <<<<");
        die();
    }

    /**
     * Calculates the total process time for the command
     *
     * @return void
     */
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

    /**
     * Adds a info message to be displayed
     *
     * @param string $status
     * @return void
     */
    protected function updateStatus($status)
    {
        $this->info('----> '.$status);
    }

    /**
     * Sets up the api instance base on the type given
     *
     * @param string $type
     * @return void
     */
    protected function setupApiInstance($type)
    {
        $this->updateStatus('Setting up api instance...');

        $this->api = ($type == 'lego') ? new \App\Gateways\RebrickableApiLego : new \App\Gateways\RebrickableApiUser;
    }

    /**
     * Sets up the RebrickableApiLego instance
     *
     * @return void
     */
    protected function setupApiLegoInstance()
    {
        $this->setupApiInstance('lego');
    }

    /**
     * Sets up the RebrickableApiUser instance
     *
     * @return void
     */
    protected function setupApiUserInstance()
    {
        $this->setupApiInstance('user');
    }

    /**
     * Truncate the table for a given model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected function truncateTable($model)
    {
        $this->updateStatus('Truncating table...');

        Schema::disableForeignKeyConstraints();
        $model::truncate();
        Schema::enableForeignKeyConstraints();
    }
}

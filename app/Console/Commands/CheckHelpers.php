<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;

trait CheckHelpers
{
    /**
     * Names to be used as keys for import
     *
     * @var Collection
     */
    protected $keys = [];

    /**
     * Table names and primary keys to verify against
     *
     * @var array
     */
    protected $checkTables = [];

    /**
     * All dependencies from the database
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * Missing dependencies
     *
     * @var array
     */
    protected $missingDeps = [];

    /**
     * Dependencies loaded from csv used to compare against database
     *
     * @var array
     */
    protected $loadedDeps = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->start()) {
            return false;
        }

        if (! $this->option('bulk')) {
            $this->processStart = microtime(true);
        }

        $this->info('');
        $this->getDependencies();
        $this->checkDirectory();
        $this->retrieveFile();
        $this->displayCsvDate();
        $this->loadDependenciesFromCSV();
        $this->compileMissingDependencies();
        $this->cleanUp();
        $this->displayMissing();
        if (! $this->option('bulk')) {
            $this->goodbye();
        }
        $this->displayProcessed();
    }

    /**
     * Retrieve dependencies from database for each table
     *
     * @return void
     */
    protected function getDependencies()
    {
        $this->updateStatus('Getting dependencies from the Database...');

        foreach ($this->checkTables as $table => $options) {
            $data = DB::table($table)->get();
            $this->dependencies[$table] = $data->keyBy($options[0]);
        }
    }

    /**
     * Load dependencies for each table from csv file
     *
     * @return void
     */
    protected function loadDependenciesFromCSV()
    {
        $this->updateStatus('Loading dependencies from CSV file...');

        $this->lazyCollectionFromCsv()
            ->chunk(500)
            ->each(function ($rows) use (&$processed) {
                foreach ($rows as $row) {
                    foreach ($this->checkTables as $table => $options) {
                        $this->loadedDeps[$table][$row[$options[1]]] = $row[$options[1]];
                    }
                    $processed++;
                }

                $this->info('  => Processed '.$processed.' rows');
            });

        $this->processed = $processed;
    }

    /**
     * Verify dependencies loaded from csv against what is in the database
     *
     * @return void
     */
    protected function compileMissingDependencies()
    {
        $this->updateStatus('Verifying missing dependencies....');

        foreach ($this->checkTables as $table => $options) {
            $this->missingDeps[$table] = (collect($this->loadedDeps[$table]))->diffKeys($this->dependencies[$table]);
        }
    }

    /**
     * Display any missing dependencies
     *
     * @return void
     */
    protected function displayMissing()
    {
        $this->info('>>> Missing Dependencies');

        foreach ($this->missingDeps as $table => $dependencies) {
            $this->info('Table: '.$table.' ('.($dependencies->count()).')');
            if ($dependencies->count()) {
                $this->error(implode(', ', $dependencies->toArray()));
            } else {
                $this->warn('   None missing');
            }
        }
    }
}

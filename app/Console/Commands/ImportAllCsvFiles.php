<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportAllCsvFiles extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-all-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes all commands that import csv files from Rebrickable';

    /**
     * Run the lego:import-inventory-parts-csv command
     *
     * @var boolean
     */
    protected $runInventoryParts = false;

    /**
     * List of commands to execute
     *
     * @var boolean
     */
    protected $commandList = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->commandList = [
            'lego:import-colors-csv',
            'lego:import-part-categories-csv',
            'lego:import-themes-csv',
            'lego:theme-hierarchy',
            'lego:import-sets-csv',
            'lego:import-parts-csv',
            'lego:category-part-count',
            'lego:import-part-relationships-csv',
            'lego:import-inventories-csv',
        ];
    }

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

        $this->processStart = microtime(true);

        $this->info('');
        foreach ($this->commandList as $command) {
            $this->info('');
            $this->info('^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^');
            $this->call($command, ['--bulk' => true]);
        }
        $this->info('');
        $this->info('<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>');
        $this->info('<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>');
        $this->goodbye();
    }

    /**
     * Display command details and request confirmation to continue
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('>> This command will execute several sub commands to import files from Rebrickable.');
        $this->info('   The following commands will be executed:');
        foreach ($this->commandList as $command) {
            $this->info('       '.$command);
        }
        $this->info('   The lego:import-inventory-parts-csv command is not included due to how memory intensive it is.');
        $this->info('   All of these commands should take less than a minute to run.');
        return $this->confirm('Continue?');
    }
}

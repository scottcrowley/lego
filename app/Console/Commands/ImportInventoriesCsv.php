<?php

namespace App\Console\Commands;

use App\Inventory;
use Illuminate\Console\Command;

class ImportInventoriesCsv extends Command
{
    use CommandHelpers, CsvHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-inventories-csv
                            {--bulk : Command being run with other commands}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all Inventories from Rebrickable using their generated csv file.';

    /**
     * CSV File Url
     *
     * @var string
     */
    protected $url = 'https://cdn.rebrickable.com/media/downloads/inventories.csv.gz';

    /**
     * Names to be used as keys for import
     *
     * @var array
     */
    protected $keys = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->keys = collect([
            'id',
            'version',
            'set_num',
        ]);
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

        if (! $this->option('bulk')) {
            $this->processStart = microtime(true);
        }

        $this->info('');
        $this->checkDirectory();
        $this->retrieveFile();
        $this->displayCsvDate();
        $this->truncateTable(new Inventory());
        $this->importInventories();
        $this->cleanUp();
        if (! $this->option('bulk')) {
            $this->goodbye();
        }
        $this->displayProcessed();
    }

    /**
     * Display command details and request confirmation to continue
     *
     * @return bool
     */
    protected function start()
    {
        if (! $this->option('bulk')) {
            $this->info('>> This command will import all the Inventories from Rebrickable');
            $this->info('   It is advisable that you first update the Sets by running either');
            $this->info('   the lego:import-sets or lego:import-sets-csv commands');
            return $this->confirm('Continue?');
        }
        $this->info('>> Please wait while we import all the Inventories from Rebrickable <<');
        return true;
    }

    /**
     * Process to add inventories to database
     *
     * @return void
     */
    protected function importInventories()
    {
        $this->updateStatus('Importing Rebrickable Inventories into Database...');

        $processed = $this->processed;

        $this->lazyCollectionFromCsv()
            ->chunk(1000)
            ->each(function ($inventories) use (&$processed) {
                $inventoryList = [];
                foreach ($inventories as $inventory) {
                    $inventoryList[] = [
                        'id' => intval($inventory['id']),
                        'version' => intval($inventory['version']),
                        'set_num' => $inventory['set_num'],
                    ];

                    $processed++;
                }

                if (count($inventoryList)) {
                    Inventory::insert($inventoryList);
                }
            });

        $this->processed = $processed;
    }
}

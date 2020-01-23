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
    protected $signature = 'lego:import-inventories-csv';

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
        $this->processStart = microtime(true);

        $this->start();

        $this->info('');
        $this->checkDirectory();
        $this->retrieveFile();
        $this->truncateTable(new Inventory());
        $this->importInventories();
        $this->cleanUp();
        $this->goodbye();
    }

    /**
     * Display command details
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('>> Please wait while we import all the Inventories from Rebrickable <<');
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

        $this->lazyCollection()
            ->chunk(1000)
            ->each(function ($inventories) use (&$processed) {
                $inventoryList = [];
                foreach ($inventories as $inventoryRow) {
                    $inventory = $this->keys->combine(str_getcsv($inventoryRow), ',');
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

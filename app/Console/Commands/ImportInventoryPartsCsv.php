<?php

namespace App\Console\Commands;

use App\InventoryPart;
use Illuminate\Console\Command;

class ImportInventoryPartsCsv extends Command
{
    use CommandHelpers, CsvHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-inventory-parts-csv
                            {--bulk : Command being run with other commands}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all Inventory Parts from Rebrickable using their generated csv file.';

    /**
     * CSV File Url
     *
     * @var string
     */
    protected $url = 'https://cdn.rebrickable.com/media/downloads/inventory_parts.csv.gz';

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
            'inventory_id',
            'part_num',
            'color_id',
            'quantity',
            'is_spare',
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
        $this->truncateTable(new InventoryPart());
        $this->importInventoryParts();
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
            $this->info('>> This command will import all the Inventory Parts from Rebrickable');
            $this->info('   It is advisable that you first update the Parts by running');
            $this->info('   the lego:import-parts-csv command');
            $this->info('   Also, update the Inventories by running the lego:import-inventories-csv command <<');
            return $this->confirm('More than 800,000 rows may be imported thus taking a VERY long time to execute. Continue?');
        }
        $this->info('>> Please wait while we import all the Inventory Parts from Rebrickable <<');
        return true;
    }

    /**
     * Process to add inventory parts to database
     *
     * @return void
     */
    protected function importInventoryParts()
    {
        $this->updateStatus('Importing Rebrickable Inventory Parts into Database...');

        $processed = $this->processed;

        $this->lazyCollectionFromCsv()
            ->chunk(500)
            ->each(function ($inventoryParts) use (&$processed) {
                $inventoryPartList = [];
                foreach ($inventoryParts as $inventoryPart) {
                    $inventoryPartList[] = [
                        'inventory_id' => intval($inventoryPart['inventory_id']),
                        'part_num' => $inventoryPart['part_num'],
                        'color_id' => intval($inventoryPart['color_id']),
                        'quantity' => intval($inventoryPart['quantity']),
                        'is_spare' => $inventoryPart['is_spare'],
                    ];

                    $processed++;
                }

                if (count($inventoryPartList)) {
                    InventoryPart::insert($inventoryPartList);
                }

                $this->updateStatus('Processed '.$processed.' Parts');
            });

        $this->processed = $processed;
    }
}

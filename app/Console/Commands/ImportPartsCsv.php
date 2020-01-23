<?php

namespace App\Console\Commands;

use App\Part;
use Illuminate\Console\Command;

class ImportPartsCsv extends Command
{
    use CommandHelpers, CsvHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-parts-csv
                            {--bulk : Command being run with other commands}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all Parts from Rebrickable using their generated csv file.';

    /**
     * CSV File Url
     *
     * @var string
     */
    protected $url = 'https://cdn.rebrickable.com/media/downloads/parts.csv.gz';

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
            'part_num',
            'name',
            'part_category_id',
            'part_material_id',
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
        $this->truncateTable(new Part());
        $this->importParts();
        $this->cleanUp();

        if (! $this->option('bulk')) {
            $this->info('');
            $this->info('');
            $this->call('lego:category-part-count', ['--bulk' => true]);
            $this->goodbye();
        }
        $this->displayProcessed();
    }

    /**
     * Display command details
     *
     * @return bool
     */
    protected function start()
    {
        if (! $this->option('bulk')) {
            $this->info('>> This command will import all the Parts from Rebrickable');
            $this->info('   It is advisable that you first update the Part Categories by running either');
            $this->info('   the lego:import-part-categories or lego:import-part-categories-csv commands');
            return $this->confirm('Continue?');
        }
        $this->info('>> Please wait while we import all the Parts from Rebrickable <<');
        return true;
    }

    /**
     * Process to add parts to database
     *
     * @return void
     */
    protected function importParts()
    {
        $this->updateStatus('Importing Rebrickable Parts into Database...');

        $processed = $this->processed;

        $this->lazyCollection()
            ->chunk(1000)
            ->each(function ($parts) use (&$processed) {
                $partList = [];
                foreach ($parts as $part) {
                    $partList[] = [
                        'part_num' => $part['part_num'],
                        'name' => $part['name'],
                        'part_category_id' => intval($part['part_category_id']),
                        'part_material_id' => intval($part['part_material_id']),
                    ];

                    $processed++;
                }

                if (count($partList)) {
                    Part::insert($partList);
                }
            });

        $this->processed = $processed;
    }
}

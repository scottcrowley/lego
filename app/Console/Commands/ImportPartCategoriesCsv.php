<?php

namespace App\Console\Commands;

use App\PartCategory;
use Illuminate\Console\Command;

class ImportPartCategoriesCsv extends Command
{
    use CommandHelpers, CsvHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-part-categories-csv
                            {--bulk : Command being run with other commands}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all Part Categories from Rebrickable using their generated csv file.';

    /**
     * CSV File Url
     *
     * @var string
     */
    protected $url = 'https://cdn.rebrickable.com/media/downloads/part_categories.csv.gz';

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
            'name',
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->option('bulk')) {
            $this->processStart = microtime(true);
        }

        $this->start();

        $this->info('');
        $this->checkDirectory();
        $this->retrieveFile();
        $this->displayCsvDate();
        $this->truncateTable(new PartCategory());
        $this->importPartCategories();
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
        $this->info('>> Please wait while we import all the Part Categories from Rebrickable <<');
    }

    /**
     * Process to add part categories to database
     *
     * @return void
     */
    protected function importPartCategories()
    {
        $this->updateStatus('Importing Rebrickable Part Categories into Database...');

        $processed = $this->processed;

        $this->lazyCollection()
            ->chunk(1000)
            ->each(function ($partCategories) use (&$processed) {
                $partCategoryList = [];
                foreach ($partCategories as $partCategory) {
                    $partCategoryList[] = [
                        'id' => $partCategory['id'],
                        'name' => $partCategory['name'],
                    ];

                    $processed++;
                }

                if (count($partCategoryList)) {
                    PartCategory::insert($partCategoryList);
                }
            });

        $this->processed = $processed;
    }
}

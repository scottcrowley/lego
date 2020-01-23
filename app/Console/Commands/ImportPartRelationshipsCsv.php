<?php

namespace App\Console\Commands;

use App\PartRelationship;
use Illuminate\Console\Command;

class ImportPartRelationshipsCsv extends Command
{
    use CommandHelpers, CsvHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-part-relationships-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all Part Relationships from Rebrickable using their generated csv file.';

    /**
     * CSV File Url
     *
     * @var string
     */
    protected $url = 'https://cdn.rebrickable.com/media/downloads/part_relationships.csv.gz';

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
            'rel_type',
            'child_part_num',
            'parent_part_num',
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
        $this->truncateTable(new PartRelationship());
        $this->importPartRelationships();
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
        $this->info('>> Please wait while we import all the Part Relationships from Rebrickable <<');
    }

    /**
     * Process to add part relationships to database
     *
     * @return void
     */
    protected function importPartRelationships()
    {
        $this->updateStatus('Importing Rebrickable Part Relationships into Database...');

        $processed = $this->processed;

        $this->lazyCollection()
            ->chunk(1000)
            ->each(function ($partRelationships) use (&$processed) {
                $partRelationshipList = [];
                foreach ($partRelationships as $partRelationshipRow) {
                    $partRelationship = $this->keys->combine(str_getcsv($partRelationshipRow), ',');
                    $partRelationshipList[] = [
                        'rel_type' => $partRelationship['rel_type'],
                        'child_part_num' => $partRelationship['child_part_num'],
                        'parent_part_num' => $partRelationship['parent_part_num'],
                    ];

                    $processed++;
                }

                if (count($partRelationshipList)) {
                    PartRelationship::insert($partRelationshipList);
                }
            });

        $this->processed = $processed;
    }
}

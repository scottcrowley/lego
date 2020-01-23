<?php

namespace App\Console\Commands;

use App\Set;
use Illuminate\Console\Command;

class ImportSetsCsv extends Command
{
    use CommandHelpers, CsvHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-sets-csv
                            {--bulk : Command being run with other commands}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all Sets from Rebrickable using their generated csv file.';

    /**
     * CSV File Url
     *
     * @var string
     */
    protected $url = 'https://cdn.rebrickable.com/media/downloads/sets.csv.gz';

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
            'set_num',
            'name',
            'year',
            'theme_id',
            'num_parts',
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
        $this->truncateTable(new Set());
        $this->importSets();
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
            $this->info('>> This command will import all the Sets from Rebrickable');
            $this->info('   It is advisable that you first update the Themes by running either');
            $this->info('   the lego:import-themes or lego:import-themes-csv commands');
            return $this->confirm('Continue?');
        }
        $this->info('>> Please wait while we import all the Sets from Rebrickable <<');
        return true;
    }

    /**
     * Process to add sets to database
     *
     * @return void
     */
    protected function importSets()
    {
        $this->updateStatus('Importing Rebrickable Sets into Database...');

        $processed = $this->processed;

        $this->lazyCollection()
            ->chunk(1000)
            ->each(function ($sets) use (&$processed) {
                $setList = [];
                foreach ($sets as $set) {
                    $setList[] = [
                        'set_num' => $set['set_num'],
                        'name' => $set['name'],
                        'year' => intval($set['year']),
                        'theme_id' => ($set['theme_id'] != '') ? intval($set['theme_id']) : null,
                        'num_parts' => intval($set['num_parts']),
                    ];

                    $processed++;
                }

                if (count($setList)) {
                    Set::insert($setList);
                }
            });

        $this->processed = $processed;
    }
}

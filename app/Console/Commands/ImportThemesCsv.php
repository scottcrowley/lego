<?php

namespace App\Console\Commands;

use App\Theme;
use Illuminate\Console\Command;

class ImportThemesCsv extends Command
{
    use CommandHelpers, CsvHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-themes-csv
                            {--bulk : Command being run with other commands}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all Themes from Rebrickable using their generated csv file.';

    /**
     * CSV File Url
     *
     * @var string
     */
    protected $url = 'https://cdn.rebrickable.com/media/downloads/themes.csv.gz';

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
            'parent_id',
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
        $this->truncateTable(new Theme());
        $this->importThemes();
        $this->cleanUp();

        if (! $this->option('bulk')) {
            $this->info('');
            $this->info('');
            $this->call('lego:theme-hierarchy', ['--bulk' => true]);
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
        $this->info('>> Please wait while we import all the Themes from Rebrickable <<');
    }

    /**
     * Process to add themes to database
     *
     * @return void
     */
    protected function importThemes()
    {
        $this->updateStatus('Importing Rebrickable Themes into Database...');

        $processed = $this->processed;

        $this->lazyCollection()
            ->chunk(1000)
            ->each(function ($themes) use (&$processed) {
                $themeList = [];
                foreach ($themes as $theme) {
                    $themeList[] = [
                        'id' => $theme['id'],
                        'name' => $theme['name'],
                        'parent_id' => ($theme['parent_id'] != '') ? intval($theme['parent_id']) : null,
                    ];

                    $processed++;
                }

                if (count($themeList)) {
                    Theme::insert($themeList);
                }
            });

        $this->processed = $processed;
    }
}

<?php

namespace App\Console\Commands;

use App\Theme;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Storage;
use wapmorgan\UnifiedArchive\UnifiedArchive;

class ImportThemesCsv extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-themes-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all themes from Rebrickable using their generated csv file.';

    /**
     * Rebrickable Themes
     *
     * @var null
     */
    protected $themes = null;

    /**
     * CSV File Url
     *
     * @var null
     */
    protected $url = 'https://cdn.rebrickable.com/media/downloads/themes.csv.gz';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
        $this->truncateTable(new Theme());
        $this->importThemes();
        $this->goodbye();
    }

    /**
     * Display command details and request confirmation to continue
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('>> Please wait while we import all the themes from Rebrickable <<');
    }

    /**
     * Reset directory
     *
     * @return void
     */
    protected function checkDirectory()
    {
        Storage::deleteDirectory('csv_files');
        Storage::makeDirectory('csv_files');
    }

    /**
     * Retrieve the actual csv file from rebrickable
     *
     * @return void
     */
    protected function retrieveFile()
    {
        $this->updateStatus('Retrieving CSV file from Rebrickable...');

        $csv = file_get_contents($this->url);
        if (Storage::put('csv_files/themes.csv.gz', $csv)) {
            $file = UnifiedArchive::open(storage_path('app/csv_files/themes.csv.gz'));
            $file->extractFiles(storage_path('app/csv_files/'));
            Storage::move('csv_files/themes.csv.gz', 'csv_files/themes.csv');
            return true;
        }

        $this->error('** The file was unable to be retrieved from '.$this->url.' **');
        $this->goodbye();
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

        LazyCollection::make(function () {
            $handle = fopen(storage_path('app/csv_files/themes.csv'), 'r');

            while (($line = fgets($handle)) !== false) {
                if (substr($line, 0, 2) == 'id') {
                    continue;
                }
                yield $line;
            }
        })->chunk(1000)->each(function ($themes) use (&$processed) {
            $themeList = [];
            foreach ($themes as $themeRow) {
                $theme = explode(',', $themeRow);
                $theme[2] = str_replace("\n", '', $theme[2]);
                $themeList[] = [
                    'id' => $theme[0],
                    'name' => $theme[1],
                    'parent_id' => ($theme[2] != '') ? intval($theme[2]) : null,
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

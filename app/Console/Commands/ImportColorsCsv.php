<?php

namespace App\Console\Commands;

use App\Color;
use Illuminate\Console\Command;

class ImportColorsCsv extends Command
{
    use CommandHelpers, CsvHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-colors-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all Colors from Rebrickable using their generated csv file.';

    /**
     * CSV File Url
     *
     * @var string
     */
    protected $url = 'https://cdn.rebrickable.com/media/downloads/colors.csv.gz';

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
            'rgb',
            'is_trans',
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
        $this->truncateTable(new Color());
        $this->importColors();
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
        $this->info('>> Please wait while we import all the Colors from Rebrickable <<');
    }

    /**
     * Process to add sets to database
     *
     * @return void
     */
    protected function importColors()
    {
        $this->updateStatus('Importing Rebrickable Colors into Database...');

        $processed = $this->processed;
        $zero = null;

        $this->lazyCollection()
            ->chunk(1000)
            ->each(function ($colors) use (&$processed) {
                $colorList = [];
                foreach ($colors as $colorRow) {
                    $color = $this->keys->combine(str_getcsv($colorRow), ',');

                    //Issue on Rebrickable where they assign an id=0, which messes with the auto increment
                    if ($color['id'] == 0) {
                        $zero = $color->toArray();
                        continue;
                    }

                    $colorList[] = [
                        'id' => intval($color['id']),
                        'name' => $color['name'],
                        'rgb' => $color['rgb'],
                        'is_trans' => $color['is_trans'],
                    ];

                    $processed++;
                }

                if (count($colorList)) {
                    Color::insert($colorList);
                }

                if ($zero) {
                    $zeroColor = Color::create($zero);
                    $zeroColor->update(['id' => 0]);
                }
            });

        $this->processed = $processed;
    }
}

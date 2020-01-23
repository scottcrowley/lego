<?php

namespace App\Console\Commands;

use App\Color;
use Illuminate\Console\Command;

class ImportColors extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-colors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all colors from Rebrickable';

    /**
     * Rebrickable Themes
     *
     * @var null
     */
    protected $colors = null;

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
        $this->setupApiLegoInstance();
        $this->truncateTable(new Color());
        $this->getRebrickableColors();
        $this->importColors();
        $this->goodbye();
    }

    /**
     * Display command details and request confirmation to continue
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('>> Please wait while we import all the colors from Rebrickable <<');
    }

    /**
     * Retrieve all colors from Rebrickable
     *
     * @return void
     */
    protected function getRebrickableColors()
    {
        $this->updateStatus('Getting all Colors from Rebrickable...');

        $this->colors = $this->api->getAll('colors');
        $this->info('=========== '.count($this->colors).' colors retrieved.');
    }

    /**
     * Process to add colors to database
     *
     * @return void
     */
    protected function importColors()
    {
        $this->updateStatus('Importing Rebrickable Colors into Database...');

        $colors = $this->colors;
        dd($colors);

        if (! $colors->count()) {
            $this->warn('** No Colors retrieved from Rebrickable **');
            $this->goodbye();
        }

        $progress = $this->output->createProgressBar($colors->count());
        $progress->start();

        Color::create([
            'id' => -1,
            'name' => '[Unknown]',
            'rgb' => '0033B2',
            'is_trans' => 'f',
        ]);

        foreach ($colors as $color) {
            Color::create([
                'id' => $color['id'],
                'name' => $color['name'],
                'rgb' => $color['rgb'],
                'is_trans' => $color['is_trans'],
            ]);

            $this->processed++;
            $progress->advance();
        }

        $progress->finish();
    }
}

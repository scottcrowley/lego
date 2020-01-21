<?php

namespace App\Console\Commands;

use App\Theme;
use Illuminate\Console\Command;

class ImportThemes extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-themes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all themes from Rebrickable';

    /**
     * Rebrickable Themes
     *
     * @var null
     */
    protected $themes = null;

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
        $this->truncateTable(new Theme(), true);
        $this->getRebrickableThemes();
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
     * Retrieve all themes from Rebrickable
     *
     * @return void
     */
    protected function getRebrickableThemes()
    {
        $this->updateStatus('Getting all Themes from Rebrickable...');

        $this->themes = $this->api->getAll('themes');
        $this->info('=========== '.count($this->themes).' themes retrieved.');
    }

    /**
     * Process to add themes to database
     *
     * @return void
     */
    protected function importThemes()
    {
        $this->updateStatus('Importing Rebrickable Themes into Database...');

        $themes = $this->themes;

        if (! $themes->count()) {
            $this->warn('** No Themes retrieved from Rebrickable **');
            $this->goodbye();
        }

        $progress = $this->output->createProgressBar($themes->count());
        $progress->start();

        foreach ($themes as $theme) {
            Theme::create([
                'id' => $theme['id'],
                'name' => $theme['name'],
                'parent_id' => $theme['parent_id'],
            ]);

            $this->processed++;
            $progress->advance();
        }

        $progress->finish();
    }
}

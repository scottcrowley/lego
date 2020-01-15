<?php

namespace App\Console\Commands;

use App\Theme;
use Illuminate\Console\Command;
use App\Gateways\RebrickableApiLego;
use Illuminate\Support\Facades\Schema;

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
     * RebrickableApiLego instance
     *
     * @var null
     */
    protected $api = null;

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
        $this->setupApiInstance();
        $this->truncateTable();
        $this->getRebrickableThemes();
        $this->importThemes();

        $this->info('');
        $this->goodbye();
    }

    protected function start()
    {
        $this->info('>> Please wait while we import all the themes from Rebrickable <<');
    }

    protected function setupApiInstance()
    {
        $this->updateStatus('Setting up api instance...');

        $this->api = new RebrickableApiLego();
    }

    protected function truncateTable()
    {
        $this->updateStatus('Truncating table...');

        Schema::disableForeignKeyConstraints();
        Theme::truncate();
        Schema::enableForeignKeyConstraints();
    }

    protected function getRebrickableThemes()
    {
        $this->updateStatus('Getting all Themes from Rebrickable...');

        $this->themes = $this->api->getAll('themes');
        $this->info('=========== '.count($this->themes).' themes retrieved.');
    }

    protected function importThemes()
    {
        $this->updateStatus('Importing Rebrickable Themes into Database...');

        $themes = $this->themes;

        if (! $themes->count()) {
            return false;
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

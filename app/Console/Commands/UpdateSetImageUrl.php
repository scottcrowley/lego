<?php

namespace App\Console\Commands;

use App\Set;
use App\SetImageUrl;
use Illuminate\Console\Command;
use App\Gateways\RebrickableApiLego;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\LazyCollection;

class UpdateSetImageUrl extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:set-image-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the image url for all sets. Data is retrieved from Rebrickable';

    /**
     * Sets from database
     *
     * @var null
     */
    protected $allDbSets = null;

    /**
     * Sets from rebrickable
     *
     * @var null
     */
    protected $sets = null;

    /**
     * Missing sets from database
     *
     * @var array
     */
    protected $missingSets = [];

    /**
     * RebrickableApiLego instance
     *
     * @var null
     */
    protected $api = null;

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
        if (! $this->start()) {
            return false;
        }

        $this->processStart = microtime(true);

        $this->info('');
        $this->setupApiInstance();
        $this->truncateTable();
        $this->getAllDbSets();
        $this->getRebrickableSets();
        $this->processSets();
        $this->displayMissingSets();

        $this->info('');
        $this->goodbye();
    }

    protected function start()
    {
        $this->info('>> It is advisable to run the lego:import-sets command before running this command <<');
        $this->info('>> This command will add set image urls for all sets on Rebrickable, if applicable <<');
        $this->info('>> This command will also overwrite all images listed in the set-image-urls table <<');
        return $this->confirm('This command can take a VERY long time to execute. Continue?');
    }

    protected function setupApiInstance()
    {
        $this->updateStatus('Setting up api instance...');

        $this->api = new RebrickableApiLego;
    }

    protected function truncateTable()
    {
        $this->updateStatus('Truncating table...');

        Schema::disableForeignKeyConstraints();
        SetImageUrl::truncate();
        Schema::enableForeignKeyConstraints();
    }

    protected function getAllDbSets()
    {
        $this->updateStatus('Getting Sets from database...');

        $this->allDbSets = Set::all()->pluck('set_num');

        $this->info('=========== '.count($this->allDbSets).' Sets retrieved.');

        return $this->allDbSets;
    }

    protected function getRebrickableSets()
    {
        $this->updateStatus('Getting Sets from Rebrickable...');

        $this->sets = $this->api->getAllSets();

        $this->info('=========== '.count($this->sets).' Sets retrieved.');
    }

    protected function processSets()
    {
        $this->updateStatus('Processing new Set images...');

        $sets = $this->sets;

        if (! $sets->count()) {
            $this->warn('** No Sets found on the Rebrickable site **');
            $this->goodbye();
        }

        $processed = $this->processed;
        $allDbSets = $this->allDbSets;
        $missingSets = $this->missingSets;

        $setsProgress = $this->output->createProgressBar($sets->count());
        $setsProgress->start();

        LazyCollection::make(function () use ($sets) {
            foreach ($sets as $set) {
                yield $set;
            }
        })->chunk(1000)->each(function ($allSets) use (&$setsProgress, $allDbSets, &$missingSets, &$processed) {
            $imgList = [];
            foreach ($allSets as $set) {
                if (! $allDbSets->contains($set['set_num'])) {
                    $missingSets[] = $set['set_num'];
                    continue;
                }

                if ($set['set_img_url']) {
                    $imgList[] = [
                        'set_num' => $set['set_num'],
                        'image_url' => $set['set_img_url']
                    ];

                    $processed++;
                }

                $setsProgress->advance();
            }

            if (count($imgList)) {
                SetImageUrl::insert($imgList);
            }
        });

        $this->missingSets = $missingSets;
        $this->processed = $processed;

        $setsProgress->finish();
    }

    protected function displayMissingSets()
    {
        if (count($this->missingSets)) {
            $this->info('');
            $this->info('');
            $missing = implode(', ', $this->missingSets);
            $this->warn('The following '.count($this->missingSets).' Sets are missing from the database: '.$missing);
        }
    }
}

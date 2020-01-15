<?php

namespace App\Console\Commands;

use App\Set;
use App\SetImageUrl;
use Illuminate\Console\Command;
use App\Gateways\RebrickableApiLego;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\LazyCollection;

class ImportSets extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-sets 
                            {--theme= : Update only sets with this theme_id}
                            {--start= : Where in the Set collection to begin processing}
                            {--end= : Where in the Set collection to stop processing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all sets from Rebrickable';

    /**
     * RebrickableApiLego instance
     *
     * @var null
     */
    protected $api = null;

    /**
     * Rebrickable Sets
     *
     * @var null
     */
    protected $sets = null;

    /**
     * Set start option
     *
     * @var int
     */
    protected $start = 0;

    /**
     * Set end option
     *
     * @var int
     */
    protected $end = 0;

    /**
     * Splice begin position.
     *
     * @var int
     */
    protected $begin = 0;

    /**
     * Limit results. $end - $start
     *
     * @var int
     */
    protected $limit = 0;

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

        $this->start = ($this->option('start')) ? ((int) $this->option('start')) : 1;
        $this->end = ($this->option('end')) ? ((int) $this->option('end')) : 0;

        $this->info('');
        if ($this->okToTruncate()) {
            $this->truncateTable();
        }
        $this->setupApiInstance();
        $this->getRebrickableSets();
        // $this->sets = $this->spliceCollection($this->sets);
        $this->importSets();

        $this->info('');
        $this->goodbye();
    }

    protected function start()
    {
        $this->info('');
        $this->info('');
        $this->info('>> To avoid issues, please use the lego:import-themes command before running this command <<');
        return $this->confirm('This process can take a VERY long time to execute. Continue?');
    }

    protected function setupApiInstance()
    {
        $this->updateStatus('Setting up api instance...');

        $this->api = new RebrickableApiLego();
    }

    protected function okToTruncate()
    {
        return $this->confirm('Do you want to overwrite all Sets that are currently in the database?');
    }

    protected function truncateTable()
    {
        $this->updateStatus('Truncating table...');

        Schema::disableForeignKeyConstraints();
        Set::truncate();
        SetImageUrl::truncate();
        Schema::enableForeignKeyConstraints();
    }

    protected function getRebrickableSets()
    {
        $this->updateStatus('Getting Sets from Rebrickable...');

        if ($this->option('theme')) {
            $this->api->setUrlParam('theme_id', $this->option('theme'));
        }

        $this->api->setUrlParam('ordering', 'set_num');

        if ($this->end > 0 && $this->end <= 1000) {
            $this->sets = $this->api->getAll('sets', 1);
            $this->info('=========== '.count($this->sets).' limited Sets retrieved.');
            return;
        }

        $this->sets = $this->api->getAllSets();
        $this->info('=========== '.count($this->sets).' Sets retrieved.');
    }

    protected function importSets()
    {
        $this->updateStatus('Importing Rebrickable Sets into Database...');

        $processed = 0;
        $sets = $this->sets;

        if (! $sets->count()) {
            return false;
        }
        $progress = $this->output->createProgressBar($sets->count());
        $progress->start();

        LazyCollection::make(function () use ($sets) {
            foreach ($sets as $set) {
                yield $set;
            }
        })->chunk(5000)->each(function ($allSets) use (&$progress, &$processed) {
            $setList = $imgList = [];
            foreach ($allSets as $set) {
                $setList[] = [
                    'set_num' => $set['set_num'],
                    'name' => $set['name'],
                    'year' => $set['year'],
                    'theme_id' => $set['theme_id'],
                    'num_parts' => $set['num_parts'],
                ];

                if ($set['set_img_url']) {
                    $imgList[] = [
                        'set_num' => $set['set_num'],
                        'image_url' => $set['set_img_url']
                    ];
                }

                $processed++;
                $progress->advance();
            }
            Set::insert($setList);

            if (count($imgList)) {
                SetImageUrl::insert($imgList);
            }
        });

        $this->processed = $processed;

        // foreach ($sets as $set) {
        //     Set::updateOrCreate(
        //         ['set_num' => $set['set_num']],
        //         [
        //             'name' => $set['name'],
        //             'year' => $set['year'],
        //             'theme_id' => $set['theme_id'],
        //             'num_parts' => $set['num_parts'],
        //         ]
        //     );

        //     if ($set['set_img_url']) {
        //         SetImageUrl::updateOrCreate(
        //             ['set_num' => $set['set_num']],
        //             ['image_url' => $set['set_img_url']]
        //         );
        //     }

        //     $this->processed++;
        //     $progress->advance();
        // }

        $progress->finish();
    }
}

<?php

namespace App\Console\Commands;

use App\Set;
use App\SetImageUrl;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;

class ImportSets extends Command
{
    use CommandHelpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lego:import-sets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all sets from Rebrickable';

    /**
     * Rebrickable Sets
     *
     * @var null
     */
    protected $sets = null;

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
        $this->truncateTable(new Set(), true);
        $this->truncateTable(new SetImageUrl(), true);
        $this->setupApiLegoInstance();
        $this->getRebrickableSets();
        $this->importSets();
        $this->goodbye();
    }

    /**
     * Display command details and request confirmation to continue
     *
     * @return bool
     */
    protected function start()
    {
        $this->info('');
        $this->info('>> To avoid issues, please use the lego:import-themes command before running this command <<');
        $this->info('>> This command will import all Sets and create set image urls if applicable <<');
        $this->info('>> This command will also overwrite any data in the sets and set_image_urls tables  <<');
        return $this->confirm('This process can take a VERY long time to execute. Continue?');
    }

    /**
     * Retrieve all sets from Rebrickable
     *
     * @return void
     */
    protected function getRebrickableSets()
    {
        $this->updateStatus('Getting Sets from Rebrickable...');

        $this->sets = $this->api->getAllSets();

        $this->info('=========== '.count($this->sets).' Sets retrieved.');
    }

    /**
     * Process for importing sets
     *
     * @return void
     */
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
        })->chunk(1000)->each(function ($allSets) use (&$progress, &$processed) {
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

        $progress->finish();
    }
}
